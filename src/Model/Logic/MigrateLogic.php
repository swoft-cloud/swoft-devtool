<?php declare(strict_types=1);


namespace Swoft\Devtool\Model\Logic;

use Leuffen\TextTemplate\TemplateParsingException;
use ReflectionException;
use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Bean\BeanFactory;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Db\DB;
use Swoft\Db\Exception\DbException;
use Swoft\Db\Pool;
use Swoft\Db\Schema;
use Swoft\Db\Schema\Blueprint;
use Swoft\Db\Schema\Builder;
use Swoft\Devtool\FileGenerator;
use Swoft\Devtool\Helper\ConsoleHelper;
use Swoft\Devtool\Migration\Migration;
use Swoft\Devtool\Migration\Exception\MigrationException;
use Swoft\Devtool\Migration\Contract\MigrationInterface;
use Swoft\Devtool\Migration\MigrationManager;
use Swoft\Devtool\Migration\MigrationRegister;
use Swoft\Devtool\Model\Dao\MigrateDao;
use Swoft\Devtool\Model\Data\MigrateData;
use Swoft\Stdlib\Helper\StringHelper;
use Throwable;
use function method_exists;

/**
 * Class MigrateLogic
 *
 * @since 2.0
 *
 * @Bean()
 */
class MigrateLogic
{
    /**
     * @Inject()
     *
     * @var MigrateData
     */
    private $migrateData;

    /**
     * @param string $name
     * @param bool   $notConfirm
     *
     * @throws ContainerException
     * @throws MigrationException
     * @throws ReflectionException
     * @throws TemplateParsingException
     */
    public function create($name, bool $notConfirm = true): void
    {
        if (preg_match("#[^[A-Za-z_]|^\u{4E00}-\u{9FA5}]+#is", $name)) {
            throw new MigrationException(sprintf("name (%s) is invalid param", $name));
        }
        /* @var MigrationManager $migrate */
        $migrate   = BeanFactory::getBean('migrationManager');
        $time      = date('YmdHis');
        $name      = $this->convertName($name);
        $namespace = $this->getNamespace($migrate->getMigrationPath());

        // check migrate exist
        $mappingClass = sprintf('%s\%s', $namespace, $name);
        if (MigrationRegister::checkExists($mappingClass)) {
            throw new MigrationException(sprintf("%s migration exists, please check migration !", $name));
        }

        $tplDir = $migrate->getTemplateDir();
        $config = [
            'tplFilename' => $migrate->getTemplateFile(),
            'tplDir'      => Swoft::getAlias($tplDir),
            'className'   => $name,
        ];

        $aliasPath = $migrate->getMigrationPath();
        $path      = Swoft::getAlias($aliasPath);
        $file      = sprintf('%s/%s.php', $path, $name);

        // check generate path exists
        if (!is_dir($path)) {
            if (!$notConfirm && !ConsoleHelper::confirm("mkdir path $path, ensure continue?", true)) {
                output()->writeln(' Quit, Bye!');
                return;
            }
            // generate path
            mkdir($path, 0755, true);
        }

        if (!$notConfirm && !ConsoleHelper::confirm("generate migrate $file, ensure continue?", true)) {
            output()->writeln(' Quit, Bye!');
            return;
        }

        $data = [
            'namespace' => $namespace,
            'name'      => $name,
            'time'      => $time,
        ];

        $gen = new FileGenerator($config);
        $gen->renderas($file, $data);


        $data = [
            'className' => $name,
            'file'      => $file
        ];
        output()->aList($data, 'Migration create success');
    }

    /**
     * @param array  $names
     * @param array  $dbs
     * @param string $prefix
     * @param int    $start
     * @param int    $end
     * @param bool   $isConfirm
     *
     * @throws ContainerException
     * @throws DbException
     * @throws MigrationException
     * @throws ReflectionException
     * @throws Throwable
     */
    public function up(
        array $names,
        array $dbs,
        string $prefix,
        int $start = 0,
        int $end = 0,
        $isConfirm = false
    ): void {

        $migrateNames = $this->matchNames($names);
        if (empty($migrateNames)) {
            throw new MigrationException('Not match migrate, please check name');
        }

        $specialHandlerStatus = $this->specialHandler(function ($db) use ($migrateNames, $prefix, $isConfirm) {
            $this->executeUp($migrateNames, $isConfirm, $prefix, $db);
        }, $dbs, $start, $end);

        if ($specialHandlerStatus === true) {
            return;
        }
        $this->executeUp($migrateNames, $isConfirm, $prefix);

        output()->success('migrate up execute ok');
    }

