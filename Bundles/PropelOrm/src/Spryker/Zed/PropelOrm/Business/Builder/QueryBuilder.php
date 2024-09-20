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

use Propel\Common\Exception\SetColumnConverterException;
use Propel\Common\Util\SetColumnConverter;
use Propel\Generator\Builder\Om\QueryBuilder as PropelQueryBuilder;
use Propel\Generator\Model\Column;
use Propel\Generator\Model\PropelTypes;
use Spryker\Zed\Kernel\BundleConfigResolverAwareTrait;
use Spryker\Zed\Kernel\Business\FactoryResolverAwareTrait as BusinessFactoryResolverAwareTrait;
use Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException;
use Spryker\Zed\PropelOrm\Business\Model\Formatter\TypeAwareSimpleArrayFormatter;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\PropelOrm\PropelOrmConfig getConfig()
 * @method \Spryker\Zed\PropelOrm\Business\PropelOrmBusinessFactory getFactory()
 */
class QueryBuilder extends PropelQueryBuilder
{
    use BundleConfigResolverAwareTrait;
    use BusinessFactoryResolverAwareTrait;

    /**
     * @var string
     */
    public const ATTRIBUTE_CASE_INSENSITIVE = 'caseInsensitive';

    /**
     * @param \Propel\Generator\Model\Column $col
     *
     * @return string
     */
    protected function addFilterByColIn(Column $col): string
    {
        $script = '';

        if ($col->isNumericType() || $col->isTemporalType() || $col->getType() == PropelTypes::ENUM || $col->isTextType()) {
            $colPhpName = $col->getPhpName();
            $variableName = $col->getCamelCaseName();

            $script .= <<<SCRIPT

    /**
     * Applies Criteria::IN filtering criteria for the column.
     *
     * @param array \$${variableName}s Filter value.
     *
     * @return \$this The current query, for fluid interface
     */
    public function filterBy${colPhpName}_In(array \$${variableName}s)
    {
        return \$this->filterBy$colPhpName(\$${variableName}s, Criteria::IN);
    }

SCRIPT;
        }

        return $script;
    }

    /**
     * @param \Propel\Generator\Model\Column $col
     *
     * @return string
     */
    protected function addFilterByColBetween(Column $col): string
    {
        $script = '';

        if ($col->isNumericType() || $col->isTemporalType()) {
            $colPhpName = $col->getPhpName();
            $variableName = $col->getCamelCaseName();

            $script .= <<<SCRIPT

    /**
     * Applies SprykerCriteria::BETWEEN filtering criteria for the column.
     *
     * @param array \$$variableName Filter value.
     * [
     *    'min' => 3, 'max' => 5
     * ]
     *
     * 'min' and 'max' are optional, when neither is specified, throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException.
     *
     * @return \$this The current query, for fluid interface
     */
    public function filterBy${colPhpName}_Between(array \$$variableName)
    {
        return \$this->filterBy$colPhpName(\$$variableName, SprykerCriteria::BETWEEN);
    }

SCRIPT;
        }

        return $script;
    }

    /**
     * @param \Propel\Generator\Model\Column $col
     *
     * @return string
     */
    protected function addFilterByColLike(Column $col): string
    {
        $script = '';

        if ($col->isTextType()) {
            $colPhpName = $col->getPhpName();
            $variableName = $col->getCamelCaseName();

            $script .= <<<SCRIPT

    /**
     * Applies SprykerCriteria::LIKE filtering criteria for the column.
     *
     * @param string \$$variableName Filter value.
     *
     * @return \$this The current query, for fluid interface
     */
    public function filterBy${colPhpName}_Like(\$$variableName)
    {
        return \$this->filterBy$colPhpName(\$$variableName, Criteria::LIKE);
    }

SCRIPT;
        }

        return $script;
    }

    /**
     * @return array<string>
     */
    protected function getAllowedArrayFilters(): array
    {
        return [
            'Criteria::IN',
            'Criteria::NOT_IN',
        ];
    }

