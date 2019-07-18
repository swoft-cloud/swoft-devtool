<?php declare(strict_types=1);

namespace Swoft\Devtool\Command;

use ReflectionException;
use function strpos;
use Swoft;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandMapping;
use Swoft\Console\Annotation\Mapping\CommandOption;
use Swoft\Console\Input\Input;
use Swoft\Console\Output\Output;
use Swoft\Devtool\DevTool;
use Swoft\Http\Server\Router\Router;
use Swoft\Stdlib\Helper\Sys;
use function alias;
use function array_shift;
use function explode;
use function trim;

/**
 * There are some commands for application dev[by <cyan>devtool</cyan>]
 * @Command("dinfo", coroutine=false)
 */
class DInfoCommand
{
    /**
     * Print current system environment information
     *
     * @CommandMapping()
     *
     * @param Output $output
     *
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function env(Output $output): void
    {
        $info = [
            // "<bold>System environment info</bold>\n",
            'OS'             => PHP_OS,
            'Php version'    => PHP_VERSION,
            'Swoole version' => SWOOLE_VERSION,
            'Swoft version'  => Swoft::VERSION,
            'App Name'       => config('name', 'unknown'),
            'Base Path'      => alias('@base'),
        ];

        $output->aList($info, 'System Environment Info');
    }

    /**
     * display info for the swoole extension
     *
     * @CommandMapping(alias="swo,sw")
     * @param Output $output
     */
    public function swoole(Output $output): void
    {
        [$zero, $ret,] = Sys::run('php --ri swoole');

        // no swoole ext
        if ($zero !== 0) {
            $output->error(trim($ret));
            return;
        }

        $info = $dirt = [];
        $list = explode("\n\n", $ret);

        $information = explode("\n", $list[1]);
        foreach ($information as $line) {
            $info[] = explode(' => ', $line, 2);
            // $info[$k] = $v;
        }

        $directives = explode("\n", trim($list[2]));
        array_shift($directives);

        foreach ($directives as $line) {
            $dirt[] = explode(' => ', $line, 2);
        }

        $output->title('information for the swoole extension');
        $output->table($info, 'basic information', [
            'columns'   => ['name', 'value'],
            'bodyStyle' => 'info'
        ]);

        $output->table($dirt, 'directive config', [
            'columns' => ['Directive', 'Local Value => Master Value']
        ]);
    }

    /**
     * @CommandMapping("http-routes", alias="hroute,httproute,httproutes")
     * @CommandOption("include", short="c", type="string", desc="must contains the string on route path")
     * @CommandOption("exclude", short="e", type="string", desc="must exclude the string on route path")
     * @CommandOption("no-devtool", type="bool", default="false", desc="exclude all devtool http routes")
     * @param Input  $input
     * @param Output $output
     *
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function httpRoutes(Input $input, Output $output): void
    {
        /** @var Router $router */
        $router = Swoft::getBean('httpRouter');

        $output->title('http routes');

        $include = (string)$input->getSameOpt(['include', 'c']);
        $exclude = (string)$input->getSameOpt(['exclude', 'e']);
        $filterDt = $input->getBoolOpt('no-devtool');

        if ($filterDt || $include || $exclude) {
            $filter = function (string $path) use ($filterDt, $include, $exclude) {
                if ($exclude) {
                    return strpos($path, $exclude) === false;
                }

                if ($include) {
                    return strpos($path, $exclude) !== false;
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
}
