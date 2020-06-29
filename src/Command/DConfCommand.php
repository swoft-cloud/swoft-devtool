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
use Swoft\Console\Input\Input;
use Swoft\Console\Output\Output;

/**
 * There are some help command for application[by <cyan>devtool</cyan>]
 *
 * @Command(coroutine=false)
 */
class DConfCommand
{
    /**
     * @CommandMapping(desc="view config for input component")
     * @CommandArgument("name", desc="the component name")
     *
     * @param Input  $input
     * @param Output $output
     */
    public function view(Input $input, Output $output): void
    {
        $output->colored('WIP');
    }

    /**
     * @CommandMapping(desc="edit config for input component")
     * @CommandArgument("name", desc="the component name")
     *
     * @param Input  $input
     * @param Output $output
     */
    public function create(Input $input, Output $output): void
    {
        $output->colored('WIP');
    }

    /**
     * @CommandMapping(desc="edit bean config for input component")
     * @CommandArgument("name", desc="the component name")
     *
     * @param Input  $input
     * @param Output $output
     */
    public function bean(Input $input, Output $output): void
    {
        $output->colored('WIP');
    }
}
