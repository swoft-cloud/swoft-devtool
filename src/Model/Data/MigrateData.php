<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Devtool\Model\Data;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Db\Exception\DbException;
use Swoft\Devtool\Model\Dao\MigrateDao;

/**
 * Class MigrateData
 *
 * @since 2.0
 *
 * @Bean()
 */
class MigrateData
{
    /**
     * @Inject()
     *
     * @var MigrateDao
     */
    private $migrateDao;

    /**
     * @param int    $limit
     * @param string $pool
     * @param string $db
     *
     * @return array
     * @throws DbException
     */
    public function listMigrateHistory(int $limit, string $pool, string $db): array
    {
        return $this->migrateDao->listMigrate($limit, $pool, $db);
    }

    /**
     * @param array  $migrateNames
     * @param string $pool
     * @param string $db
     *
     * @return array
     * @throws DbException
     */
    public function getEffectiveMigrates(array $migrateNames, string $pool, string $db): array
    {
        $result = $this->migrateDao->getMigrateNames($migrateNames, $pool, $db);

        $rollBackNames = [];
        foreach ($result as $name => $status) {
            if ((int)$status === MigrateDao::NOT_ROLLBACK) {
                $rollBackNames[] = $name;
            }
        }

        return array_diff($migrateNames, $rollBackNames);
    }

    /**
     * @param array  $migrateNames
     * @param string $pool
     * @param string $db
     *
     * @return array
     * @throws DbException
     */
    public function getRollbackMigrates(array $migrateNames, string $pool, string $db): array
    {
        $result = $this->migrateDao->getMigrateNames($migrateNames, $pool, $db);

        $rollBackNames = [];
        foreach ($result as $name => $status) {
            if ((int)$status === MigrateDao::IS_ROLLBACK) {
                $rollBackNames[] = $name;
            }
        }

        return array_diff($migrateNames, $rollBackNames);
    }

    /**
     * @param string $pool
     * @param string $db
     * @param int    $step
     *
     * @return array
     * @throws DbException
     */
    public function lastMigrationNames(string $pool, string $db, int $step = 1): array
    {
        return $this->migrateDao->lastMigrationNames($pool, $db, $step);
    }

    /**
     * save migrate log
     *
     * @param string $migrateName
     * @param int    $time
     * @param string $pool
     * @param string $db
     *
     * @return bool
     * @throws DbException
     */
    public function saveMigrateLog(string $migrateName, int $time, string $pool, string $db): bool
    {
        return $this->migrateDao->save($migrateName, $time, $pool, $db);
    }

    /**
     * @param string|array $migrates
     * @param string       $pool
     * @param string       $db
     *
     * @return bool
     * @throws DbException
     */
    public function rollback($migrates, string $pool, string $db): bool
    {
        return $this->migrateDao->rollback($migrates, $pool, $db);
    }
}
