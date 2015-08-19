<?php

namespace SprykerFeature\Zed\UiExample\Persistence\Propel\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\UiExample\Persistence\Propel\SpyUiExample as ChildSpyUiExample;
use SprykerFeature\Zed\UiExample\Persistence\Propel\SpyUiExampleQuery as ChildSpyUiExampleQuery;
use SprykerFeature\Zed\UiExample\Persistence\Propel\Map\SpyUiExampleTableMap;

/**
 * Base class that represents a query for the 'spy_ui_example' table.
 *
 *
 *
 * @method     ChildSpyUiExampleQuery orderByIdUiExample($order = Criteria::ASC) Order by the id_ui_example column
 * @method     ChildSpyUiExampleQuery orderByColumnForString($order = Criteria::ASC) Order by the column_for_string column
 * @method     ChildSpyUiExampleQuery orderByColumnForBoolean($order = Criteria::ASC) Order by the column_for_boolean column
 * @method     ChildSpyUiExampleQuery orderByColumnForTimestamp($order = Criteria::ASC) Order by the column_for_timestamp column
 *
 * @method     ChildSpyUiExampleQuery groupByIdUiExample() Group by the id_ui_example column
 * @method     ChildSpyUiExampleQuery groupByColumnForString() Group by the column_for_string column
 * @method     ChildSpyUiExampleQuery groupByColumnForBoolean() Group by the column_for_boolean column
 * @method     ChildSpyUiExampleQuery groupByColumnForTimestamp() Group by the column_for_timestamp column
 *
 * @method     ChildSpyUiExampleQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSpyUiExampleQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSpyUiExampleQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSpyUiExampleQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildSpyUiExampleQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildSpyUiExampleQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildSpyUiExample findOne(ConnectionInterface $con = null) Return the first ChildSpyUiExample matching the query
 * @method     ChildSpyUiExample findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSpyUiExample matching the query, or a new ChildSpyUiExample object populated from the query conditions when no match is found
 *
 * @method     ChildSpyUiExample findOneByIdUiExample(int $id_ui_example) Return the first ChildSpyUiExample filtered by the id_ui_example column
 * @method     ChildSpyUiExample findOneByColumnForString(string $column_for_string) Return the first ChildSpyUiExample filtered by the column_for_string column
 * @method     ChildSpyUiExample findOneByColumnForBoolean(boolean $column_for_boolean) Return the first ChildSpyUiExample filtered by the column_for_boolean column
 * @method     ChildSpyUiExample findOneByColumnForTimestamp(string $column_for_timestamp) Return the first ChildSpyUiExample filtered by the column_for_timestamp column *

 * @method     ChildSpyUiExample requirePk($key, ConnectionInterface $con = null) Return the ChildSpyUiExample by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSpyUiExample requireOne(ConnectionInterface $con = null) Return the first ChildSpyUiExample matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSpyUiExample requireOneByIdUiExample(int $id_ui_example) Return the first ChildSpyUiExample filtered by the id_ui_example column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSpyUiExample requireOneByColumnForString(string $column_for_string) Return the first ChildSpyUiExample filtered by the column_for_string column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSpyUiExample requireOneByColumnForBoolean(boolean $column_for_boolean) Return the first ChildSpyUiExample filtered by the column_for_boolean column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSpyUiExample requireOneByColumnForTimestamp(string $column_for_timestamp) Return the first ChildSpyUiExample filtered by the column_for_timestamp column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSpyUiExample[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSpyUiExample objects based on current ModelCriteria
 * @method     ChildSpyUiExample[]|ObjectCollection findByIdUiExample(int $id_ui_example) Return ChildSpyUiExample objects filtered by the id_ui_example column
 * @method     ChildSpyUiExample[]|ObjectCollection findByColumnForString(string $column_for_string) Return ChildSpyUiExample objects filtered by the column_for_string column
 * @method     ChildSpyUiExample[]|ObjectCollection findByColumnForBoolean(boolean $column_for_boolean) Return ChildSpyUiExample objects filtered by the column_for_boolean column
 * @method     ChildSpyUiExample[]|ObjectCollection findByColumnForTimestamp(string $column_for_timestamp) Return ChildSpyUiExample objects filtered by the column_for_timestamp column
 * @method     ChildSpyUiExample[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SpyUiExampleQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \SprykerFeature\Zed\UiExample\Persistence\Propel\Base\SpyUiExampleQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'zed', $modelName = '\\SprykerFeature\\Zed\\UiExample\\Persistence\\Propel\\SpyUiExample', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSpyUiExampleQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSpyUiExampleQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSpyUiExampleQuery) {
            return $criteria;
        }
        $query = new ChildSpyUiExampleQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildSpyUiExample|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SpyUiExampleTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SpyUiExampleTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @return ChildSpyUiExample A model object, or null if the key is not found
     * @throws \Propel\Runtime\Exception\PropelException
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id_ui_example, column_for_string, column_for_boolean, column_for_timestamp FROM spy_ui_example WHERE id_ui_example = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildSpyUiExample $obj */

            /** @var \Generated\Zed\Ide\AutoCompletion $locator */
            $locator = \SprykerEngine\Zed\Kernel\Locator::getInstance();
            $obj = $locator->uiExample()->entitySpyUiExample();

            $obj->hydrate($row);
            SpyUiExampleTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildSpyUiExample|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildSpyUiExampleQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SpyUiExampleTableMap::COL_ID_UI_EXAMPLE, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSpyUiExampleQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SpyUiExampleTableMap::COL_ID_UI_EXAMPLE, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id_ui_example column
     *
     * Example usage:
     * <code>
     * $query->filterByIdUiExample(1234); // WHERE id_ui_example = 1234
     * $query->filterByIdUiExample(array(12, 34)); // WHERE id_ui_example IN (12, 34)
     * $query->filterByIdUiExample(array('min' => 12)); // WHERE id_ui_example > 12
     * </code>
     *
     * @param     mixed $idUiExample The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSpyUiExampleQuery The current query, for fluid interface
     */
    public function filterByIdUiExample($idUiExample = null, $comparison = null)
    {
        if (is_array($idUiExample)) {
            $useMinMax = false;
            if (isset($idUiExample['min'])) {
                $this->addUsingAlias(SpyUiExampleTableMap::COL_ID_UI_EXAMPLE, $idUiExample['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idUiExample['max'])) {
                $this->addUsingAlias(SpyUiExampleTableMap::COL_ID_UI_EXAMPLE, $idUiExample['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SpyUiExampleTableMap::COL_ID_UI_EXAMPLE, $idUiExample, $comparison);
    }

    /**
     * Filter the query on the column_for_string column
     *
     * Example usage:
     * <code>
     * $query->filterByColumnForString('fooValue');   // WHERE column_for_string = 'fooValue'
     * $query->filterByColumnForString('%fooValue%'); // WHERE column_for_string LIKE '%fooValue%'
     * </code>
     *
     * @param     string $columnForString The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSpyUiExampleQuery The current query, for fluid interface
     */
    public function filterByColumnForString($columnForString = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($columnForString)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $columnForString)) {
                $columnForString = str_replace('*', '%', $columnForString);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SpyUiExampleTableMap::COL_COLUMN_FOR_STRING, $columnForString, $comparison);
    }

    /**
     * Filter the query on the column_for_boolean column
     *
     * Example usage:
     * <code>
     * $query->filterByColumnForBoolean(true); // WHERE column_for_boolean = true
     * $query->filterByColumnForBoolean('yes'); // WHERE column_for_boolean = true
     * </code>
     *
     * @param     boolean|string $columnForBoolean The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSpyUiExampleQuery The current query, for fluid interface
     */
    public function filterByColumnForBoolean($columnForBoolean = null, $comparison = null)
    {
        if (is_string($columnForBoolean)) {
            $columnForBoolean = in_array(strtolower($columnForBoolean), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(SpyUiExampleTableMap::COL_COLUMN_FOR_BOOLEAN, $columnForBoolean, $comparison);
    }

    /**
     * Filter the query on the column_for_timestamp column
     *
     * Example usage:
     * <code>
     * $query->filterByColumnForTimestamp('2011-03-14'); // WHERE column_for_timestamp = '2011-03-14'
     * $query->filterByColumnForTimestamp('now'); // WHERE column_for_timestamp = '2011-03-14'
     * $query->filterByColumnForTimestamp(array('max' => 'yesterday')); // WHERE column_for_timestamp > '2011-03-13'
     * </code>
     *
     * @param     mixed $columnForTimestamp The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSpyUiExampleQuery The current query, for fluid interface
     */
    public function filterByColumnForTimestamp($columnForTimestamp = null, $comparison = null)
    {
        if (is_array($columnForTimestamp)) {
            $useMinMax = false;
            if (isset($columnForTimestamp['min'])) {
                $this->addUsingAlias(SpyUiExampleTableMap::COL_COLUMN_FOR_TIMESTAMP, $columnForTimestamp['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($columnForTimestamp['max'])) {
                $this->addUsingAlias(SpyUiExampleTableMap::COL_COLUMN_FOR_TIMESTAMP, $columnForTimestamp['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SpyUiExampleTableMap::COL_COLUMN_FOR_TIMESTAMP, $columnForTimestamp, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSpyUiExample $spyUiExample Object to remove from the list of results
     *
     * @return $this|ChildSpyUiExampleQuery The current query, for fluid interface
     */
    public function prune($spyUiExample = null)
    {
        if ($spyUiExample) {
            $this->addUsingAlias(SpyUiExampleTableMap::COL_ID_UI_EXAMPLE, $spyUiExample->getIdUiExample(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the spy_ui_example table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SpyUiExampleTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SpyUiExampleTableMap::clearInstancePool();
            SpyUiExampleTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SpyUiExampleTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SpyUiExampleTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SpyUiExampleTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SpyUiExampleTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // SpyUiExampleQuery
