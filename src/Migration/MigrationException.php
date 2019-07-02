<?php declare(strict_types=1);


namespace Swoft\Devtool\Migration;

use Exception;
use function sprintf;

/**
 * Class MigrationException
 *
 * @since 2.0
 */
class MigrationException extends Exception
{

    /**
     * make migration exception
     *
     * @param string $message
     * @param array  $args
     *
     * @return MigrationException
     */
    public static function make(string $message, ...$args): self
    {
        $message = sprintf($message, ...$args);
        return new static($message);
    }
}
