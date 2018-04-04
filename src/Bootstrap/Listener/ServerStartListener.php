<?php

namespace Swoft\Devtool\Bootstrap\Listener;

use Swoft\Bean\Annotation\ServerListener;
use Swoft\Bootstrap\Listeners\Interfaces\BeforeStartInterface;
use Swoft\Bootstrap\Listeners\Interfaces\WorkerStartInterface;
use Swoft\Bootstrap\SwooleEvent;
use Swoft\Bootstrap\Server\AbstractServer;
use Swoft\Devtool\DevTool;
use Swoft\Memory\Table;
use Swoole\Server;

/**
 * Class ServerStartListener
 * @package Swoft\Devtool\Bootstrap\Listener
 * @ServerListener(event={
 *     SwooleEvent::ON_BEFORE_START,
 *     SwooleEvent::ON_WORKER_START
 * })
 */
class ServerStartListener implements BeforeStartInterface, WorkerStartInterface
{
    /**
     * @param AbstractServer $server
     * @throws \InvalidArgumentException
     */
    public function onBeforeStart(AbstractServer &$server)
    {
        // DevTool::$table = new Table('runtime-devtool', 8 * 1024);
    }

    /**
     * @param Server $server
     * @param int $workerId
     * @param bool $isWorker
     */
    public function onWorkerStart(Server $server, int $workerId, bool $isWorker)
    {
        \output()->writeln(\sprintf(
            'Children process start successful. ' .
            'PID <magenta>%s</magenta>, Worker Id <magenta>%s</magenta>, Role <info>%s</info> process',
            $server->worker_pid,
            $workerId,
            $isWorker ? 'Work' : 'Task'
        ));
    }
}
