<?php

namespace Swoft\Devtool\Command;

use Swoft\App;
use Swoft\Console\Bean\Annotation\Command;
use Swoft\Helper\ProcessHelper;

/**
 * Some commands for application dev[<cyan>built-in</cyan>]
 * @package Swoft\Devtool\Command
 * @Command(coroutine=false)
 */
class DevCommand
{
    /**
     * Used to publish the internal resources of the module to the 'public' directory
     * @throws \InvalidArgumentException
     */
    public function publish(): int
    {
        $assetDir = App::getAlias('@devtool') . '/web/dist/devtool';
        $targetDir = App::getAlias('@root') . '/public';

        list($code, $return, $error) = ProcessHelper::run("cp $assetDir $targetDir", App::getAlias('@root'));

        if ($code !== 0) {
            \output()->colored("Publish devtool assets to $targetDir is failed!", 'error');
            \output()->writeln($error);

            return -2;
        }

        \output()->colored("Publish devtool assets to $targetDir is OK!", 'info');

        return 0;
    }

}
