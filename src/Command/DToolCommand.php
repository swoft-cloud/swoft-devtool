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

use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandArgument;
use Swoft\Console\Annotation\Mapping\CommandMapping;
use Swoft\Console\Helper\Show;
use Swoft\Console\Input\Input;
use Swoft\Stdlib\Helper\Sys;

/**
 * There are some commands for application dev[by <cyan>devtool</cyan>]
 * @Command("dtool", coroutine=false)
 */
class DToolCommand
{
    private $issueUrl = 'https://github.com/swoft-cloud/swoft/issues?q=is%3Aissue+is%3Aopen+sort%3Aupdated-desc';

    /**
     * Open github issues page
     *
     * @CommandMapping(alias="issue")
     *
     * @CommandArgument("operate", mode=Command::ARG_REQUIRED, default="open",
     *      desc="operate command for issues. allow: open, search, create"
     * )
     *
     * @param Input $input
     *
     * @example
     *   {fullCmd} open      Open github issues page
     *   {fullCmd} search 'websocket'
     *   {fullCmd} create 'bug xxx'
     */
    public function issues(Input $input): void
    {
        $opName = $input->getString('operate');
        switch ($opName) {
            case 'open':
                $this->openIssuePage();
                break;
            case 'create':
            case 'report':
                $this->createIssue();
                break;
            case 'search':
                $this->searchIssues();
                break;
        }
    }

    /**
     * Open github issues page
     *
     * Mac：
     * open 'https://swoft.org'
     *
     * Linux:
     * x-www-browser 'https://swoft.org'
     *
     * Windows:
     * cmd /c start https://swoft.org
     */
    public function openIssuePage(): void
    {
        Show::info('will open the github issues page on browser');
        /*
         */
        if (Sys::isMac()) {
            $cmd = 'open';
        } elseif (Sys::isWin()) {
            $cmd = 'cmd /c start';
        } else {
            $cmd = 'x-www-browser';
        }

        Sys::execute($cmd . ' ' . $this->issueUrl);
    }

    /**
     * @return int
     */
    public function createIssue(): int
    {
        return 0;
    }

    /**
     * @return int
     */
    public function searchIssues(): int
    {
        return 0;
    }

    /**
     * @CommandMapping(alias="pub", desc="publish some assets to application direactory")
     */
    public function publish(): void
    {
        Show::info('WIP');
    }
}
