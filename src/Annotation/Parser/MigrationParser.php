<?php declare(strict_types=1);


namespace Swoft\Devtool\Annotation\Parser;


use InvalidArgumentException;
use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Devtool\Annotation\Mapping\Migration;
use Swoft\Devtool\Migration\MigrationRegister;
use Swoft\Stdlib\Helper\StringHelper;

/**
 * Class MigrationParser
 *
 * @since 2.0
 * @AnnotationParser(Migration::class)
 */
class MigrationParser extends Parser
{
    /**
     * @param int       $type
     * @param Migration $annotationObject
     *
     * @return array
     */
    public function parse(int $type, $annotationObject): array
    {
        $className = $this->className;

        if (!$time = $annotationObject->getTime()) {
            $time = StringHelper::substr($className, -14, 14);
        }

        $migrationName = StringHelper::replaceLast((string)$time, '', $className);
        $time          = (int)$time;

        if (empty($time)) {
            throw new InvalidArgumentException(get_class($annotationObject) . ' time params must exists');
        }

        MigrationRegister::registerMigration($migrationName, $time, $annotationObject->getPool());

        return [$migrationName, $className, Bean::PROTOTYPE, ''];
    }
}
