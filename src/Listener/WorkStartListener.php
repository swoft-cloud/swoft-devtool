<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Devtool\Bootstrap\Listener;

use Swoft\Config\Annotation\Mapping\Config;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Server\Swoole\SwooleEvent;
use Swoole\Server;
use Throwable;
use function config;
use function output;
use function sprintf;

/**
 * Class WorkStartListener
 *
 * @package Swoft\Devtool\Bootstrap\Listener
 * @Listener(SwooleEvent::WORKER_START)
 */
class WorkStartListener implements EventHandlerInterface
{
    /**
     * @Config("devtool.appLogToConsole")
     * @var bool
     */
    public $appLogToConsole = false;

    /**
     * @param Server $server
     * @param int    $workerId
     * @param bool   $isWorker
     *
     * @throws Throwable
     */
    public function onWorkerStart(Server $server, int $workerId, bool $isWorker): void
    {
        if (!$enable = config('devtool.enable', false)) {
            return;
        }

        output()->writeln(sprintf(
            'Children process start successful. ' . 'PID <magenta>%s</magenta>, Worker Id <magenta>%s</magenta>, Role <info>%s</info>',
            $server->worker_pid,
            $workerId,
            $isWorker ? 'Worker' : 'Task'
        ));
    }

    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        // TODO: Implement handle() method.
    }
}
