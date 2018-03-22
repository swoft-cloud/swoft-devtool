<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2018/3/22
 * Time: 下午4:55
 */

namespace Swoft\Devtool\WebSocket;

use Swoft\Http\Message\Server\Request;
use Swoft\Http\Message\Server\Response;
use Swoft\WebSocket\Server\Bean\Annotation\WebSocket;

/**
 * Class DevToolController
 * @package Swoft\Devtool\WebSocket
 * @WebSocket("/__devtool")
 */
class DevToolController
{
    /**
     * 在这里你可以验证请求信息
     * - 返回bool来决定是否进行握手
     * @param Request $request
     * @param Response $response
     * @return bool
     */
    public function checkHandshake(Request $request, Response $response): bool
    {
        // some validate logic ...

        return true;
    }

    /**
     * @param Request $request
     */
    public function onOpen(Request $request)
    {

    }

    public function onMessage()
    {

    }

    public function onClose()
    {

    }
}