    /**
     * @param array  $names
     * @param array  $dbs
     * @param string $prefix
     * @param int    $start
     * @param int    $end
     * @param bool   $isConfirm
     * @param string $defaultPool
     *
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     * @throws Throwable
     */
    public function down(
        array $names,
        array $dbs,
        string $prefix,
        int $start = 0,
        int $end = 0,
        $isConfirm = false,
        string $defaultPool = Pool::DEFAULT_POOL
    ) {
        // Strict match rollback migrations
        $migrateNames = $this->matchNames($names, true);
        if ($names && empty($migrateNames)) {
            throw new MigrationException('Not match migrate, please check name');
        }

        $specialHandlerStatus = $this->specialHandler(function ($db) use (
            $migrateNames,
            $prefix,
            $isConfirm,
            $defaultPool
        ) {
            $this->executeDown($migrateNames, $isConfirm, $prefix, $db, $defaultPool);
        }, $dbs, $start, $end);

        if ($specialHandlerStatus === true) {
            return;
        }

        $this->executeDown($migrateNames, $isConfirm, $prefix, '', $defaultPool);

        output()->success('migrate down execute ok');
    }

    /**
     * @param array  $dbs
     * @param string $prefix
     * @param int    $start
     * @param int    $end
     * @param int    $limit
     * @param string $defaultPool
     *
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     */
    public function history(
        array $dbs,
        string $prefix,
        int $start = 0,
        int $end = 0,
        int $limit = 10,
        string $defaultPool = Pool::DEFAULT_POOL
    ) {
        $specialHandlerStatus = $this->specialHandler(function ($db) use (
            $prefix,
            $limit,
            $defaultPool
        ) {
            $this->showHistory($limit, $prefix, $db, $defaultPool);
        }, $dbs, $start, $end);

        if ($specialHandlerStatus === true) {
            return;
        }

        $this->showHistory($limit, $prefix, '', $defaultPool);

        output()->success('migrate show history ok');
    }

    /**
     * Show migration history
     *
     * @param int    $limit
     * @param string $dbPrefix
     * @param string $db
     * @param string $defaultPool
     *
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     */
    private function showHistory(
        int $limit,
        string $dbPrefix = '',
        string $db = '',
        string $defaultPool = Pool::DEFAULT_POOL
    ): void {
        $schema   = $this->getSchema($defaultPool, $db, $dbPrefix);
        $database = $schema->getDatabaseName();

        if ($schema->checkDatabaseExists() === false) {
            output()->warning("database=$database not exists");
            return;
        }
        $this->createMigrationIfNotExists($schema);

        $list = $this->getSafeMigrationData(function () use ($limit, $defaultPool, $database) {
            return $this->migrateData->listMigrateHistory($limit, $defaultPool, $database);
        });

        $showItems = [];
        foreach ($list as $k => $item) {
            $showItems[$k]['MigrationName'] = $item['name'] . $item['time'];
            $showItems[$k]['Time']          = $item['time'];
            $showItems[$k]['RollBack']      = $item['is_rollback'] == MigrateDao::IS_ROLLBACK ? 'yes' : 'no';
        }

        output()->panel($showItems, "Database=$database migrations history");
    }

    /**
     * @param callable $callback
     * @param array    $dbs
     * @param int      $start
     * @param int      $end
     *
     * @return bool
     */
    private function specialHandler(callable $callback, array $dbs, int $start = 0, int $end = 0): bool
    {
        if ($start) {
            $end = empty($end) ? $start : $end;
            // convert start end
            for ($i = $start; $i <= $end; $i++) {
                $dbs[] = $i;
            }
        }

        if (empty($dbs)) {
            return false;
        }

        foreach ($dbs as $db) {
            $callback((string)$db);
        }
        output()->success('execute ok');

        return true;
    }

    /**
     * @param array  $mathMigrateNames
     * @param bool   $isConfirm
     * @param string $dbPrefix
     * @param string $db
     *
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     * @throws Throwable
     */
    private function executeUp(array $mathMigrateNames, bool $isConfirm, string $dbPrefix, string $db = ''): void
    {
        $poolGroup = $this->groupByPoolMigrates($mathMigrateNames);

        foreach ($poolGroup as $pool => $migrates) {
            $migrateNames       = [];
            $migrateNameTimeMap = [];

            foreach ($migrates as $migrate) {
                $time                             = $migrate['time'];
                $migrateNames[]                   = $migrateName = $migrate['name'];
                $migrateNameTimeMap[$migrateName] = $time;
            }

            $schema = $this->getSchema($pool, $db, $dbPrefix);

            $database = $schema->getDatabaseName();

            if ($schema->checkDatabaseExists() === false) {
                output()->warning("database=$database not exists");
                return;
            }

            $this->createMigrationIfNotExists($schema);

            $effectiveMigrates = $this->migrateData->getEffectiveMigrates($migrateNames, $pool,
                $schema->getDatabaseName());
            // Check migrate exists
            if (empty($effectiveMigrates)) {
                continue;
            }

            $this->displayMigrates($effectiveMigrates, $migrateNameTimeMap, 'new migrations to be applied');

            if (!$isConfirm && !ConsoleHelper::confirm("Apply the above migrations?", false)) {
                output()->writeln(' Quit, Bye!');
                return;
            }

            foreach ($effectiveMigrates as $effectiveMigrateName) {
                if ($this->runMigration($schema, $effectiveMigrateName, 'up')) {
                    $time = $migrateNameTimeMap[$effectiveMigrateName];

                    $this->migrateData->saveMigrateLog($effectiveMigrateName, $time, $pool, $schema->getDatabaseName());

                    output()->success($effectiveMigrateName . $time . " up migration executed success");
                }
            }

        }
    }

