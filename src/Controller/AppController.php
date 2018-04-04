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
use Swoft\Http\Server\Payload;

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
     * @return array
     */
    public function index(): array
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
     * get all registered application events list
     * @RequestMapping(route="events", method=RequestMethod::GET)
     * @param Request $request
     * @return Payload
     */
    public function events(Request $request): Payload
    {
        /** @var \Swoft\Event\EventManager $em */
        $em = \bean('eventManager');

        if ($event = \trim($request->query('name'))) {
            $queue = $em->getListenerQueue($event);

            if (!$queue) {
                return Payload::make(['msg' => 'event name is invalid: ' . $event],404);
            }

            $classes = [];

            foreach ($queue->getIterator() as $listener) {
                $classes[] = \get_parent_class($listener);
            }

            return Payload::make($classes);
        }

        return Payload::make($em->getListenedEvents());
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
        /** @var \Swoft\Http\Server\ServerDispatcher $dispatcher */
        $dispatcher = \bean('serverDispatcher');

        if (\method_exists($dispatcher, 'getMiddlewares')) {
            return $dispatcher->getMiddlewares();
        }

        return [];
    }

    /**
     * get all registered rpc middleware list
     * @RequestMapping(route="rpc/middles", method=RequestMethod::GET)
     * @return array
     */
    public function rpcMiddles(): array
    {
        /** @var \Swoft\Rpc\Server\ServiceDispatcher $dispatcher */
        $dispatcher = \bean('serviceDispatcher');

        if (\method_exists($dispatcher, 'getMiddlewares')) {
            return $dispatcher->getMiddlewares();
        }

        return [];
    }
}
