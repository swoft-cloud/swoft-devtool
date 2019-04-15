<?php declare(strict_types=1);

namespace Swoft\Devtool;

/**
 *
 */
class AutoLoader extends AnotherClass
{

    public function __construct()
    {
        \Swoft::setAlias('@devtool', \dirname(__DIR__, 2));
    }

    /**
     * @return array
     */
    public function coreBeans(): array
    {
        return [];
    }
}
