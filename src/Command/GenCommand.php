<?php

namespace Swoft\Devtool\Command;

use Swoft\Console\Bean\Annotation\Mapping;
use Swoft\Devtool\PharCompiler;
use Swoft\Helper\DirHelper;
use Swoft\Console\Bean\Annotation\Command;

/**
 * Generate some common application template classes
 * @Command(coroutine=false)
 * @package Swoft\Devtool\Command
 */
class GenCommand
{
    /**
     * Generate CLI command controller class
     * @return int
     */
    public function command(): int
    {
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
