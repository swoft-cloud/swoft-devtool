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
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function publish(): int
    {
        $assetDir = App::getAlias('@devtool') . '/web/dist/devtool';
        $targetDir = App::getAlias('@root') . '/public';
        $command = "cp -Rf $assetDir $targetDir";

        \output()->writeln("Will run shell command:\n $command");

        list($code, , $error) = ProcessHelper::run($command, App::getAlias('@root'));

        if ($code !== 0) {
            \output()->colored("Publish devtool assets to $targetDir is failed!", 'error');
            \output()->writeln($error);

            return -2;
        }

        \output()->colored("\nPublish devtool assets to $targetDir is OK!", 'info');

        return 0;
    }

    public function test(): int
    {
        return 0;
    }
}
