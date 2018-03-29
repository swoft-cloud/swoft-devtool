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
// use Swoft\View\Bean\Annotation\View;
// use Swoft\Http\Message\Server\Response;

/**
 * Class AppController
 * @Controller(prefix="/__devtool/app/")
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
     * @return array
     */
    public function config(Request $request): array
    {
        /** @see Config::toArray() */
        return \bean('config')->toArray();
    }

    /**
     * get app path aliases
     * @RequestMapping(route="config", method=RequestMethod::GET)
     * @param Request $request
     * @return array
     */
    public function pathAlias(Request $request): array
    {
        return [];
    }
}
