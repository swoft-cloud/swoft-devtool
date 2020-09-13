<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Devtool\Http\Controller;

use Swoft\Bean\BeanFactory;
use Swoft\Http\Message\Request;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Http\Server\Annotation\Mapping\RequestMethod;
use Throwable;
use function bean;

/**
 * Class RouteController
 *
 * @Controller("/__devtool")
 */
class RouteController
{
    /**
     * @RequestMapping("http/routes", method=RequestMethod::GET)
     * @param Request $request
     *
     * @return array|string
     * @throws Throwable
     */
    public function httpRoutes(Request $request)
    {
        $asString = (int)$request->query('asString', 0);

        /** @var \Swoft\Http\Server\Router\Router $router */
        $router = bean('httpRouter');

        if ($asString === 1) {
            return $router->toString();
        }

        return [
            'routes' => $router->getRoutes()
        ];
    }

    /**
     * @RequestMapping("ws/routes", method=RequestMethod::GET)
     * @return array
     * @throws Throwable
     */
    public function wsRoutes(): array
    {
        if (!BeanFactory::hasBean('wsRouter')) {
            return [];
        }

        /** @var \Swoft\WebSocket\Server\Router\HandlerMapping $router */
        $router = bean('wsRouter');

        return $router->getRoutes();
    }

    /**
     * @RequestMapping("rpc/routes", method=RequestMethod::GET)
     * @return array
     */
    public function rpcRoutes(): array
    {
        if (!BeanFactory::hasBean('serviceRouter')) {
            return [];
        }

        /** @var \Swoft\Rpc\Server\Router\HandlerMapping $router */
        $router  = bean('serviceRouter');
        $rawList = $router->getRoutes();
        $routes  = [];

        foreach ($rawList as $key => $route) {
            $routes[] = [
                'serviceKey' => $key,
                'class'      => $route[0],
                'method'     => $route[1],
            ];
        }

        return $routes;
    }
}
