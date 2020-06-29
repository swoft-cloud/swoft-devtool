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
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Http\Server\Annotation\Mapping\RequestMethod;
use function bean;

/**
 * Class RouteController
 *
 * @Controller("/__devtool/rpc")
 */
class RpcController
{
    /**
     * @RequestMapping("rpc/routes", method=RequestMethod::GET)
     * @return array
     */
    public function rpcRoutes(): array
    {
        if (!BeanFactory::hasBean('serviceRouter')) {
            return [];
        }

        /** @var \Swoft\Rpc\Server\Router\Router $router */
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
