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

use Psr\Http\Message\ResponseInterface;
use Swoft\App;
use Swoft\Core\Config;
use Swoft\Helper\ProcessHelper;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Message\Server\Response;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;
use Swoft\Http\Server\Payload;

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
     * @return array
     */
    public function config(): array
    {
        $info = [
            'swoole' => App::$server->getServer()->setting,
            'basic' => App::$server->getServerSetting(),
            'http' => App::$server->getHttpSetting(),
            'tcp(rpc)' => App::$server->getTcpSetting(),
        ];

        if (\method_exists(App::$server, 'getWsSettings')) {
            $info['websocket'] = App::$server->getWsSettings();
        }

        return $info;
    }

    /**
     * get php ext list
     * @RequestMapping(route="php-ext-list", method=RequestMethod::GET)
     * @return array
     */
    public function phpExt(): array
    {
        return \get_loaded_extensions();
    }

    /**
     * get swoole server stats
     * @RequestMapping(route="stats", method=RequestMethod::GET)
     * @return Payload
     */
    public function stats(): Payload
    {
        if (!App::$server) {
            return Payload::make(['msg' => 'server is not started'], 404);
        }

        return Payload::make(App::$server->getServer()->stats());
    }

    /**
     * get swoole info
     * @RequestMapping(route="swoole-info", method=RequestMethod::GET)
     * @return Payload
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function swoole(): Payload
    {
        list($code, $return, $error) = ProcessHelper::run('php --ri swoole');

        if ($code) {
            return Payload::make(['msg' => $error], 404);
        }

        // format
        $str = \str_replace("\r\n", "\n", \trim($return));
        list(, $enableStr, $directiveStr) = \explode("\n\n", $str);

        $directive = $this->formatSwooleInfo($directiveStr);
        \array_shift($directive);

        return Payload::make([
            'raw' => $return,
            'enable' => $this->formatSwooleInfo($enableStr),
            'directive' => $directive,
        ]);
    }

    /**
     * @param string $str
     * @return array
     */
    private function formatSwooleInfo(string $str): array
    {
        $data = [];
        $lines = \explode("\n", \trim($str));

        foreach ($lines as $line) {
            list($name, $value) = \explode(' => ', $line);
            $data[] = [
                'name' => $name,
                'value' => $value,
            ];
        }

        return $data;
    }
}
