<?php

namespace SprykerFeature\Zed\QueueDistributor\Persistence\Propel\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueType as ChildSpyQueueType;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueTypeQuery as ChildSpyQueueTypeQuery;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\Map\SpyQueueTypeTableMap;

/**
 * Base class that represents a query for the 'spy_queue_type' table.
 *
 *
 *
 * @method     ChildSpyQueueTypeQuery orderByIdQueueType($order = Criteria::ASC) Order by the id_queue_type column
 * @method     ChildSpyQueueTypeQuery orderByKey($order = Criteria::ASC) Order by the key column
 * @method     ChildSpyQueueTypeQuery orderByLastDistribution($order = Criteria::ASC) Order by the last_distribution column
 *
 * @method     ChildSpyQueueTypeQuery groupByIdQueueType() Group by the id_queue_type column
 * @method     ChildSpyQueueTypeQuery groupByKey() Group by the key column
 * @method     ChildSpyQueueTypeQuery groupByLastDistribution() Group by the last_distribution column
 *
 * @method     ChildSpyQueueTypeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSpyQueueTypeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSpyQueueTypeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSpyQueueTypeQuery leftJoinSpyQueueTouch($relationAlias = null) Adds a LEFT JOIN clause to the query using the SpyQueueTouch relation
 * @method     ChildSpyQueueTypeQuery rightJoinSpyQueueTouch($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SpyQueueTouch relation
 * @method     ChildSpyQueueTypeQuery innerJoinSpyQueueTouch($relationAlias = null) Adds a INNER JOIN clause to the query using the SpyQueueTouch relation
 *
 * @method     \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueTouchQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildSpyQueueType findOne(ConnectionInterface $con = null) Return the first ChildSpyQueueType matching the query
 * @method     ChildSpyQueueType findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSpyQueueType matching the query, or a new ChildSpyQueueType object populated from the query conditions when no match is found
 *
 * @method     ChildSpyQueueType findOneByIdQueueType(int $id_queue_type) Return the first ChildSpyQueueType filtered by the id_queue_type column
 * @method     ChildSpyQueueType findOneByKey(string $key) Return the first ChildSpyQueueType filtered by the key column
 * @method     ChildSpyQueueType findOneByLastDistribution(string $last_distribution) Return the first ChildSpyQueueType filtered by the last_distribution column
 *
 * @method     ChildSpyQueueType[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSpyQueueType objects based on current ModelCriteria
 * @method     ChildSpyQueueType[]|ObjectCollection findByIdQueueType(int $id_queue_type) Return ChildSpyQueueType objects filtered by the id_queue_type column
 * @method     ChildSpyQueueType[]|ObjectCollection findByKey(string $key) Return ChildSpyQueueType objects filtered by the key column
 * @method     ChildSpyQueueType[]|ObjectCollection findByLastDistribution(string $last_distribution) Return ChildSpyQueueType objects filtered by the last_distribution column
 * @method     ChildSpyQueueType[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SpyQueueTypeQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\Base\SpyQueueTypeQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'zed', $modelName = '\\SprykerFeature\\Zed\\QueueDistributor\\Persistence\\Propel\\SpyQueueType', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSpyQueueTypeQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSpyQueueTypeQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSpyQueueTypeQuery) {
            return $criteria;
        }
        $query = new ChildSpyQueueTypeQuery();
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
     * @return ChildSpyQueueType|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SpyQueueTypeTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SpyQueueTypeTableMap::DATABASE_NAME);
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
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSpyQueueType A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id_queue_type, key, last_distribution FROM spy_queue_type WHERE id_queue_type = :p0';
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
            /** @var ChildSpyQueueType $obj */

            /* @var $locator \Generated\Zed\Ide\AutoCompletion */
            $locator = \SprykerEngine\Zed\Kernel\Locator::getInstance();
            $obj = $locator->queueDistributor()->entitySpyQueueType();

            $obj->hydrate($row);
            SpyQueueTypeTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildSpyQueueType|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildSpyQueueTypeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SpyQueueTypeTableMap::COL_ID_QUEUE_TYPE, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSpyQueueTypeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SpyQueueTypeTableMap::COL_ID_QUEUE_TYPE, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id_queue_type column
     *
     * Example usage:
     * <code>
     * $query->filterByIdQueueType(1234); // WHERE id_queue_type = 1234
     * $query->filterByIdQueueType(array(12, 34)); // WHERE id_queue_type IN (12, 34)
     * $query->filterByIdQueueType(array('min' => 12)); // WHERE id_queue_type > 12
     * </code>
     *
     * @param     mixed $idQueueType The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSpyQueueTypeQuery The current query, for fluid interface
     */
    public function filterByIdQueueType($idQueueType = null, $comparison = null)
    {
        if (is_array($idQueueType)) {
            $useMinMax = false;
            if (isset($idQueueType['min'])) {
                $this->addUsingAlias(SpyQueueTypeTableMap::COL_ID_QUEUE_TYPE, $idQueueType['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idQueueType['max'])) {
                $this->addUsingAlias(SpyQueueTypeTableMap::COL_ID_QUEUE_TYPE, $idQueueType['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SpyQueueTypeTableMap::COL_ID_QUEUE_TYPE, $idQueueType, $comparison);
    }

    /**
     * Filter the query on the key column
     *
     * Example usage:
     * <code>
     * $query->filterByKey('fooValue');   // WHERE key = 'fooValue'
     * $query->filterByKey('%fooValue%'); // WHERE key LIKE '%fooValue%'
     * </code>
     *
     * @param     string $key The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSpyQueueTypeQuery The current query, for fluid interface
     */
    public function filterByKey($key = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($key)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $key)) {
                $key = str_replace('*', '%', $key);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SpyQueueTypeTableMap::COL_KEY, $key, $comparison);
    }

    /**
     * Filter the query on the last_distribution column
     *
     * Example usage:
     * <code>
     * $query->filterByLastDistribution('2011-03-14'); // WHERE last_distribution = '2011-03-14'
     * $query->filterByLastDistribution('now'); // WHERE last_distribution = '2011-03-14'
     * $query->filterByLastDistribution(array('max' => 'yesterday')); // WHERE last_distribution > '2011-03-13'
     * </code>
     *
     * @param     mixed $lastDistribution The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSpyQueueTypeQuery The current query, for fluid interface
     */
    public function filterByLastDistribution($lastDistribution = null, $comparison = null)
    {
        if (is_array($lastDistribution)) {
            $useMinMax = false;
            if (isset($lastDistribution['min'])) {
                $this->addUsingAlias(SpyQueueTypeTableMap::COL_LAST_DISTRIBUTION, $lastDistribution['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lastDistribution['max'])) {
                $this->addUsingAlias(SpyQueueTypeTableMap::COL_LAST_DISTRIBUTION, $lastDistribution['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SpyQueueTypeTableMap::COL_LAST_DISTRIBUTION, $lastDistribution, $comparison);
    }

    /**
     * Filter the query by a related \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueTouch object
     *
     * @param \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueTouch|ObjectCollection $spyQueueTouch  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSpyQueueTypeQuery The current query, for fluid interface
     */
    public function filterBySpyQueueTouch($spyQueueTouch, $comparison = null)
    {
        if ($spyQueueTouch instanceof \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueTouch) {
            return $this
                ->addUsingAlias(SpyQueueTypeTableMap::COL_ID_QUEUE_TYPE, $spyQueueTouch->getFkQueueType(), $comparison);
        } elseif ($spyQueueTouch instanceof ObjectCollection) {
            return $this
                ->useSpyQueueTouchQuery()
                ->filterByPrimaryKeys($spyQueueTouch->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySpyQueueTouch() only accepts arguments of type \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueTouch or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SpyQueueTouch relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSpyQueueTypeQuery The current query, for fluid interface
     */
    public function joinSpyQueueTouch($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SpyQueueTouch');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'SpyQueueTouch');
        }

        return $this;
    }

    /**
     * Use the SpyQueueTouch relation SpyQueueTouch object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueTouchQuery A secondary query class using the current class as primary query
     */
    public function useSpyQueueTouchQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSpyQueueTouch($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SpyQueueTouch', '\SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueTouchQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSpyQueueType $spyQueueType Object to remove from the list of results
     *
     * @return $this|ChildSpyQueueTypeQuery The current query, for fluid interface
     */
    public function prune($spyQueueType = null)
    {
        if ($spyQueueType) {
            $this->addUsingAlias(SpyQueueTypeTableMap::COL_ID_QUEUE_TYPE, $spyQueueType->getIdQueueType(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the spy_queue_type table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SpyQueueTypeTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SpyQueueTypeTableMap::clearInstancePool();
            SpyQueueTypeTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(SpyQueueTypeTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SpyQueueTypeTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SpyQueueTypeTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SpyQueueTypeTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // SpyQueueTypeQuery
