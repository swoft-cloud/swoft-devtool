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

use Swoft;
use Swoft\Http\Message\Request;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Http\Server\Annotation\Mapping\RequestMethod;
use Swoft\Http\Server\Router\Router;
use Throwable;

/**
 * Class RouteController
 *
 * @Controller("/__devtool/http")
 */
class HttpController
{
    /**
     * @RequestMapping("routes", method=RequestMethod::GET)
     * @param Request $request
     *
     * @return array|string
     * @throws Throwable
     */
    public function routes(Request $request)
    {
        /** @var Router $router */
        $router = Swoft::getBean('httpRouter');

        $asString = (int)$request->query('asString', 0);
        if ($asString === 1) {
            return $router->toString();
        }

        return [
            'routes' => $router->getRoutes()
        ];
    }

    /**
     * @RequestMapping("route-dump", method=RequestMethod::GET)
     *
     * @return array|string
     * @throws Throwable
     */
    public function routesDump(): string
    {
        /** @var Router $router */
        $router = Swoft::getBean('httpRouter');

        return $router->toString();
    }
}