    /**
     * @param array  $mathMigrateNames
     * @param bool   $isConfirm
     * @param string $dbPrefix
     * @param string $db
     * @param string $defaultPool
     *
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     * @throws Throwable
     */
    private function executeDown(
        array $mathMigrateNames,
        bool $isConfirm,
        string $dbPrefix,
        string $db = '',
        string $defaultPool = Pool::DEFAULT_POOL
    ): void {

        // Default execute last up migration
        if (empty($mathMigrateNames)) {
            $this->rollback($isConfirm, $dbPrefix, $db, $defaultPool);
            return;
        }

        // Batch Rollback
        $poolGroup = $this->groupByPoolMigrates($mathMigrateNames);
        foreach ($poolGroup as $pool => $migrates) {
            $migrateNames       = [];
            $migrateNameTimeMap = [];

            foreach ($migrates as $migrate) {
                $time                             = $migrate['time'];
                $migrateNames[]                   = $migrateName = $migrate['name'];
                $migrateNameTimeMap[$migrateName] = $time;
            }

            $schema = $this->getSchema($pool, $db, $dbPrefix);

            $database = $schema->getDatabaseName();
            if ($schema->checkDatabaseExists() === false) {
                output()->warning("database=$database not exists");
                return;
            }

            $filterMigrateNames = $this->getSafeMigrationData(function () use ($migrateNames, $pool, $database) {
                return $this->migrateData->getRollbackMigrates($migrateNames, $pool, $database);
            });

            if (empty($filterMigrateNames)) {
                output()->warning("database=$database nothing migrations");
                continue;
            }
            $this->displayMigrates($filterMigrateNames, $migrateNameTimeMap, 'Down migrations to be applied');

            if (!$isConfirm && !ConsoleHelper::confirm("Apply down the above migrations?", false)) {
                output()->writeln(' Quit, Bye!');
                return;
            }

            foreach ($filterMigrateNames as $rollbackName) {
                if ($this->runMigration($schema, $rollbackName, 'down')) {
                    $this->migrateData->rollback($rollbackName, $pool, $database);

                    output()->success($rollbackName . $migrateNameTimeMap[$rollbackName]
                        . " down migration executed success");
                }
            }
        }
        return;
    }

    /**
     * Get safe migrate table data
     *
     * @param callable $callback
     *
     * @return mixed
     */
    private function getSafeMigrationData(callable $callback)
    {
        try {
            // Check migrate exists
            $data = $callback();
        } catch (Throwable $e) {
            $message = $e->getMessage();
            if (stripos($message, MigrateDao::tableName()) === false) {
                output()->warning($e->getMessage());
            }
            return null;
        }
        return $data;
    }

    /**
     * @param string $pool
     * @param string $db
     * @param string $dbPrefix
     *
     * @return Builder
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     */
    private function getSchema(string $pool, string $db, string $dbPrefix): Builder
    {
        $schema     = Schema::getSchemaBuilder($pool);
        $connection = DB::connection($pool);

        $selectDb = $connection->getSelectDb() ?: $connection->getDb();

        if (empty($dbPrefix)) {
            $db = $selectDb . $db;
        } else {
            $db = $dbPrefix . $db;
        }

        // Reselect database
        if ($db && $db !== $selectDb) {
            $schema->setDatabase($db);
        }
        $connection->release();

        return $schema;
    }

