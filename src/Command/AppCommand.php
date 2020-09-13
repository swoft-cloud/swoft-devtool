<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Devtool\Command;

use InvalidArgumentException;
use RuntimeException;
use Swoft;
use Swoft\Annotation\AnnotationRegister;
use Swoft\Bean\BeanFactory;
use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandArgument;
use Swoft\Console\Annotation\Mapping\CommandMapping;
use Swoft\Console\Annotation\Mapping\CommandOption;
use Swoft\Console\Exception\ConsoleErrorException;
use Swoft\Console\Helper\Show;
use Swoft\Console\Input\Input;
use Swoft\Console\Output\Output;
use Swoft\Contract\ComponentInterface;
use Swoft\Devtool\DevTool;
use Swoft\Http\Server\Router\Router;
use Swoft\Stdlib\Helper\DirHelper;
use function count;
use function implode;
use function input;
use function output;
use function strpos;
use function trim;

/**
 * There are some help command for application[by <cyan>devtool</cyan>]
 *
 * @Command(coroutine=false)
 */
class AppCommand
{
    /**
     * init the project, will create runtime dirs
     *
     * @CommandMapping("init", usage="{fullCommand} [arguments] [options]")
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function initApp(): void
    {
        $tmpDir = Swoft::getAlias('@runtime');
        $names  = [
            'logs',
            'uploadfiles'
        ];

        output()->writeln('Create runtime directories: ' . implode(',', $names));

        foreach ($names as $name) {
            DirHelper::make($tmpDir . '/' . $name);
        }

        output()->writeln('<success>OK</success>');
    }

    /**
     * @CommandMapping(alias="beans")
     * @CommandOption("type", type="string", default="sig",
     *     desc="Display the bean names of the type
     *     sig  - singleton
     *     pro  - prototype
     *     req  - request
     *     sess - session
     *     def  - definition"
     * )
     * @CommandOption("include", short="c", type="string", desc="must contains the string on bean name")
     * @CommandOption("exclude", short="e", type="string", desc="must exclude the string on bean name")
     * @param Input  $input
     * @param Output $output
     *
     * @throws ConsoleErrorException
     */
    public function bean(Input $input, Output $output): void
    {
        $names = BeanFactory::getContainer()->getNames();
        $type  = input()->getStringOpt('type', 'sig');

        $realTypes = [
            'sig'  => 'singleton',
            'pro'  => 'prototype',
            'req'  => 'request',
            'sess' => 'session',
            'def'  => 'definition',
        ];

        if (!isset($realTypes[$type])) {
            throw new ConsoleErrorException('invalid bean type: ' . $type);
        }

        $realType = $realTypes[$type];
        $include  = (string)$input->getSameOpt(['include', 'c']);
        $exclude  = (string)$input->getSameOpt(['exclude', 'e']);

        $output->title('application beans');

        if (isset($names[$realType])) {
            $names = $names[$realType];

            if ($include || $exclude) {
                $filtered = [];

                foreach ($names as $name) {
                    if ($exclude && strpos($name, $exclude) !== false) {
                        continue;
                    }

                    if ($include) {
                        if (strpos($name, $include) !== false) {
                            $filtered[] = $name;
                        }
                    } else {
                        $filtered[] = $name;
                    }
                }

                Show::prettyJSON($filtered);
                return;
            }
        }

        Show::prettyJSON($names);
    }

    /**
     * display all registered http routes of the application
     *
     * @CommandMapping("http-routes", alias="hroute, httproute, httproutes")
     * @CommandOption("include", short="c", type="string", desc="must contains the string on route path")
     * @CommandOption("exclude", short="e", type="string", desc="must exclude the string on route path")
     * @CommandOption("no-devtool", type="bool", default="false", desc="exclude all devtool http routes")
     *
     * @param Input  $input
     * @param Output $output
     *
     */
    public function httpRoutes(Input $input, Output $output): void
    {
        /** @var Router $router */
        $router = Swoft::getBean('httpRouter');

        $output->title('HTTP Routes');

        $include  = (string)$input->getSameOpt(['include', 'c']);
        $exclude  = (string)$input->getSameOpt(['exclude', 'e']);
        $filterDt = $input->getBoolOpt('no-devtool');

        if ($filterDt || $include || $exclude) {
            $filter = function (string $path) use ($filterDt, $include, $exclude) {
                if ($exclude) {
                    return strpos($path, $exclude) === false;
                }

                if ($include) {
                    return strpos($path, $include) !== false;
                }

                if ($filterDt) {
                    return strpos($path, DevTool::ROUTE_PREFIX) === false;
                }

                return true;
            };

            $output->writeln($router->toString($filter));
            return;
        }

        // Print all routes
        $output->writeln($router->toString());
    }

