<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2018/3/19
 * Time: 上午11:32
 */

namespace Swoft\Devtool\Bootstrap\Listener;

use Swoft\App;
use Swoft\Bean\Annotation\ServerListener;
use Swoft\Bootstrap\Listeners\Interfaces\BeforeStartInterface;
use Swoft\Bootstrap\SwooleEvent;
use Swoft\Bootstrap\Server\AbstractServer;

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
        App::setAlias('@devtool', \dirname(__DIR__, 3));
    }
}
