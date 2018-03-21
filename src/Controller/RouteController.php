<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2018/3/21
 * Time: 上午11:44
 */

namespace Swoft\Devtool\Controller;

use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Router\HandlerMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;

/**
 * Class RouteController
 * @package Swoft\Devtool\Controller
 * @Controller("/__devtool/routes")
 */
class RouteController
{
    /**
     * @RequestMapping("/__devtool/routes", method=RequestMethod::GET)
     */
    public function routes(): array
    {
        /** @var HandlerMapping $router */
        $router = \bean('httpRouter');

        return [
            'static' => $router->getStaticRoutes(),
            'regular' => $router->getRegularRoutes(),
            'vague' => $router->getVagueRoutes(),
            'cached' => $router->getCacheRoutes(),
        ];
    }
}
