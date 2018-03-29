<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2018/3/21
 * Time: 下午2:08
 */

namespace Swoft\Devtool\Controller;

use Swoft\App;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;
use Swoft\View\Bean\Annotation\View;

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
     * View(template="@devtool/res/views/index.php")
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function index()
    {
        $data = [
            '@devtool' => App::getAlias('@devtool')
        ];

        return \view(App::getAlias('@devtool/res/views/index.php'), $data);
    }
}
