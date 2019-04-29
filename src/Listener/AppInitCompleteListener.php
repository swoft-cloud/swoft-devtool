<?php declare(strict_types=1);

namespace Swoft\Devtool\Listener;

use Swoft\Devtool\DevTool;
use Swoft\Devtool\WebSocket\DevToolController;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;

/**
 * Class AppInitCompleteListener
 * @since 2.0
 * @package Swoft\Devtool\Listener
 */
class AppInitCompleteListener implements EventHandlerInterface
{
    /**
     * @param EventInterface $event
     * @throws \Throwable
     */
    public function handle(EventInterface $event): void
    {
        // if websocket is enabled. register a ws route
        if (\Swoft::hasBean('wsRouter')) {
            /* @see \Swoft\WebSocket\Server\Router\HandlerMapping::add() */
            // \bean('wsRouter')->add(DevTool::ROUTE_PREFIX, DevToolController::class);
        }
    }
}
