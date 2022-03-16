<?php

/**
 * This file is part of the Propel package - modified by Spryker Systems GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with the source code of the extended class.
 *
 * @license MIT License
 * @see https://github.com/propelorm/Propel2
 */

namespace Spryker\Zed\PropelOrm\Business\Builder;

use Propel\Generator\Builder\Om\ClassTools;
use Propel\Generator\Builder\Om\ObjectBuilder as PropelObjectBuilder;
use Propel\Generator\Model\Column;
use Propel\Generator\Model\IdMethod;
use Propel\Generator\Model\Table;
use Propel\Generator\Platform\MssqlPlatform;
use Spryker\Shared\Config\Application\Environment;
use Spryker\Shared\ErrorHandler\ErrorHandlerEnvironment;
use Spryker\Zed\Kernel\Business\FactoryResolverAwareTrait as BusinessFactoryResolverAwareTrait;

/**
 * @method \Spryker\Zed\PropelOrm\Business\PropelOrmBusinessFactory getFactory()
 */
class ObjectBuilderWithLogger extends PropelObjectBuilder
{
    use BusinessFactoryResolverAwareTrait;

    /**
     * @param \Propel\Generator\Model\Table $table
     */
    public function __construct(Table $table)
    {
        parent::__construct($table);

        Environment::initialize();

        $errorHandlerEnvironment = new ErrorHandlerEnvironment();
        $errorHandlerEnvironment->initialize();
    }

    /**
     * Changes default Propel behavior.
     *
     * Adds setter method for boolean columns.
     *
     * @see \Propel\Generator\Builder\Om\ObjectBuilder::addColumnMutators()
     *
     * @param string $script The script will be modified in this method.
     * @param \Propel\Generator\Model\Column $col The current column.
     *
     * @return void
     */
    protected function addBooleanMutator(&$script, Column $col)
    {
        $clo = $col->getLowercasedName();

        $this->addBooleanMutatorComment($script, $col);
        $this->addMutatorOpenOpen($script, $col);
        $this->addMutatorOpenBody($script, $col);

        $allowNullValues = ($col->getAttribute('required', 'true') === 'true') ? 'false' : 'true';

        $hasDefaultValue = $col->getDefaultValue() === null ? 'false' : 'true';

        $script .= "
        if (\$v !== null) {
            if (is_string(\$v)) {
                \$v = in_array(strtolower(\$v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                \$v = (bool) \$v;
            }
        }

        \$allowNullValues = $allowNullValues;

        if (\$v === null && !\$allowNullValues) {
            return \$this;
        }

        // When this is true we will not check for value equality as we need to be able to set a value for this field
        // to its initial value and have the column marked as modified. This is relevant for update cases when
        // we create an instance of an entity manually.
        // @see \Spryker\Zed\Kernel\Persistence\EntityManager\TransferToEntityMapper::mapEntity()
        \$hasDefaultValue = $hasDefaultValue;

        if ((\$this->isNew() && \$hasDefaultValue) || \$this->$clo !== \$v) {
            \$this->$clo = \$v;
            \$this->modifiedColumns[" . $this->getColumnConstant($col) . "] = true;
        }
";
        $this->addMutatorClose($script, $col);
    }

    /**
     * Specifies the methods that are added as part of the basic OM class.
     * This can be overridden by subclasses that wish to add more methods.
     *
     * @see ObjectBuilder::addClassBody()
     *
     * @param string $script
     *
     * @return void
     */
    protected function addClassBody(&$script)
    {
        $classes = $this->getFactory()
            ->createtPostSaveClassNamespacesCollector()
            ->extractClassesToDeclare();

        foreach ($classes as $class) {
            $this->declareClass($class);
        }

        parent::addClassBody($script);
    }

    /**
     * get the doInsert() method code
     *
     * @return string the doInsert() method code
     */
    protected function addDoInsert()
    {
        $table = $this->getTable();
        $script = "
    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface \$con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface \$con)
    {";
        if ($this->getPlatform() instanceof MssqlPlatform) {
            if ($table->hasAutoIncrementPrimaryKey()) {
                $script .= "
        \$this->modifiedColumns[" . $this->getColumnConstant($table->getAutoIncrementPrimaryKey()) . '] = true;';
            }
            $script .= "
        \$criteria = \$this->buildCriteria();";
            if ($this->getTable()->getIdMethod() != IdMethod::NO_ID_METHOD) {
                $script .= $this->addDoInsertBodyWithIdMethod();
            } else {
                $script .= $this->addDoInsertBodyStandard();
            }
        } else {
            $script .= $this->addDoInsertBodyRaw();
        }
        $script .= "
        \\Spryker\\Shared\\Log\\LoggerFactory::getInstance()->info('Entity save (new)', ['entity' => \$this->toArray('fieldName', false)]);

        \$this->setNew(false);

    }
";

        return $script;
    }

    /**
     * get the doUpdate() method code
     *
     * @return string the doUpdate() method code
     */
    protected function addDoUpdate()
    {
        return "
    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface \$con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface \$con)
    {
        \$selectCriteria = \$this->buildPkeyCriteria();
        \$valuesCriteria = \$this->buildCriteria();

        \\Spryker\\Shared\\Log\\LoggerFactory::getInstance()->info('Entity save (update)', ['entity' => \$this->toArray('fieldName', false)]);

        return \$selectCriteria->doUpdate(\$valuesCriteria, \$con);
    }
";
    }

    /**
     * Adds the function close for the delete function
     *
     * @see addDelete()
     *
     * @param string $script The script will be modified in this method.
     *
     * @return void
     */
    protected function addDeleteClose(&$script)
    {
        $script .= "
        \\Spryker\\Shared\\Log\\LoggerFactory::getInstance()->info('Entity delete', ['entity' => \$this->toArray('fieldName', false)]);
    }
";
    }

    /**
     * Adds the base object hook functions.
     *
     * @param string $script
     *
     * @return void
     */
    protected function addHookMethods(&$script)
    {
        $hooks = [];
        foreach (['pre', 'post'] as $hook) {
            foreach (['Insert', 'Update', 'Save', 'Delete'] as $action) {
                $hooks[$hook . $action] = strpos($script, 'function ' . $hook . $action . '(') === false;
            }
        }

        /** @var string|null $className */
        $className = ClassTools::classname($this->getBaseClass());
        $hooks['hasBaseClass'] = $this->getBehaviorContent('parentClass') !== null || $className !== null;

        $script .= $this->renderTemplate('baseObjectMethodHook', $hooks);
    }

    /**
     * Adds the function close for the save method
     *
     * @see addSave()
     *
     * @param string $script The script will be modified in this method.
     *
     * @return void
     */
    protected function addSaveClose(&$script)
    {
        $script .= "
    }
    ";
        $this->addPostSaveMethodProcess($script);
    }

    /**
     * Adds custom postSave hook to Propel instance
     *
     * @param string $script
     *
     * @return void
     */
    protected function addPostSaveMethodProcess(string &$script): void
    {
        $script .= "
     /**
     * Code to be run after persisting the object
     * @param \\Propel\\Runtime\\Connection\\ConnectionInterface \$con
     */
     public function postSave(?ConnectionInterface \$con = null)
     {";

        $extensionPlugins = $this->getFactory()->getPostSaveExtensionPlugins();
        foreach ($extensionPlugins as $plugin) {
            $script = $plugin->extend($script);
        }

        $script .= "
     }
     ";
    }
}
