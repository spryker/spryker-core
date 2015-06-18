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
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItemType as ChildSpyQueueItemType;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItemTypeQuery as ChildSpyQueueItemTypeQuery;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\Map\SpyQueueItemTypeTableMap;

/**
 * Base class that represents a query for the 'spy_queue_item_type' table.
 *
 *
 *
 * @method     ChildSpyQueueItemTypeQuery orderByIdQueueItemType($order = Criteria::ASC) Order by the id_queue_item_type column
 * @method     ChildSpyQueueItemTypeQuery orderByKey($order = Criteria::ASC) Order by the key column
 * @method     ChildSpyQueueItemTypeQuery orderByLastDistribution($order = Criteria::ASC) Order by the last_distribution column
 *
 * @method     ChildSpyQueueItemTypeQuery groupByIdQueueItemType() Group by the id_queue_item_type column
 * @method     ChildSpyQueueItemTypeQuery groupByKey() Group by the key column
 * @method     ChildSpyQueueItemTypeQuery groupByLastDistribution() Group by the last_distribution column
 *
 * @method     ChildSpyQueueItemTypeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSpyQueueItemTypeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSpyQueueItemTypeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSpyQueueItemTypeQuery leftJoinSpyQueueItem($relationAlias = null) Adds a LEFT JOIN clause to the query using the SpyQueueItem relation
 * @method     ChildSpyQueueItemTypeQuery rightJoinSpyQueueItem($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SpyQueueItem relation
 * @method     ChildSpyQueueItemTypeQuery innerJoinSpyQueueItem($relationAlias = null) Adds a INNER JOIN clause to the query using the SpyQueueItem relation
 *
 * @method     \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItemQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildSpyQueueItemType findOne(ConnectionInterface $con = null) Return the first ChildSpyQueueItemType matching the query
 * @method     ChildSpyQueueItemType findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSpyQueueItemType matching the query, or a new ChildSpyQueueItemType object populated from the query conditions when no match is found
 *
 * @method     ChildSpyQueueItemType findOneByIdQueueItemType(int $id_queue_item_type) Return the first ChildSpyQueueItemType filtered by the id_queue_item_type column
 * @method     ChildSpyQueueItemType findOneByKey(string $key) Return the first ChildSpyQueueItemType filtered by the key column
 * @method     ChildSpyQueueItemType findOneByLastDistribution(string $last_distribution) Return the first ChildSpyQueueItemType filtered by the last_distribution column
 *
 * @method     ChildSpyQueueItemType[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSpyQueueItemType objects based on current ModelCriteria
 * @method     ChildSpyQueueItemType[]|ObjectCollection findByIdQueueItemType(int $id_queue_item_type) Return ChildSpyQueueItemType objects filtered by the id_queue_item_type column
 * @method     ChildSpyQueueItemType[]|ObjectCollection findByKey(string $key) Return ChildSpyQueueItemType objects filtered by the key column
 * @method     ChildSpyQueueItemType[]|ObjectCollection findByLastDistribution(string $last_distribution) Return ChildSpyQueueItemType objects filtered by the last_distribution column
 * @method     ChildSpyQueueItemType[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SpyQueueItemTypeQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\Base\SpyQueueItemTypeQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'zed', $modelName = '\\SprykerFeature\\Zed\\QueueDistributor\\Persistence\\Propel\\SpyQueueItemType', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSpyQueueItemTypeQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSpyQueueItemTypeQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSpyQueueItemTypeQuery) {
            return $criteria;
        }
        $query = new ChildSpyQueueItemTypeQuery();
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
     * @return ChildSpyQueueItemType|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SpyQueueItemTypeTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SpyQueueItemTypeTableMap::DATABASE_NAME);
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
     * @return ChildSpyQueueItemType A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id_queue_item_type, key, last_distribution FROM spy_queue_item_type WHERE id_queue_item_type = :p0';
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
            /** @var ChildSpyQueueItemType $obj */

            /* @var $locator \Generated\Zed\Ide\AutoCompletion */
            $locator = \SprykerEngine\Zed\Kernel\Locator::getInstance();
            $obj = $locator->queueDistributor()->entitySpyQueueItemType();

            $obj->hydrate($row);
            SpyQueueItemTypeTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildSpyQueueItemType|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildSpyQueueItemTypeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SpyQueueItemTypeTableMap::COL_ID_QUEUE_ITEM_TYPE, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSpyQueueItemTypeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SpyQueueItemTypeTableMap::COL_ID_QUEUE_ITEM_TYPE, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id_queue_item_type column
     *
     * Example usage:
     * <code>
     * $query->filterByIdQueueItemType(1234); // WHERE id_queue_item_type = 1234
     * $query->filterByIdQueueItemType(array(12, 34)); // WHERE id_queue_item_type IN (12, 34)
     * $query->filterByIdQueueItemType(array('min' => 12)); // WHERE id_queue_item_type > 12
     * </code>
     *
     * @param     mixed $idQueueItemType The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSpyQueueItemTypeQuery The current query, for fluid interface
     */
    public function filterByIdQueueItemType($idQueueItemType = null, $comparison = null)
    {
        if (is_array($idQueueItemType)) {
            $useMinMax = false;
            if (isset($idQueueItemType['min'])) {
                $this->addUsingAlias(SpyQueueItemTypeTableMap::COL_ID_QUEUE_ITEM_TYPE, $idQueueItemType['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idQueueItemType['max'])) {
                $this->addUsingAlias(SpyQueueItemTypeTableMap::COL_ID_QUEUE_ITEM_TYPE, $idQueueItemType['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SpyQueueItemTypeTableMap::COL_ID_QUEUE_ITEM_TYPE, $idQueueItemType, $comparison);
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
     * @return $this|ChildSpyQueueItemTypeQuery The current query, for fluid interface
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

        return $this->addUsingAlias(SpyQueueItemTypeTableMap::COL_KEY, $key, $comparison);
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
     * @return $this|ChildSpyQueueItemTypeQuery The current query, for fluid interface
     */
    public function filterByLastDistribution($lastDistribution = null, $comparison = null)
    {
        if (is_array($lastDistribution)) {
            $useMinMax = false;
            if (isset($lastDistribution['min'])) {
                $this->addUsingAlias(SpyQueueItemTypeTableMap::COL_LAST_DISTRIBUTION, $lastDistribution['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lastDistribution['max'])) {
                $this->addUsingAlias(SpyQueueItemTypeTableMap::COL_LAST_DISTRIBUTION, $lastDistribution['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SpyQueueItemTypeTableMap::COL_LAST_DISTRIBUTION, $lastDistribution, $comparison);
    }

    /**
     * Filter the query by a related \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItem object
     *
     * @param \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItem|ObjectCollection $spyQueueItem  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSpyQueueItemTypeQuery The current query, for fluid interface
     */
    public function filterBySpyQueueItem($spyQueueItem, $comparison = null)
    {
        if ($spyQueueItem instanceof \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItem) {
            return $this
                ->addUsingAlias(SpyQueueItemTypeTableMap::COL_ID_QUEUE_ITEM_TYPE, $spyQueueItem->getFkItemType(), $comparison);
        } elseif ($spyQueueItem instanceof ObjectCollection) {
            return $this
                ->useSpyQueueItemQuery()
                ->filterByPrimaryKeys($spyQueueItem->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySpyQueueItem() only accepts arguments of type \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItem or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SpyQueueItem relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSpyQueueItemTypeQuery The current query, for fluid interface
     */
    public function joinSpyQueueItem($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SpyQueueItem');

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
            $this->addJoinObject($join, 'SpyQueueItem');
        }

        return $this;
    }

    /**
     * Use the SpyQueueItem relation SpyQueueItem object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItemQuery A secondary query class using the current class as primary query
     */
    public function useSpyQueueItemQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSpyQueueItem($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SpyQueueItem', '\SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItemQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSpyQueueItemType $spyQueueItemType Object to remove from the list of results
     *
     * @return $this|ChildSpyQueueItemTypeQuery The current query, for fluid interface
     */
    public function prune($spyQueueItemType = null)
    {
        if ($spyQueueItemType) {
            $this->addUsingAlias(SpyQueueItemTypeTableMap::COL_ID_QUEUE_ITEM_TYPE, $spyQueueItemType->getIdQueueItemType(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the spy_queue_item_type table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SpyQueueItemTypeTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SpyQueueItemTypeTableMap::clearInstancePool();
            SpyQueueItemTypeTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(SpyQueueItemTypeTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SpyQueueItemTypeTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SpyQueueItemTypeTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SpyQueueItemTypeTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // SpyQueueItemTypeQuery
