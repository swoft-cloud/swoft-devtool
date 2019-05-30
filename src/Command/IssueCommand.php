<?php declare(strict_types=1);

namespace Swoft\Devtool\Command;

use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandMapping;

/**
 * There are some commands for application dev[by <cyan>devtool</cyan>]
 * @Command(coroutine=false)
 */
class IssueCommand
{


    /**
     * Open github issues page
     *
     * @CommandMapping()
     */
    public function open(): void
    {
    /*
    Mac：
    open 'https://swoft.org'

    Linux:
    x-www-browser 'https://swoft.org'

    Windows:
    cmd /c start https://swoft.org
     */
    }

    /**
     * @CommandMapping()
     * @return int
     */
    public function create(): int
    {
        return 0;
    }

    /**
     * @CommandMapping()
     * @return int
     */
    public function report(): int
    {
        return 0;
    }

    /**
     * @return int
     */
    public function search(): int
    {
        return 0;
    }
}
