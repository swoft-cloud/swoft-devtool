<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Devtool\Listener;

use Swoft\Bean\BeanFactory;
use Swoft\Config\Annotation\Mapping\Config;
use Swoft\Devtool\Model\Logic\MetaLogic;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Log\Helper\CLog;
use Swoft\SwoftEvent;
use Throwable;

/**
 * Class AppInitCompleteListener
 *
 * @since 2.0
 *
 * @Listener(event=SwoftEvent::APP_INIT_COMPLETE)
 */
class AppInitCompleteListener implements EventHandlerInterface
{
    /**
     * @Config("devtool.genPhpstormStubs")
     * @var bool
     */
    private $genPhpstormStubs = true;

    /**
     * @param EventInterface $event
     *
     * @throws Throwable
     */
    public function handle(EventInterface $event): void
    {
        // Generate phpstorm.meta.php
        if (APP_DEBUG && $this->genPhpstormStubs) {
            CLog::debug('auto generate phpstorm meta file');

            $phpstorm = BeanFactory::getBean(MetaLogic::class);
            $phpstorm->generate();
        }
    }
}
