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

use Swoft\Http\Message\Request;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Http\Server\Annotation\Mapping\RequestMethod;

/**
 * Class GenController
 *
 * @Controller(prefix="/__devtool/gen/")
 */
class GenController
{
    /**
     * This is a example action
     * @RequestMapping(route="/__devtool/gen", method=RequestMethod::GET)
     *
     * @param Request $request
     *
     * @return array
     */
    public function index(Request $request): array
    {
        // $request->isAjax();

        return ['item0', 'item1'];
    }

    /**
     * Generate class file preview
     * @RequestMapping(route="preview", method=RequestMethod::POST)
     *
     * @param Request $request
     *
     * @return array
     */
    public function preview(Request $request): array
    {
        // $data = $request->json();

        return ['item0', 'item1'];
    }

    /**
     * Generate class file
     * @RequestMapping(route="file", method=RequestMethod::POST)
     *
     * @param Request $request
     *
     * @return array
     */
    public function create(Request $request): array
    {
        // $request->isAjax();

        return ['item0', 'item1'];
    }
}
