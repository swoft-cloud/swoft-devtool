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

use Swoft\Http\Message\Server\Request;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;
// use Swoft\View\Bean\Annotation\View;
// use Swoft\Http\Message\Server\Response;

/**
 * Class GenController
 * @Controller(prefix="/__devtool/gen")
 * @package Swoft\Devtool\Controller
 */
class GenController
{
    /**
     * this is a example action
     * @RequestMapping(route="/__devtool/gen", method=RequestMethod::GET)
     * @param Request $request
     * @return array
     */
    public function index(Request $request): array
    {
        // $request->isAjax();

        return ['item0', 'item1'];
    }
}
