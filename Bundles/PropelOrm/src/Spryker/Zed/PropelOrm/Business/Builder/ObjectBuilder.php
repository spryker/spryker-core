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
use Propel\Generator\Model\Table;
use Spryker\Shared\Config\Application\Environment;
use Spryker\Shared\ErrorHandler\ErrorHandlerEnvironment;
use Spryker\Zed\Kernel\Business\FactoryResolverAwareTrait as BusinessFactoryResolverAwareTrait;

/**
 * @method \Spryker\Zed\PropelOrm\Business\PropelOrmBusinessFactory getFactory()
 */
class ObjectBuilder extends PropelObjectBuilder
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
    protected function addBooleanMutator(string &$script, Column $col): void
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
     * Adds setter method for "normal" columns.
     *
     * @see parent::addColumnMutators()
     *
     * @param string $script The script will be modified in this method.
     * @param \Propel\Generator\Model\Column $col The current column.
     *
     * @return void
     */
    protected function addDefaultMutator(string &$script, Column $col): void
    {
        $clo = $col->getLowercasedName();

        $this->addMutatorOpen($script, $col);

        // Perform type-casting to ensure that we can use type-sensitive
        // checking in mutators.
        if ($col->isPhpPrimitiveType()) {
            $script .= "
        if (\$v !== null) {
            \$v = (" . $col->getPhpType() . ") \$v;
        }
";
        }

        $hasDefaultValue = $col->getDefaultValue() === null ? 'false' : 'true';

        $script .= "
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
    protected function addClassBody(string &$script): void
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
     * Adds the base object hook functions.
     *
     * @param string $script
     *
     * @return void
     */
    protected function addHookMethods(string &$script): void
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
    protected function addSaveClose(string &$script): void
    {
        $script .= "
    }
    ";
        $this->addPostSaveMethodProcess($script);
        $this->addPostUpdateMethodProcess($script);
        $this->addPostDeleteMethodProcess($script);
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
     * @param \\Propel\\Runtime\\Connection\\ConnectionInterface|null \$con
     *
     * @return void
     */
    public function postSave(?ConnectionInterface \$con = null): void
    {";

        $extensionPlugins = $this->getFactory()->getPostSaveExtensionPlugins();
        foreach ($extensionPlugins as $plugin) {
            $script = $plugin->extend($script);
        }

        $script .= "
    }
    ";
    }

    /**
     * Adds custom postUpdate hook to Propel instance
     *
     * @param string $script
     *
     * @return void
     */
    protected function addPostUpdateMethodProcess(string &$script): void
    {
        $script .= "
    /**
     * Code to be run after updating the object in database
     * @param \\Propel\\Runtime\\Connection\\ConnectionInterface|null \$con
     *
     * @return void
     */
    public function postUpdate(?ConnectionInterface \$con = null): void
    {";

        $extensionPlugins = $this->getFactory()->getPostUpdateExtensionPlugins();
        foreach ($extensionPlugins as $plugin) {
            $script = $plugin->extend($script);
        }

        $script .= "
    }
    ";
    }

    /**
     * Adds custom postDelete hook to Propel instance
     *
     * @param string $script
     *
     * @return void
     */
    protected function addPostDeleteMethodProcess(string &$script): void
    {
        $script .= "
    /**
     * Code to be run after deleting the object in database
     * @param \\Propel\\Runtime\\Connection\\ConnectionInterface|null \$con
     *
     * @return void
     */
    public function postDelete(?ConnectionInterface \$con = null): void
    {";

        $extensionPlugins = $this->getFactory()->getPostDeleteExtensionPlugins();
        foreach ($extensionPlugins as $plugin) {
            $script = $plugin->extend($script);
        }

        $script .= "
    }
    ";
    }
}
