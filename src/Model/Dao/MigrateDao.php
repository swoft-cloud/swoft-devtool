<?php declare(strict_types=1);


namespace Swoft\Devtool\Model\Dao;

use ReflectionException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Db\DB;
use Swoft\Db\Exception\DbException;
use Swoft\Db\Query\Builder;

/**
 * Class MigrateDao
 *
 * @since 2.0
 *
 * @Bean()
 */
class MigrateDao
{

    /**
     * Migration rollback status control
     *
     * @var int
     */
    public const IS_ROLLBACK  = 1;
    public const NOT_ROLLBACK = 2;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'migration';
    }

    /**
     * Get migrate history
     *
     * @param int    $limit
     * @param string $pool
     * @param string $db
     *
     * @return array
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     */
    public function listMigrate(int $limit, string $pool, string $db)
    {
        return $this->table($pool, $db)
            ->latest('id')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Save migrate
     *
     * @param string $name
     * @param int    $time
     * @param string $pool
     * @param string $db
     *
     * @return bool
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     */
    public function save(string $name, int $time, string $pool, string $db): bool
    {
        $params                = compact('name', 'time');
        $params['is_rollback'] = static::NOT_ROLLBACK;

        return $this->table($pool, $db)->insert($params);
    }


    /**
     * Get executed migrates
     *
     * @param string $pool
     * @param array  $migrateNames
     * @param string $db
     *
     * @return array
     * @throws ReflectionException
     * @throws ContainerException
     * @throws DbException
     */
    public function getMigrateNames(array $migrateNames, string $pool, string $db): array
    {
        $migrateNames = $this->table($pool, $db)
            ->whereIn('name', $migrateNames)
            ->get(['name', 'is_rollback'])
            ->pluck('is_rollback', 'name')
            ->toArray();

        return $migrateNames;
    }

    /**
     * Get effective migrate last log
     *
     * @param string $pool
     * @param string $db
     *
     * @return mixed
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     */
    public function lastMigrationName(string $pool, string $db)
    {
        $last = $this->table($pool, $db)
            ->where('is_rollback', static::NOT_ROLLBACK)
            ->latest('id')
            ->value('name');

        return (string)$last;
    }

    /**
     * Rollback migrates
     *
     * @param        $names
     * @param string $pool
     * @param string $db
     *
     * @return bool
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     */
    public function rollback($names, string $pool, string $db): bool
    {
        return (bool)$this->table($pool, $db)
            ->where('name', '=', $names)
            ->update(['is_rollback' => static::IS_ROLLBACK]);
    }

    /**
     * @param string $pool
     * @param string $db
     *
     * @return Builder
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     */
    public function table(string $pool, string $db): Builder
    {
        return DB::query($pool)->from(static::tableName())->db($db);
    }
}
