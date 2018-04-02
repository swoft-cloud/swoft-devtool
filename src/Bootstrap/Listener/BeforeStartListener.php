<?php

namespace Swoft\Devtool\Bootstrap\Listener;

use Swoft\App;
use Swoft\Bean\Annotation\ServerListener;
use Swoft\Bootstrap\Listeners\Interfaces\BeforeStartInterface;
use Swoft\Bootstrap\SwooleEvent;
use Swoft\Bootstrap\Server\AbstractServer;
use Swoft\Devtool\DevTool;
use Swoft\Memory\Table;

/**
 * Class BeforeStartListener
 * @package Swoft\Devtool\Bootstrap\Listener
 * @ServerListener(event=SwooleEvent::ON_BEFORE_START)
 */
class BeforeStartListener implements BeforeStartInterface
{
    /**
     * @param AbstractServer $server
     * @throws \InvalidArgumentException
     */
    public function onBeforeStart(AbstractServer &$server)
    {

        // DevTool::$table = new Table('runtime-devtool', 8096);
    }
}
