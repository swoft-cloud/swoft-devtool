<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2018/3/21
 * Time: 上午11:44
 */

namespace Swoft\Devtool\Controller;

use Swoft\Http\Message\Server\Request;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;

/**
 * Class RouteController
 * @package Swoft\Devtool\Controller
 * @Controller("/__devtool")
 */
class RouteController
{
    /**
     * @RequestMapping("/__devtool/http/routes", method=RequestMethod::GET)
     * @param Request $request
     * @return array
     */
    public function httpRoutes(Request $request): array
    {
        $type = $request->query('type');
        $types = [
            'static' => 1,
            'regular' => 1,
            'vague' => 1,
            'cached' => 1,
        ];

        /** @var \Swoft\Http\Server\Router\HandlerMapping $router */
        $router = \bean('httpRouter');

        // one type
        if (isset($types[$type])) {
            $getter = 'get' . \ucfirst($type) . 'Routes';

            return $router->$getter();
        }

        // all
        return [
            'static' => $router->getStaticRoutes(),
            'regular' => $router->getRegularRoutes(),
            'vague' => $router->getVagueRoutes(),
            'cached' => $router->getCacheRoutes(),
        ];
    }
}
