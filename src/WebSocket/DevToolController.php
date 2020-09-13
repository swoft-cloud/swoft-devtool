<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Devtool\WebSocket;

use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

// use Swoft\WebSocket\Server\Bean\Annotation\WebSocket;

/**
 * Class DevToolController
 *
 * @see     \Swoft\WebSocket\Server\HandlerInterface
 * @package Swoft\Devtool\WebSocket
 * - Remove dependency on 'websocket-server'
 * WsModule("/__devtool")
 */
class DevToolController
{
    /**
     * {@inheritdoc}
     */
    public function checkHandshake(Request $request, Response $response): array
    {
        return [0, $response];
    }

    /**
     * @param Server  $server
     * @param Request $request
     * @param int     $fd
     */
    public function onOpen(Server $server, Request $request, int $fd): void
    {
        $server->push($fd, 'hello, welcome to devtool! :)');
    }

    /**
     * @param Server $server
     * @param Frame  $frame
     */
    public function onMessage(Server $server, Frame $frame): void
    {
        $server->push($frame->fd, 'hello, I have received your message: ' . $frame->data);
    }

    /**
     * @param Server $server
     * @param int    $fd
     */
    public function onClose(Server $server, int $fd): void
    {
        // $server->push($fd, 'ooo, goodbye! :)');
    }
}