    /**
     * display all registered websocket routes of the application
     *
     * @CommandMapping("ws-routes", alias="wsroute, wsroutes, wroute, wroutes")
     *
     * @param Output $output
     */
    public function wsRoutes(Output $output): void
    {
        /** @var Swoft\WebSocket\Server\Router\Router $router */
        $router = Swoft::getBean('wsRouter');

        $data = [];
        foreach ($router->getModules() as $path => $module) {
            $data[] = [
                $path,
                $module['class'],
                $module['messageParser'],
            ];
        }

        $output->table($data, 'WebSocket Modules', [
            'columns' => ['Module Path', 'Module Class', 'Message Parser']
        ]);

        if ($commands = $router->getCommands()) {
            $rows = [];
            foreach ($commands as $path => $groups) {
                foreach ($groups as $cmdId => $command) {
                    $count  = count($router->getCmdMiddlewares($path, $cmdId));
                    $rows[] = [
                        $path,
                        $cmdId,
                        implode('@', $command['handler']),
                        $count,
                    ];
                }
            }

            $output->table($rows, 'WebSocket Commands', [
                'columns' => ['Module Path', 'Command ID', 'Command Handler', 'Middleware Number']
            ]);
            $output->writeln("> Notice: 'Middleware Number' is not contains global middleware");
        }
    }

    /**
     * display all registered tcp routes of the application
     *
     * @CommandMapping("tcp-routes", alias="tcproute, tcproutes, troute, troutes")
     *
     * @param Output $output
     */
    public function tcpRoutes(Output $output): void
    {
        /** @var Swoft\Tcp\Server\Router\Router $router */
        $router = Swoft::getBean('tcpRouter');

        $data = [];
        foreach ($router->getRoutes() as $route) {
            $cmdId  = $route['command'];
            $count  = count($router->getCmdMiddlewares($cmdId));
            $data[] = [
                $cmdId,
                implode('@', $route['handler']),
                $count,
            ];
        }

        $output->table($data, 'TCP Routes', [
            'columns' => ['Command Name', 'Route Handler', 'Middleware Number']
        ]);
        $output->writeln("> Notice: 'Middleware Number' is not contains global middleware");
    }

    /**
     * display all swoft components of the application
     *
     * @CommandMapping(alias="cpt, component")
     *
     * @CommandArgument("name", type="string", desc="display detail for the component")
     * @CommandOption("disabled", type="bool", default="false", desc="display all disabled components")
     * @CommandOption("show-loader", type="bool", default="false", desc="display component autoloader class")
     *
     * @param Input  $input
     * @param Output $output
     */
    public function components(Input $input, Output $output): void
    {
        if ($input->boolOpt('disabled')) {
            $loaders = AnnotationRegister::getDisabledLoaders();
        } else {
            $loaders = AnnotationRegister::getAutoLoaders();
        }

        $metadata  = [];
        $component = $input->getString('name');
        if ($component = trim($component)) {
            foreach ($loaders as $loader) {
                if (!$loader instanceof ComponentInterface) {
                    continue;
                }

                // If found, display and return.
                if ($loader->getName() === $component) {
                    $metadata = $loader->getMetadata();
                    break;
                }
            }

            if ($metadata) {
                $title = "Metadata For Component '{$component}'";
                $output->panel($metadata, $title, [
                    'ucFirst'  => false,
                    'keyStyle' => 'cyan',
                ]);
            } else {
                $output->info("Not found component: {$component}");
            }
            return;
        }

        $loaderInfo = [];
        $showLoader = $input->boolOpt('show-loader');

        // Format loader information
        foreach ($loaders as $loader) {
            if (!$loader instanceof ComponentInterface) {
                continue;
            }

            $col3 = $loader->getDescription() ?: '-';
            if ($showLoader) {
                $col3 = $loader->getClass();
            }

            $loaderInfo[] = [
                $loader->getName() ?: 'UNKNOWN',
                $loader->getVersion() ?: 'UNKNOWN',
                $col3,
            ];
        }

        if (!$loaderInfo) {
            $output->info('Not found any information!');
        }

        $col3title = $showLoader ? 'AutoLoader Class' : 'Description';
        $output->table($loaderInfo, 'Enabled Components', [
            'columns' => ['Component Name', 'Component Version', $col3title]
        ]);
    }
}