    /**
     * Adds the filterByCol method for this object.
     *
     * @param string $script
     * @param \Propel\Generator\Model\Column $col
     *
     * @return void
     */
    protected function addFilterByCol(string &$script, Column $col): void
    {
        $allowedArrayFilters = $this->getAllowedArrayFilters();
        $implodedArrayComparisons = implode(', ', $allowedArrayFilters);

        $this->declareClass(AmbiguousComparisonException::class);
        $this->declareClass(Criteria::class, 'Spryker');

        $colPhpName = $col->getPhpName();
        $colName = $col->getName();
        $variableName = $col->getCamelCaseName();
        $qualifiedName = $this->getColumnConstant($col);

        $script .= $this->addFilterByColBetween($col);
        $script .= $this->addFilterByColIn($col);
        $script .= $this->addFilterByColLike($col);

        $script .= "
    /**
     * Filter the query on the $colName column
     *";
        if ($col->isNumericType()) {
            $script .= "
     * Example usage:
     * <code>
     * \$query->filterBy$colPhpName(1234); // WHERE $colName = 1234
     * \$query->filterBy$colPhpName(array(12, 34), Criteria::IN); // WHERE $colName IN (12, 34)
     * \$query->filterBy$colPhpName(array('min' => 12), SprykerCriteria::BETWEEN); // WHERE $colName > 12
     * </code>";
            if ($col->isForeignKey()) {
                foreach ($col->getForeignKeys() as $fk) {
                    $script .= "
     *
     * @see       filterBy" . $this->getFKPhpNameAffix($fk) . '()';
                }
            }
            $script .= "
     *
     * @param     mixed \$$variableName The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent. Add Criteria::IN explicitly.
     *              Use associative array('min' => \$minValue, 'max' => \$maxValue) for intervals. Add SprykerCriteria::BETWEEN explicitly.";
        } elseif ($col->isTemporalType()) {
            $script .= "
     * Example usage:
     * <code>
     * \$query->filterBy$colPhpName('2011-03-14'); // WHERE $colName = '2011-03-14'
     * \$query->filterBy$colPhpName('now'); // WHERE $colName = '2011-03-14'
     * \$query->filterBy$colPhpName(array('max' => 'yesterday'), SprykerCriteria::BETWEEN); // WHERE $colName > '2011-03-13'
     * </code>
     *
     * @param     mixed \$$variableName The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent. Add Criteria::IN explicitly.
     *              Use associative array('min' => \$minValue, 'max' => \$maxValue) for intervals. Add SprykerCriteria::BETWEEN explicitly.";
        } elseif ($col->getType() == PropelTypes::PHP_ARRAY) {
            $script .= "
     * @param     array \$$variableName The values to use as filter. Use Criteria::LIKE to enable like matching of array values.";
        } elseif ($col->isTextType()) {
            $script .= "
     * Example usage:
     * <code>
     * \$query->filterBy$colPhpName('fooValue');   // WHERE $colName = 'fooValue'
     * \$query->filterBy$colPhpName('%fooValue%', Criteria::LIKE); // WHERE $colName LIKE '%fooValue%'
     * \$query->filterBy$colPhpName([1, 'foo'], Criteria::IN); // WHERE $colName IN (1, 'foo')
     * </code>
     *
     * @param     string|string[] \$$variableName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE). Add Criteria::LIKE explicitly.";
        } elseif ($col->isBooleanType()) {
            $script .= "
     * Example usage:
     * <code>
     * \$query->filterBy$colPhpName(true); // WHERE $colName = true
     * \$query->filterBy$colPhpName('yes'); // WHERE $colName = true
     * </code>
     *
     * @param     bool|string \$$variableName The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').";
        } else {
            $script .= "
     * @param     mixed \$$variableName The value to use as filter";
        }
        $script .= "
     * @param     string \$comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return \$this The current query, for fluid interface
     *
     * @throws \\Spryker\\Zed\\Propel\\Business\\Exception\\AmbiguousComparisonException
     */
    public function filterBy$colPhpName(\$$variableName = null, \$comparison = Criteria::EQUAL)
    {";
        if ($col->isNumericType() || $col->isTemporalType()) {
            $script .= "

        if (is_array(\$$variableName)) {
            \$useMinMax = false;
            if (isset(\${$variableName}['min'])) {
                if (\$comparison != SprykerCriteria::BETWEEN && \$comparison != Criteria::GREATER_EQUAL && \$comparison != Criteria::GREATER_THAN) {
                    throw new AmbiguousComparisonException('\\'min\\' requires explicit Criteria::GREATER_EQUAL, Criteria::GREATER_THAN or SprykerCriteria::BETWEEN when \\'max\\' is also needed as comparison criteria.');
                }
                \$this->addUsingAlias($qualifiedName, \${$variableName}['min'], Criteria::GREATER_EQUAL);
                \$useMinMax = true;
            }
            if (isset(\${$variableName}['max'])) {
                if (\$comparison != SprykerCriteria::BETWEEN && \$comparison != Criteria::LESS_EQUAL && \$comparison != Criteria::LESS_THAN) {
                    throw new AmbiguousComparisonException('\\'max\\' requires explicit Criteria::LESS_EQUAL, Criteria::LESS_THAN or SprykerCriteria::BETWEEN when \\'min\\' is also needed as comparison criteria.');
                }
                \$this->addUsingAlias($qualifiedName, \${$variableName}['max'], Criteria::LESS_EQUAL);
                \$useMinMax = true;
            }
            if (\$useMinMax) {
                return \$this;
            }

            if (!in_array(\$comparison, [$implodedArrayComparisons])) {
                throw new AmbiguousComparisonException('\$$variableName of type array requires one of [$implodedArrayComparisons] as comparison criteria.');
            }
        }";
        } elseif ($col->getType() == PropelTypes::OBJECT) {
            $script .= "
        if (is_object(\$$variableName)) {
            \$$variableName = serialize(\$$variableName);
        }";
        } elseif ($col->getType() == PropelTypes::PHP_ARRAY) {
            $script .= "
        \$key = \$this->getAliasedColName($qualifiedName);
        if (null === \$comparison || \$comparison == Criteria::CONTAINS_ALL) {
            foreach (\$$variableName as \$value) {
                \$value = '%| ' . \$value . ' |%';
                if (\$this->containsKey(\$key)) {
                    \$this->addAnd(\$key, \$value, Criteria::LIKE);
                } else {
                    \$this->add(\$key, \$value, Criteria::LIKE);
                }
            }

            return \$this;
        } elseif (\$comparison == Criteria::CONTAINS_SOME) {
            foreach (\$$variableName as \$value) {
                \$value = '%| ' . \$value . ' |%';
                if (\$this->containsKey(\$key)) {
                    \$this->addOr(\$key, \$value, Criteria::LIKE);
                } else {
                    \$this->add(\$key, \$value, Criteria::LIKE);
                }
            }

            return \$this;
        } elseif (\$comparison == Criteria::CONTAINS_NONE) {
            foreach (\$$variableName as \$value) {
                \$value = '%| ' . \$value . ' |%';
                if (\$this->containsKey(\$key)) {
                    \$this->addAnd(\$key, \$value, Criteria::NOT_LIKE);
                } else {
                    \$this->add(\$key, \$value, Criteria::NOT_LIKE);
                }
            }
            \$this->addOr(\$key, null, Criteria::ISNULL);

            return \$this;
        }";
        } elseif ($col->isSetType()) {
            $this->declareClasses(
                SetColumnConverter::class,
                SetColumnConverterException::class,
            );
            $script .= "
        \$valueSet = " . $this->getTableMapClassName() . '::getValueSet(' . $this->getColumnConstant($col) . ");
        try {
            \${$variableName} = SetColumnConverter::convertToInt(\${$variableName}, \$valueSet);
        } catch (SetColumnConverterException \$e) {
            throw new PropelException(sprintf('Value \"%s\" is not accepted in this set column', \$e->getValue()), \$e->getCode(), \$e);
        }
        if (null === \$comparison || \$comparison == Criteria::CONTAINS_ALL) {
            if (\${$variableName} === '0') {
                return \$this;
            }
            \$comparison = Criteria::BINARY_ALL;
        } elseif (\$comparison == Criteria::CONTAINS_SOME || \$comparison == Criteria::IN) {
            if (\${$variableName} === '0') {
                return \$this;
            }
            \$comparison = Criteria::BINARY_AND;
        } elseif (\$comparison == Criteria::CONTAINS_NONE) {
            \$key = \$this->getAliasedColName($qualifiedName);
            if (\${$variableName} !== '0') {
                \$this->add(\$key, \${$variableName}, Criteria::BINARY_NONE);
            }
            \$this->addOr(\$key, null, Criteria::ISNULL);

            return \$this;
        }";
        } elseif ($col->getType() == PropelTypes::ENUM) {
            $script .= "
        \$valueSet = " . $this->getTableMapClassName() . '::getValueSet(' . $this->getColumnConstant($col) . ");
        if (is_scalar(\$$variableName)) {
            if (!in_array(\$$variableName, \$valueSet)) {
                throw new PropelException(sprintf('Value \"%s\" is not accepted in this enumerated column', \$$variableName));
            }
            \$$variableName = array_search(\$$variableName, \$valueSet);
        } elseif (is_array(\$$variableName)) {
            if (!in_array(\$comparison, [$implodedArrayComparisons])) {
                throw new AmbiguousComparisonException('array requires one of [$implodedArrayComparisons] as comparison criteria.');
            }
            \$convertedValues = array();
            foreach (\$$variableName as \$value) {
                if (!in_array(\$value, \$valueSet)) {
                    throw new PropelException(sprintf('Value \"%s\" is not accepted in this enumerated column', \$value));
                }
                \$convertedValues []= array_search(\$value, \$valueSet);
            }
            \$$variableName = \$convertedValues;
        }";
        } elseif ($col->isTextType()) {
            $script .= "
        if (\$comparison == Criteria::LIKE || \$comparison == Criteria::ILIKE) {
            \$$variableName = str_replace('*', '%', \$$variableName);
        }

        if (is_array(\$$variableName) && !in_array(\$comparison, [$implodedArrayComparisons])) {
            throw new AmbiguousComparisonException('\$$variableName of type array requires one of [$implodedArrayComparisons] as comparison criteria.');
        }";
        } elseif ($col->isBooleanType()) {
            $script .= "
        if (is_string(\$$variableName)) {
            \$$variableName = in_array(strtolower(\$$variableName), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }";
        }

        $script .= "

        \$query = \$this->addUsingAlias($qualifiedName, \$$variableName, \$comparison);
";
        if ($col->isTextType() && filter_var($col->getAttribute(static::ATTRIBUTE_CASE_INSENSITIVE), FILTER_VALIDATE_BOOLEAN) === true) {
            $script .= "
        /** @var \\Propel\\Runtime\\ActiveQuery\\Criterion\\BasicCriterion \$criterion */
        \$criterion = \$query->getCriterion($qualifiedName);
        \$criterion->setIgnoreCase(true);
";
        }

        $script .= "
        return \$query;
    }
";
    }

    /**
     * @param string $script
     *
     * @return void
     */
    protected function addClassBody(string &$script): void
    {
        $classes = $this->getFactory()
            ->createFindClassNamespacesCollector()
            ->extractClassesToDeclare();

        foreach ($classes as $class) {
            $this->declareClass($class);
        }

        $this->addForUpdate($script);
        $this->addPostUpdate($script);
        $this->addPostDelete($script);
        $this->addFind($script);
        $this->addFindOne($script);
        $this->addExists($script);
        $this->addConfigureSelectColumns($script);

        parent::addClassBody($script);
    }

    /**
     * @deprecated You can achieve same results with {@link \Propel\Runtime\ActiveQuery\Criteria::lockForUpdate()}.
     *
     * @param string $script
     *
     * @return void
     */
    protected function addForUpdate(string &$script): void
    {
        $script .= "
    /**
     * @var bool
     */
    protected \$isForUpdateEnabled = false;

    /**
     * @deprecated Use {@link \Propel\Runtime\ActiveQuery\Criteria::lockForUpdate()} instead.
     *
     * @param bool \$isForUpdateEnabled
     *
     * @return \$this The primary criteria object
     */
    public function forUpdate(\$isForUpdateEnabled)
    {
        \$this->isForUpdateEnabled = \$isForUpdateEnabled;

        return \$this;
    }

    /**
     * @param array \$params
     *
     * @return string
     */
    public function createSelectSql(&\$params): string
    {
        \$sql = parent::createSelectSql(\$params);
        if (\$this->isForUpdateEnabled) {
            \$sql .= ' FOR UPDATE';
        }

        return \$sql;
    }

    /**
     * Clear the conditions to allow the reuse of the query object.
     * The ModelCriteria's Model and alias 'all the properties set by construct) will remain.
     *
     * @return \$this The primary criteria object
     */
    public function clear()
    {
        parent::clear();

        \$this->isSelfSelected = false;
        \$this->forUpdate(false);

        return \$this;
    }\n
    ";
    }

    /**
     * Adds custom postUpdate hook to Propel instance
     *
     * @param string $script
     *
     * @return void
     */
    protected function addPostUpdate(string &$script): void
    {
        $script .= "
    /**
     * @param int \$affectedRows
     * @param \\Propel\\Runtime\\Connection\\ConnectionInterface \$con
     *
     * @return int|null
     */
    protected function postUpdate(int \$affectedRows, ConnectionInterface \$con): ?int
    {";

        $extensionPlugins = $this->getFactory()->getPostUpdateExtensionPlugins();
        foreach ($extensionPlugins as $plugin) {
            $script = $plugin->extend($script);
        }

        $script .= "
        return null;
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
    protected function addPostDelete(string &$script): void
    {
        $script .= "
    /**
     * @param int \$affectedRows
     * @param \\Propel\\Runtime\\Connection\\ConnectionInterface \$con
     *
     * @return int|null
     */
    protected function postDelete(int \$affectedRows, ConnectionInterface \$con): ?int
    {";

        $extensionPlugins = $this->getFactory()->getPostDeleteExtensionPlugins();
        foreach ($extensionPlugins as $plugin) {
            $script = $plugin->extend($script);
        }

        $script .= "
        return null;
    }
    ";
    }

    /**
     * @param string $script
     *
     * @return void
     */
    protected function addFindPk(string &$script): void
    {
        $class = $this->getObjectClassName();
        $tableMapClassName = $this->getTableMapClassName();
        $table = $this->getTable();

        $script .= "
    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *";
        if ($table->hasCompositePrimaryKey()) {
            $pks = $table->getPrimaryKey();
            $examplePk = array_slice([12, 34, 56, 78, 91], 0, count($pks));
            $colNames = [];
            foreach ($pks as $col) {
                $colNames[] = '$' . $col->getName();
            }
            $pkType = 'array[' . implode(', ', $colNames) . ']';
            $script .= "
     * <code>
     * \$obj = \$c->findPk(array(" . implode(', ', $examplePk) . '), $con);';
        } else {
            $pkType = 'mixed';
            $script .= "
     * <code>
     * \$obj  = \$c->findPk(12, \$con);";
        }
        $script .= "
     * </code>
     *
     * @param " . $pkType . " \$key Primary key to use for the query
     * @param ConnectionInterface \$con an optional connection object
     *
     * @return $class|array|mixed the result, formatted by the current formatter
     */
    public function findPk(\$key, ?ConnectionInterface \$con = null)
    {";
        if (!$table->hasPrimaryKey()) {
            $this->declareClass('Propel\\Runtime\\Exception\\LogicException');
            $script .= "
        throw new LogicException('The {$this->getObjectName()} object has no primary key');
    }
";

            return;
        }

        $script .= "
        if (\$key === null) {
            return null;
        }";
        if ($table->hasCompositePrimaryKey()) {
            $numberOfPks = count($table->getPrimaryKey());
            $pkIndexes = range(0, $numberOfPks - 1);
            $pks = preg_filter('/(\d+)/', '$key[${1}]', $pkIndexes); // put ids into "$key[]"
        } else {
            $pks = '$key';
        }
        $pkHash = $this->getTableMapBuilder()->getInstancePoolKeySnippet($pks);

        $extensionPlugins = $this->getFactory()->getFindExtensionPlugins();
        foreach ($extensionPlugins as $plugin) {
            $script = $plugin->extend($script);
        }

        $script .= "

        if (\$con === null) {
            \$con = Propel::getServiceContainer()->getReadConnection(\$this->getDbName());
        }

        \$this->basePreSelect(\$con);

        if (
            \$this->formatter || \$this->modelAlias || \$this->with || \$this->select
            || \$this->selectColumns || \$this->asColumns || \$this->selectModifiers
            || \$this->map || \$this->having || \$this->joins
        ) {
            return \$this->findPkComplex(\$key, \$con);
        }

        if ((null !== (\$obj = {$tableMapClassName}::getInstanceFromPool({$pkHash})))) {
            // the object is already in the instance pool
            return \$obj;
        }

        return \$this->findPkSimple(\$key, \$con);
    }
";
    }

    /**
     * Adds the findPks method for this object.
     *
     * @param string $script The script will be modified in this method.
     *
     * @return void
     */
    protected function addFindPks(string &$script): void
    {
        $this->declareClasses(
            '\Propel\Runtime\Collection\ObjectCollection',
            '\Propel\Runtime\Collection\Collection',
            '\Propel\Runtime\Connection\ConnectionInterface',
            '\Propel\Runtime\Propel',
        );
        $table = $this->getTable();
        $pks = $table->getPrimaryKey();
        $count = count($pks);
        $script .= "
    /**
     * Find objects by primary key
     * <code>";
        if ($count === 1) {
            $script .= "
     * \$objs = \$c->findPks(array(12, 56, 832), \$con);";
        } else {
            $script .= "
     * \$objs = \$c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), \$con);";
        }
        $script .= "
     * </code>
     * @param     array \$keys Primary keys to use for the query
     * @param     ConnectionInterface \$con an optional connection object
     *
     * @return    Collection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks(\$keys, ConnectionInterface \$con = null)
    {";
        if (!$table->hasPrimaryKey()) {
            $this->declareClass('Propel\\Runtime\\Exception\\LogicException');
            $script .= "
        throw new LogicException('The {$this->getObjectName()} object has no primary key');
    }
";

            return;
        }

        $extensionPlugins = $this->getFactory()->getFindExtensionPlugins();
        foreach ($extensionPlugins as $plugin) {
            $script = $plugin->extend($script);
        }

        $script .= "

        if (\$con === null) {
            \$con = Propel::getServiceContainer()->getReadConnection(\$this->getDbName());
        }

        \$this->basePreSelect(\$con);
        \$criteria = \$this->isKeepQuery() ? clone \$this : \$this;
        \$dataFetcher = \$criteria
            ->filterByPrimaryKeys(\$keys)
            ->doSelect(\$con);

        return \$criteria->getFormatter()->init(\$criteria)->format(\$dataFetcher);
    }
";
    }

    /**
     * Adds the find method for this object.
     *
     * @param string $script The script will be modified in this method.
     *
     * @return void
     */
    protected function addFind(string &$script): void
    {
        $script .= "
    /**
     * Issue a SELECT query based on the current ModelCriteria
     * and format the list of results with the current formatter
     * By default, returns an array of model objects
     *
     * @param \\Propel\\Runtime\\Connection\\ConnectionInterface|null \$con an optional connection object
     *
     * @return \\Propel\\Runtime\\Collection\\ObjectCollection|\\Propel\\Runtime\\ActiveRecord\\ActiveRecordInterface[]|mixed the list of results, formatted by the current formatter
     */
    public function find(?ConnectionInterface \$con = null)
    {";

        $extensionPlugins = $this->getFactory()->getFindExtensionPlugins();
        foreach ($extensionPlugins as $plugin) {
            $script = $plugin->extend($script);
        }

        $script .= "
        return parent::find(\$con);
    }
    ";
    }

    /**
     * Adds the findOne method for this object.
     *
     * @param string $script The script will be modified in this method.
     *
     * @return void
     */
    protected function addFindOne(string &$script): void
    {
        $script .= "
    /**
     * Issue a SELECT ... LIMIT 1 query based on the current ModelCriteria
     * and format the result with the current formatter
     * By default, returns a model object.
     *
     * Does not work with ->with()s containing one-to-many relations.
     *
     * @param \\Propel\\Runtime\\Connection\\ConnectionInterface|null \$con an optional connection object
     *
     * @return mixed the result, formatted by the current formatter
     */
    public function findOne(?ConnectionInterface \$con = null)
    {";

        $extensionPlugins = $this->getFactory()->getFindExtensionPlugins();
        foreach ($extensionPlugins as $plugin) {
            $script = $plugin->extend($script);
        }

        $script .= "
        return parent::findOne(\$con);
    }
    ";
    }

    /**
     * Adds the exists method for this object.
     *
     * @param string $script The script will be modified in this method.
     *
     * @return void
     */
    protected function addExists(string &$script): void
    {
        $script .= "
    /**
     * Issue an existence check on the current ModelCriteria
     *
     * @param \\Propel\\Runtime\\Connection\\ConnectionInterface|null \$con an optional connection object
     *
     * @return bool column existence
     */
    public function exists(?ConnectionInterface \$con = null): bool
    {";

        $extensionPlugins = $this->getFactory()->getFindExtensionPlugins();
        foreach ($extensionPlugins as $plugin) {
            $script = $plugin->extend($script);
        }

        $script .= "
        return parent::exists(\$con);
    }
    ";
    }

    /**
     * Specification:
     * - Overrides configureSelectColumns() method of parent class.
     * - Uses TypeAwareSimpleArrayFormatter class to cast boolean values.
     *
     * @param string $script
     *
     * @return void
     */
    protected function addConfigureSelectColumns(string &$script): void
    {
        if (!$this->getConfig()->isBooleanCastingEnabled()) {
            return;
        }
        $this->declareClass(TypeAwareSimpleArrayFormatter::class);

        $script .= "
    /**
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function configureSelectColumns(): void
    {
        if (!\$this->select) {
            return;
        }

        if (\$this->formatter === null) {
            \$this->setFormatter(new TypeAwareSimpleArrayFormatter());
        }

        parent::configureSelectColumns();
     }
    ";
    }
}
