<?php

namespace Swoft\Devtool\Command;

use Leuffen\TextTemplate\TextTemplate;
use Swoft\App;
use Swoft\Console\Bean\Annotation\Command;
use Swoft\Console\Input\Input;
use Swoft\Console\Output\Output;

/**
 * Generate some common application template classes
 * @Command(coroutine=false)
 * @package Swoft\Devtool\Command
 */
class GenCommand
{
    /**
     * @var TextTemplate
     */
    private $parser;

    /**
     * @var string
     */
    public $defaultTplPath;

    /** @var string */
    private $tplFileSuffix = '.stub';

    public function init()
    {
        $this->parser = new TextTemplate();
        // {include file="some.tpl"}
        $this->parser->addFunction('incude', function (
            $paramArr,
            $command,
            $context,
            $cmdParam
        ) {
            return file_get_contents($paramArr['file']);
        });

        $this->defaultTplPath = \dirname(__DIR__, 2) . '/res/templates/';
    }

    /**
     * Generate CLI command controller class
     * @Arguments
     *   name       The class name(eg. <info>demo</info>)
     *   dir        The class file save dir(default: <info>@app/Commands</info>)
     * @Options
     *   --suffix STRING     The class name suffix. default is: <info>Command</info>
     *   --tpl-file STRING   The template file name. default is: <info>command</info>(fullname: command.class.stub)
     *   --tpl-dir STRING    The template file dir path.(default: devtool/res/templates)
     * @param Input $in
     * @param Output $out
     * @return int
     * @throws \InvalidArgumentException
     */
    public function command(Input $in, Output $out): int
    {
        // \var_dump($this);
        $config = [
            'dir' => '@app/Commands',
            'namespace' => 'App\\Commands\\',
            'suffix' => 'Command',
            'command' => 'test',
            'name' => 'test',
            'tplFilename' => $in->getOpt('tpl-file') ?: 'command',
            'tplDir' => $in->getOpt('tpl-dir') ?: $this->defaultTplPath,
        ];

        if ($dir = $in->getArg(0)) {
            $config['dir'] = $dir;
            $config['dir'] = App::getAlias($config['dir']);
        }

        $config['tplFile'] = \rtrim($config['tplDir'], '/ ') . '/' . $config['tplFilename'];

        \print_r($config);

        return 0;
    }

    /**
     * Generate http controller class
     * @return int
     */
    public function controller(): int
    {
        return 0;
    }

    /**
     * Generate rpc service class
     * @return int
     */
    public function rpcService(): int
    {
        return 0;
    }

    /**
     * Generate http middleware class
     * @return int
     */
    public function middleware(): int
    {
        return 0;
    }

    /**
     * Generate user task class
     * @return int
     */
    public function task(): int
    {
        return 0;
    }

    /**
     * Generate user custom process class
     * @return int
     */
    public function process(): int
    {
        return 0;
    }
}
