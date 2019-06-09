<?php declare(strict_types=1);


namespace Swoft\Devtool\Command;

use Leuffen\TextTemplate\TemplateParsingException;
use ReflectionException;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandArgument;
use Swoft\Console\Annotation\Mapping\CommandMapping;
use Swoft\Db\Exception\DbException;
use Swoft\Db\Pool;
use Swoft\Devtool\Model\Logic\EntityLogic;
use function alias;
use function input;

/**
 * Class entityCommand generate entity
 *
 * @Command(coroutine=false)
 *
 * @since 2.0
 */
class EntityCommand
{

    /**
     * @Inject()
     *
     * @var EntityLogic
     */
    private $logic;

    /**
     * Generate database entity
     *
     * @CommandMapping(alias="c,gen")
     * @CommandArgument(name="table", desc="table names", type="string")
     * @throws TemplateParsingException
     * @throws ReflectionException
     * @throws ContainerException
     * @throws DbException
     */
    public function create(): void
    {
        $table       = input()->get('table', input()->getOpt('table'));
        $pool        = input()->getOpt('pool', Pool::DEFAULT_POOL);
        $path        = input()->getOpt('path', '@app/Model/Entity');
        $isConfirm   = input()->getOpt('y', false);
        $fieldPrefix = input()->getOpt('field_prefix', input()->getOpt('fp'));
        $tablePrefix = input()->getOpt('table_prefix', input()->getOpt('tp'));
        $exclude     = input()->getOpt('exc', input()->getOpt('exclude'));
        $tplDir      = input()->getOpt('tr', alias('@devtool/devtool/resource/template'));

        $this->logic->create([
            (string)$table,
            (string)$tablePrefix,
            (string)$fieldPrefix,
            (string)$exclude,
            (string)$pool,
            (string)$path,
            (bool)$isConfirm,
            (string)$tplDir
        ]);
    }
}
