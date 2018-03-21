<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2018/3/21
 * Time: 下午2:08
 */

namespace Swoft\Devtool\Controller;

use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;

/**
 * Class DashboardController
 * @package Swoft\Devtool\Controller
 * @Controller(prefix="/__devtool")
 */
class DashboardController
{
    /**
     * this is a example action
     * @RequestMapping(route="/__devtool", method=RequestMethod::GET)
     * @return array
     */
    public function index(): array
    {
        return ['item0', 'item1'];
    }
}