    /**
     * Rollback last migration
     *
     * @param bool   $isConfirm
     * @param string $dbPrefix
     * @param string $db
     * @param string $defaultPool
     *
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     * @throws Throwable
     */
    private function rollback(
        bool $isConfirm,
        string $dbPrefix,
        string $db = '',
        string $defaultPool = Pool::DEFAULT_POOL
    ): void {

        $schema   = $this->getSchema($defaultPool, $db, $dbPrefix);
        $database = $schema->getDatabaseName();

        if ($schema->checkDatabaseExists() === false) {
            output()->warning("database=$database not exists");
            return;
        }

        $this->createMigrationIfNotExists($schema);

        $migrateName = $this->getSafeMigrationData(function () use ($defaultPool, $database) {
            return $this->migrateData->lastMigrationName($defaultPool, $database);
        });

        if (empty($migrateName)) {
            output()->warning("Database $database nothing to rollback.");
            return;
        }

        $config = MigrationRegister::getMigrationDetail($migrateName);

        $this->displayMigrates($migrateName, [$migrateName => $config['time']], 'rollback migration to be applied');

        if (!$isConfirm && !ConsoleHelper::confirm("Apply rollback the above migration?", false)) {
            output()->writeln(' Quit, Bye!');
            return;
        }
        if ($this->runMigration($schema, $migrateName, 'down')) {
            $this->migrateData->rollback($migrateName, $defaultPool, $database);

            output()->success($migrateName . $config['time'] . " down migration executed success");
        }
    }

    /**
     * @param array|string $migrates
     * @param array        $migrateNameTimeMap
     * @param string       $message
     */
    private function displayMigrates(
        $migrates,
        array $migrateNameTimeMap,
        string $message
    ): void {
        $shows = [];
        foreach ((array)$migrates as &$migrateName) {
            $migrateName .= (string)$migrateNameTimeMap[$migrateName];
            $shows[]     = "<blue>$migrateName</blue>";
        }
        unset($migrateName);
        output()->aList($shows, $message);
    }

    /**
     * @param array $mathMigrateNames
     *
     * @return array
     */
    private function groupByPoolMigrates(array $mathMigrateNames): array
    {
        $poolMigrates = [];
        // Group by pool
        foreach ($mathMigrateNames as $name) {
            $config = MigrationRegister::getMigrationDetail($name);
            $time   = $config['time'];
            $pool   = $config['pool'];

            $poolMigrates[$pool][] = compact('time', 'name');
        }

        return $poolMigrates;
    }

    /**
     * Run a migration inside a transaction if the database supports it.
     *
     * @param Builder $schema
     * @param string  $migrateName
     * @param string  $method
     *
     * @return bool
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     * @throws Throwable
     */
    private function runMigration(Builder $schema, string $migrateName, string $method): bool
    {
        /* @var MigrationInterface|Migration $migration */
        $migration = BeanFactory::getBean($migrateName);
        if (!$migration instanceof MigrationInterface) {
            output()->error("$migrateName migration must implement MigrationInterface");
            return false;
        }
        if (method_exists($migration, 'setSchema')) {
            $copySchema = clone $schema;
            $migration->setSchema($copySchema);
        }

        $callback = function () use ($schema, $migration, $method) {
            // Call up or down method
            $migration->{$method}();
            if (method_exists($migration, 'getWaitExecuteSql')) {
                foreach ($migration->getWaitExecuteSql() as $statement) {
                    $schema->getConnection()->statement($statement);
                }
            }
        };

        $schema->grammar->supportsSchemaTransactions()
            ?
            $schema->getConnection()->transaction($callback)
            :
            $callback();
        return true;
    }

    /**
     * Match name convert migrate name
     *
     * @param array $names
     * @param bool  $strict
     *
     * @return array
     */
    private function matchNames(array $names, $strict = false): array
    {
        $migrations = MigrationRegister::getMigrations();

        $matchNames = [];
        foreach ($names as $name) {
            $name = $this->convertName($name);
            // match names
            foreach ($migrations as $migrationName => $migration) {
                if (stripos($migrationName, $name) !== false) {
                    $matchNames[] = $migrationName;
                }
            }
        }

        if (empty($names) && $strict === false) {
            $matchNames = array_keys($migrations);
        }

        return $matchNames;
    }

    /**
     * @param Builder $schema
     *
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     */
    private function createMigrationIfNotExists(Builder $schema): void
    {
        $schema->createIfNotExists(MigrateDao::tableName(), function (Blueprint $blueprint) {
            $blueprint->increments('id');
            $blueprint->string('name', 60);
            $blueprint->bigInteger('time');
            $blueprint->tinyInteger('is_rollback');
        });
    }


    /**
     * Get file namespace
     *
     * @param string $path
     *
     * @return string
     */
    private function getNameSpace(string $path): string
    {
        $path = str_replace(["@", "/"], ['', '\\'], $path);
        $path = ucfirst($path);

        return $path;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    private function convertName(string $name): string
    {
        $result = ucfirst(StringHelper::camel($name));

        return $result;
    }
}
