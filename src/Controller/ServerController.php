<?php
/**
 * This file is part of Swoft.
 *
 * @link https://swoft.org
 * @document https://doc.swoft.org
 * @contact group@swoft.org
 * @license https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Devtool\Controller;

use Swoft\App;
use Swoft\Core\Config;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;

/**
 * Class ServerController
 * @Controller(prefix="/__devtool/server/")
 * @package Swoft\Devtool\Controller
 */
class ServerController
{
    /**
     * get server config
     * @RequestMapping(route="config", method=RequestMethod::GET)
     * @param Request $request
     * @return array
     */
    public function config(Request $request): array
    {
        /** @see Config::toArray() */
        $info = [
            'basic' => App::$server->getServerSetting(),
            'http' => App::$server->getHttpSetting(),
            'tcp(rpc)' => App::$server->getTcpSetting(),
        ];

        if (\method_exists(App::$server, 'getWsSettings')) {
            $info['websocket'] = App::$server->getWsSettings();
        }

        return $info;
    }
}
