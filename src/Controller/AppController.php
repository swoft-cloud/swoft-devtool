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
use Swoft\Bean\Collector\ServerListenerCollector;
use Swoft\Bean\Collector\SwooleListenerCollector;
use Swoft\Core\Config;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;

/**
 * Class AppController
 * @Controller(prefix="/__devtool/app")
 * @package Swoft\Devtool\Controller
 */
class AppController
{
    /**
     * get app info
     * @RequestMapping(route="info", method=RequestMethod::GET)
     * @param Request $request
     * @return array
     */
    public function index(Request $request): array
    {
        return [
            'os' => \PHP_OS,
            'phpVersion' => \PHP_VERSION,
            'swoVersion' => \SWOOLE_VERSION,
            'swfVersion' => App::version(),
            'basePath' => \BASE_PATH,
        ];
    }

    /**
     * get app config
     * @RequestMapping(route="config", method=RequestMethod::GET)
     * @param Request $request
     * @return array|mixed
     */
    public function config(Request $request)
    {
        if ($key = $request->query('key')) {
            /** @see Config::get() */
            return \bean('config')->get($key);
        }

        /** @see Config::toArray() */
        return \bean('config')->toArray();
    }

    /**
     * get app path aliases
     * @RequestMapping(route="aliases", method=RequestMethod::GET)
     * @return array
     */
    public function pathAliases(): array
    {
        return App::getAliases();
    }

    /**
     * get all registered events list
     * @RequestMapping(route="events", method=RequestMethod::GET)
     * @param Request $request
     * @return array
     */
    public function events(Request $request): array
    {
        // 1 application event
        // 2 server event
        // 3 swoole event
        $type = (int)$request->query('type', 1);

        if ($type === 3) {
            return SwooleListenerCollector::getCollector();
        }

        if ($type === 2) {
            return ServerListenerCollector::getCollector();
        }

        /** @var \Swoft\Event\EventManager $em */
        // $em = \bean('eventManager');

        // $event = $request->getQuery('name');
        // $em->getListenerQueue($event);

        return [];
    }

    /**
     * get all registered components
     * @RequestMapping(route="components", method=RequestMethod::GET)
     * @return array
     */
    public function components(): array
    {
        return [];
    }

    /**
     * get all registered http middleware list
     * @RequestMapping(route="http/middles", method=RequestMethod::GET)
     * @return array
     */
    public function httpMiddles(): array
    {
        return [];
    }

    /**
     * get all registered rpc middleware list
     * @RequestMapping(route="rpc/middles", method=RequestMethod::GET)
     * @return array
     */
    public function rpcMiddles(): array
    {
        return [];
    }
}
