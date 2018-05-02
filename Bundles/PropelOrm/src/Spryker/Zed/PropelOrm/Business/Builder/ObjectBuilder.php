<?php

/**
 * This file is part of the Propel package - modified by Spryker Systems GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with the source code of the extended class.
 *
 * @license MIT License
 */

namespace Spryker\Zed\PropelOrm\Business\Builder;

use Propel\Generator\Builder\Om\ObjectBuilder as PropelObjectBuilder;
use Propel\Generator\Model\Column;
use Propel\Generator\Model\IdMethod;
use Propel\Generator\Model\Table;
use Propel\Generator\Platform\PlatformInterface;
use Propel\Runtime\Exception\PropelException;
use Spryker\Shared\Config\Application\Environment;
use Spryker\Shared\Config\Config;
use Spryker\Shared\ErrorHandler\ErrorHandlerEnvironment;
use Spryker\Shared\PropelOrm\PropelOrmConstants;

class ObjectBuilder extends PropelObjectBuilder
{
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
     * Change default propel behaviour
     *
     * Adds setter method for boolean columns.
     *
     * @see \Propel\Generator\Builder\Om\ObjectBuilder::addColumnMutators()
     *
     * @param string &$script The script will be modified in this method.
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

        if (\$this->$clo !== \$v) {
            \$this->$clo = \$v;
            \$this->modifiedColumns[" . $this->getColumnConstant($col) . "] = true;
        }
";
        $this->addMutatorClose($script, $col);
    }

    /**
     * Boosts ActiveRecord::doInsert() by doing more calculations at build-time.
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return string
     */
    protected function addDoInsertBodyRaw()
    {
        $this->declareClasses(
            '\Propel\Runtime\Propel',
            '\PDO'
        );
        $table = $this->getTable();
        $platform = $this->getPlatform();
        $primaryKeyMethodInfo = '';
        if ($table->getIdMethodParameters()) {
            $params = $table->getIdMethodParameters();
            $imp = $params[0];
            $primaryKeyMethodInfo = $imp->getValue();
        } elseif ($table->getIdMethod() == IdMethod::NATIVE && ($platform->getNativeIdMethod() == PlatformInterface::SEQUENCE || $platform->getNativeIdMethod() == PlatformInterface::SERIAL)) {
            $primaryKeyMethodInfo = $platform->getSequenceName($table);
        }
        $query = 'INSERT INTO ' . $this->quoteIdentifier($table->getName()) . ' (%s) VALUES (%s)';
        $script = "
        \$modifiedColumns = array();
        \$index = 0;
";

        foreach ($table->getPrimaryKey() as $column) {
            if (!$column->isAutoIncrement()) {
                continue;
            }
            $constantName = $this->getColumnConstant($column);
            if ($platform->supportsInsertNullPk()) {
                $script .= "
        \$this->modifiedColumns[$constantName] = true;";
            }
            $columnProperty = $column->getLowercasedName();
            if (!$table->isAllowPkInsert()) {
                $script .= "
        if (null !== \$this->{$columnProperty}) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . $constantName . ')');
        }";
            } elseif (!$platform->supportsInsertNullPk()) {
                $script .= "
        // add primary key column only if it is not null since this database does not accept that
        if (null !== \$this->{$columnProperty}) {
            \$this->modifiedColumns[$constantName] = true;
        }";
            }
        }

        // if non auto-increment but using sequence, get the id first
        if (!$platform->isNativeIdMethodAutoIncrement() && $table->getIdMethod() == "native") {
            $column = $table->getFirstPrimaryKeyColumn();
            if (!$column) {
                throw new PropelException('Cannot find primary key column in table `' . $table->getName() . '`.');
            }
            $columnProperty = $column->getLowercasedName();
            $script .= "
        if (null === \$this->{$columnProperty}) {
            try {";
            $script .= $platform->getIdentifierPhp('$this->' . $columnProperty, '$con', $primaryKeyMethodInfo, '                ');
            $script .= "
            } catch (Exception \$e) {
                throw new PropelException('Unable to get sequence id.', 0, \$e);
            }
        }
";
        }

        $script .= "

         // check the columns in natural order for more readable SQL queries";
        foreach ($table->getColumns() as $column) {
            $constantName = $this->getColumnConstant($column);
            $identifier = var_export($this->quoteIdentifier($column->getName()), true);
            $script .= "
        if (\$this->isColumnModified($constantName)) {
            \$modifiedColumns[':p' . \$index++]  = $identifier;
        }";
        }

        $script .= "

        \$sql = sprintf(
            '$query',
            implode(', ', \$modifiedColumns),
            implode(', ', array_keys(\$modifiedColumns))
        );

        try {
            \$stmt = \$con->prepare(\$sql);
            foreach (\$modifiedColumns as \$identifier => \$columnName) {
                switch (\$columnName) {";
        foreach ($table->getColumns() as $column) {
            $columnNameCase = var_export($this->quoteIdentifier($column->getName()), true);
            $script .= "
                    case $columnNameCase:";
            $script .= $platform->getColumnBindingPHP($column, "\$identifier", '$this->' . $column->getLowercasedName(), '                        ');
            $script .= "
                        break;";
        }

        if (Config::get(PropelOrmConstants::PROPEL_SHOW_EXTENDED_EXCEPTION, false)) {
            $script .= "
                }
            }
            \$stmt->execute();
        } catch (Exception \$e) {
            Propel::log(\$e->getMessage(), Propel::LOG_ERR);
            \$message = \$e->getMessage() . PHP_EOL . PHP_EOL
                . 'Executed query: ' . PHP_EOL
                . \$stmt->getExecutedQueryString()
            ;
            throw new PropelException(\$message);
        }
";
        } else {
            $script .= "
                }
            }
            \$stmt->execute();
        } catch (Exception \$e) {
            Propel::log(\$e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', \$sql), 0, \$e);
        }
";
        }

        // if auto-increment, get the id after
        if ($platform->isNativeIdMethodAutoIncrement() && $table->getIdMethod() == "native") {
            $script .= "
        try {";
            $script .= $platform->getIdentifierPhp('$pk', '$con', $primaryKeyMethodInfo);
            $script .= "
        } catch (Exception \$e) {
            throw new PropelException('Unable to get autoincrement id.', 0, \$e);
        }";
            $column = $table->getFirstPrimaryKeyColumn();
            if ($column) {
                if ($table->isAllowPkInsert()) {
                    $script .= "
        if (\$pk !== null) {
            \$this->set" . $column->getPhpName() . "(\$pk);
        }";
                } else {
                    $script .= "
        \$this->set" . $column->getPhpName() . "(\$pk);";
                }
            }
            $script .= "
";
        }

        return $script;
    }

    /**
     * Adds the toArray method
     *
     * @param string &$script The script will be modified in this method.
     *
     * @return void
     **/
    protected function addToArray(&$script)
    {
        $fks = $this->getTable()->getForeignKeys();
        $referrers = $this->getTable()->getReferrers();
        $hasFks = count($fks) > 0 || count($referrers) > 0;
        $objectClassName = $this->getUnqualifiedClassName();
        $pkGetter = $this->getTable()->hasCompositePrimaryKey() ? 'serialize($this->getPrimaryKey())' : '$this->getPrimaryKey()';
        $defaultKeyType = $this->getDefaultKeyType();
        $script .= "
    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  \$keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::$defaultKeyType.
     * @param     boolean \$includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array \$alreadyDumpedObjects List of objects to skip to avoid recursion";
        if ($hasFks) {
            $script .= "
     * @param     boolean \$includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.";
        }
        $script .= "
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray(\$keyType = TableMap::$defaultKeyType, \$includeLazyLoadColumns = true, \$alreadyDumpedObjects = array()" . ($hasFks ? ", \$includeForeignObjects = false" : '') . ")
    {

        if (isset(\$alreadyDumpedObjects['$objectClassName'][\$this->hashCode()])) {
            return '*RECURSION*';
        }
        \$alreadyDumpedObjects['$objectClassName'][\$this->hashCode()] = true;
        \$keys = " . $this->getTableMapClassName() . "::getFieldNames(\$keyType);
        \$result = array(";
        foreach ($this->getTable()->getColumns() as $num => $col) {
            if ($col->isLazyLoad()) {
                $script .= "
            \$keys[$num] => (\$includeLazyLoadColumns) ? \$this->get" . $col->getPhpName() . "() : null,";
            } else {
                $script .= "
            \$keys[$num] => \$this->get" . $col->getPhpName() . "(),";
            }
        }
        $script .= "
        );";

        foreach ($this->getTable()->getColumns() as $num => $col) {
            if ($col->isTemporalType()) {
                $script .= "
        if (\$result[\$keys[$num]] instanceof \DateTime) {
            \$result[\$keys[$num]] = \$result[\$keys[$num]]->format('" . $this->getTemporalFormatter($col) . "');
        }
        ";
            }
        }
        $script .= "
        \$virtualColumns = \$this->virtualColumns;
        foreach (\$virtualColumns as \$key => \$virtualColumn) {
            \$result[\$key] = \$virtualColumn;
        }
        ";
        if ($hasFks) {
            $script .= "
        if (\$includeForeignObjects) {";
            foreach ($fks as $fk) {
                $script .= "
            if (null !== \$this->" . $this->getFKVarName($fk) . ") {
                {$this->addToArrayKeyLookUp($fk->getPhpName(), $fk->getForeignTable(), false)}
                \$result[\$key] = \$this->" . $this->getFKVarName($fk) . "->toArray(\$keyType, \$includeLazyLoadColumns,  \$alreadyDumpedObjects, true);
            }";
            }
            foreach ($referrers as $fk) {
                if ($fk->isLocalPrimaryKey()) {
                    $script .= "
            if (null !== \$this->" . $this->getPKRefFKVarName($fk) . ") {
                {$this->addToArrayKeyLookUp($fk->getRefPhpName(), $fk->getTable(), false)}
                \$result[\$key] = \$this->" . $this->getPKRefFKVarName($fk) . "->toArray(\$keyType, \$includeLazyLoadColumns, \$alreadyDumpedObjects, true);
            }";
                } else {
                    $script .= "
            if (null !== \$this->" . $this->getRefFKCollVarName($fk) . ") {
                {$this->addToArrayKeyLookUp($fk->getRefPhpName(), $fk->getTable(), true)}
                \$result[\$key] = \$this->" . $this->getRefFKCollVarName($fk) . "->toArray(null, false, \$keyType, \$includeLazyLoadColumns, \$alreadyDumpedObjects);
            }";
                }
            }
            $script .= "
        }";
        }
        $script .= "

        return \$result;
    }
";
    }
}
