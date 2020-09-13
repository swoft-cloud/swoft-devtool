<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Devtool;

use Swoft;
use Swoft\Helper\ComposerJSON;
use Swoft\SwoftComponent;
use function dirname;
use function env;

/**
 * Class AutoLoader
 *
 * @since 2.0
 */
class AutoLoader extends SwoftComponent
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        parent::__construct();

        Swoft::setAlias('@devtool', dirname(__DIR__, 2));
    }

    /**
     * @return bool
     */
    public function isEnable(): bool
    {
        return (int)env('ENABLE_DEVTOOL', 1) > 0;
    }

    /**
     * Get namespace and dir
     *
     * @return array
     * [
     *     namespace => dir path
     * ]
     */
    public function getPrefixDirs(): array
    {
        return [
            __NAMESPACE__ => __DIR__,
        ];
    }

    /**
     * Metadata information for the component.
     *
     * @return array
     * @see ComponentInterface::getMetadata()
     */
    public function metadata(): array
    {
        $jsonFile = dirname(__DIR__) . '/composer.json';

        return ComposerJSON::open($jsonFile)->getMetadata();
    }
}
