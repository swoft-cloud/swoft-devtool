<?php
/**
 * This file is part of Swoft.
 *
 * @link https://swoft.org
 * @document https://doc.swoft.org
 * @contact group@swoft.org
 * @license https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Devtool\Middleware;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Swoft\App;
use Swoft\Bean\Annotation\Bean;
use Swoft\Devtool\DevTool;
use Swoft\Http\Message\Middleware\MiddlewareInterface;
use Swoft\Http\Message\Server\Request;

/**
 * Class DevToolMiddleware - Custom middleware
 * @Bean()
 * @package Swoft\Devtool\Middleware
 */
class DevToolMiddleware implements MiddlewareInterface
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface|Request $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \InvalidArgumentException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // before request handle
        $path = $request->getUri()->getPath();

        \printf("[%s] %s request uri %s \n", \date('Y/m/d H:i:s'), $request->getMethod(), $path);

        // if not is ajax, always render vue index file.
        if (0 === \strpos($path, DevTool::ROUTE_PREFIX) && !$request->isAjax()) {
            return \view(App::getAlias('@devtool/web/index.html'), []);
        }

        $response = $handler->handle($request);

        // after request handle

        return $response->withAddedHeader('Swoft-DevTool-Version', '1.0.0');
    }
}
