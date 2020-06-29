<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Devtool\Migration;

use Swoft\Db\Schema\Builder;
use Swoft\Devtool\Migration\Contract\MigrationInterface;

/**
 * Class Migration
 *
 * @since 2.0
 */
abstract class Migration implements MigrationInterface
{
    /**
     * @var Builder
     */
    protected $schema;

    /**
     * @var array
     */
    private $waitExecuteSql;

    /**
     * @return array
     */
    public function getWaitExecuteSql(): array
    {
        return (array)$this->waitExecuteSql;
    }

    /**
     * proxy execute sql
     *
     * @param string $sql
     */
    protected function execute(string $sql): void
    {
        $this->waitExecuteSql[] = $sql;
    }

    /**
     * @param Builder $schema
     */
    public function setSchema(Builder $schema): void
    {
        $this->schema = $schema;
    }
}
