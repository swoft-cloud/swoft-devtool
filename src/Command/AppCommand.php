<?php declare(strict_types=1);

namespace Swoft\Devtool\Command;

use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandMapping;
use Swoft\Console\Helper\Show;
use Swoft\Console\Output\Output;
use Swoft\Stdlib\Helper\DirHelper;
use Swoft\Stdlib\Helper\Sys;

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
     * @Usage
     *   {fullCommand} [arguments] [options]
     * @CommandMapping("init")
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function initApp(): void
    {
        $tmpDir = \Swoft::getAlias('@runtime');
        $names  = [
            'logs',
            'uploadfiles'
        ];

        \output()->writeln('Create runtime directories: ' . \implode(',', $names));

        foreach ($names as $name) {
            DirHelper::make($tmpDir . '/' . $name);
        }

        \output()->writeln('<success>OK</success>');
    }

    /**
     * Print current system environment information
     * @param Output $output
     * @throws \RuntimeException
     */
    public function env(Output $output): void
    {
        $info = [
            // "<bold>System environment info</bold>\n",
            'OS'             => \PHP_OS,
            'Php version'    => \PHP_VERSION,
            'Swoole version' => \SWOOLE_VERSION,
            'Swoft version'  => \Swoft::VERSION,
            'App Name'       => \APP_NAME,
            'Base Path'      => \BASE_PATH,
        ];

        Show::aList($info, 'System Environment Info');
    }

    /**
     * Check current operating environment information
     *
     * @CommandMapping()
     * @param Output $output
     * @throws \RuntimeException
     */
    public function check(Output $output): void
    {
        // Env check
        [$code, $return,] = Sys::run('php --ri swoole');
        $asyncRdsEnabled = $code === 0 ? \strpos($return, 'redis client => enabled') : false;

        $list = [
            "<bold>Runtime environment check</bold>\n",
            'PHP version is greater than 7.1?'    => self::wrap(\PHP_VERSION_ID > 70100, 'current is ' .
                \PHP_VERSION),
            'Swoole extension is installed?'      => self::wrap(\extension_loaded('swoole')),
            'Swoole version is greater than 2.1?' => self::wrap(\version_compare(\SWOOLE_VERSION, '4.0.0', '>='),
                'current is ' . \SWOOLE_VERSION),
            'Swoole async redis is enabled?'      => self::wrap($asyncRdsEnabled),
            'Swoole coroutine is enabled?'        => self::wrap(\class_exists('Swoole\Coroutine', false)),
            "\n<bold>Extensions that conflict with 'swoole'</bold>\n",
            ' - zend'                             => self::wrap(!\extension_loaded('zend'),
                'Please disabled it, otherwise swoole will be affected!', true),
            ' - xdebug'                           => self::wrap(!\extension_loaded('xdebug'),
                'Please disabled it, otherwise swoole will be affected!', true),
            ' - xhprof'                           => self::wrap(!\extension_loaded('xhprof'),
                'Please disabled it, otherwise swoole will be affected!', true),
            ' - blackfire'                        => self::wrap(!\extension_loaded('blackfire'),
                'Please disabled it, otherwise swoole will be affected!', true),
        ];

        $buffer = [];
        $pass   = $total = 0;

        foreach ($list as $question => $value) {
            if (\is_int($question)) {
                $buffer[] = $value;
                continue;
            }

            $total++;

            if ($value[0]) {
                $pass++;
            }

            $question = \str_pad($question, 45);
            $buffer[] = \sprintf('  <comment>%s</comment> %s', $question, $value[1]);
        }

        $buffer[] = "\nCheck total: <bold>$total</bold>, Pass the check: <success>$pass</success>";

        $output->writeln($buffer);
    }

    /**
     * @param             $condition
     * @param string|null $msg
     * @param bool        $showOnFalse
     * @return array
     */
    public static function wrap($condition, string $msg = null, $showOnFalse = false): array
    {
        $result = $condition ? '<success>Yes</success>' : '<red>No</red>';
        $des    = '';

        if ($msg) {
            if ($showOnFalse) {
                $des = !$condition ? " ($msg)" : '';
            } else {
                $des = " ($msg)";
            }
        }

        return [(bool)$condition, $result . $des];
    }
}
