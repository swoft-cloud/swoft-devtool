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

use Swoft\Config\Annotation\Mapping\Config;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Log\Helper\CLog;

/**
 * Class EventFireListener
 * @Listener("*")
 */
class EventFireListener implements EventHandlerInterface
{
    /**
     * @Config("devtool.logEventToConsole")
     * @var bool
     */
    public $logEventToConsole = false;

    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        if (!$this->logEventToConsole) {
            return;
        }

        CLog::info('Trigger event %s', $event->getName());
    }
}
