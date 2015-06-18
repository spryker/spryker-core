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
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItem as ChildSpyQueueItem;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItemQuery as ChildSpyQueueItemQuery;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\Map\SpyQueueItemTableMap;

/**
 * Base class that represents a query for the 'spy_queue_item' table.
 *
 *
 *
 * @method     ChildSpyQueueItemQuery orderByIdQueueItem($order = Criteria::ASC) Order by the id_queue_item column
 * @method     ChildSpyQueueItemQuery orderByItemEvent($order = Criteria::ASC) Order by the item_event column
 * @method     ChildSpyQueueItemQuery orderByTouched($order = Criteria::ASC) Order by the touched column
 * @method     ChildSpyQueueItemQuery orderByFkItemType($order = Criteria::ASC) Order by the fk_item_type column
 *
 * @method     ChildSpyQueueItemQuery groupByIdQueueItem() Group by the id_queue_item column
 * @method     ChildSpyQueueItemQuery groupByItemEvent() Group by the item_event column
 * @method     ChildSpyQueueItemQuery groupByTouched() Group by the touched column
 * @method     ChildSpyQueueItemQuery groupByFkItemType() Group by the fk_item_type column
 *
 * @method     ChildSpyQueueItemQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSpyQueueItemQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSpyQueueItemQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSpyQueueItemQuery leftJoinSpyQueueItemType($relationAlias = null) Adds a LEFT JOIN clause to the query using the SpyQueueItemType relation
 * @method     ChildSpyQueueItemQuery rightJoinSpyQueueItemType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SpyQueueItemType relation
 * @method     ChildSpyQueueItemQuery innerJoinSpyQueueItemType($relationAlias = null) Adds a INNER JOIN clause to the query using the SpyQueueItemType relation
 *
 * @method     \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItemTypeQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildSpyQueueItem findOne(ConnectionInterface $con = null) Return the first ChildSpyQueueItem matching the query
 * @method     ChildSpyQueueItem findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSpyQueueItem matching the query, or a new ChildSpyQueueItem object populated from the query conditions when no match is found
 *
 * @method     ChildSpyQueueItem findOneByIdQueueItem(int $id_queue_item) Return the first ChildSpyQueueItem filtered by the id_queue_item column
 * @method     ChildSpyQueueItem findOneByItemEvent(int $item_event) Return the first ChildSpyQueueItem filtered by the item_event column
 * @method     ChildSpyQueueItem findOneByTouched(string $touched) Return the first ChildSpyQueueItem filtered by the touched column
 * @method     ChildSpyQueueItem findOneByFkItemType(int $fk_item_type) Return the first ChildSpyQueueItem filtered by the fk_item_type column
 *
 * @method     ChildSpyQueueItem[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSpyQueueItem objects based on current ModelCriteria
 * @method     ChildSpyQueueItem[]|ObjectCollection findByIdQueueItem(int $id_queue_item) Return ChildSpyQueueItem objects filtered by the id_queue_item column
 * @method     ChildSpyQueueItem[]|ObjectCollection findByItemEvent(int $item_event) Return ChildSpyQueueItem objects filtered by the item_event column
 * @method     ChildSpyQueueItem[]|ObjectCollection findByTouched(string $touched) Return ChildSpyQueueItem objects filtered by the touched column
 * @method     ChildSpyQueueItem[]|ObjectCollection findByFkItemType(int $fk_item_type) Return ChildSpyQueueItem objects filtered by the fk_item_type column
 * @method     ChildSpyQueueItem[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SpyQueueItemQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\Base\SpyQueueItemQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'zed', $modelName = '\\SprykerFeature\\Zed\\QueueDistributor\\Persistence\\Propel\\SpyQueueItem', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSpyQueueItemQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSpyQueueItemQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSpyQueueItemQuery) {
            return $criteria;
        }
        $query = new ChildSpyQueueItemQuery();
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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array[$id_queue_item, $fk_item_type] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildSpyQueueItem|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SpyQueueItemTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SpyQueueItemTableMap::DATABASE_NAME);
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
     * @return ChildSpyQueueItem A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id_queue_item, item_event, touched, fk_item_type FROM spy_queue_item WHERE id_queue_item = :p0 AND fk_item_type = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildSpyQueueItem $obj */

            /* @var $locator \Generated\Zed\Ide\AutoCompletion */
            $locator = \SprykerEngine\Zed\Kernel\Locator::getInstance();
            $obj = $locator->queueDistributor()->entitySpyQueueItem();

            $obj->hydrate($row);
            SpyQueueItemTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return ChildSpyQueueItem|array|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
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
     * @return $this|ChildSpyQueueItemQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(SpyQueueItemTableMap::COL_ID_QUEUE_ITEM, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(SpyQueueItemTableMap::COL_FK_ITEM_TYPE, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSpyQueueItemQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(SpyQueueItemTableMap::COL_ID_QUEUE_ITEM, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(SpyQueueItemTableMap::COL_FK_ITEM_TYPE, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the id_queue_item column
     *
     * Example usage:
     * <code>
     * $query->filterByIdQueueItem(1234); // WHERE id_queue_item = 1234
     * $query->filterByIdQueueItem(array(12, 34)); // WHERE id_queue_item IN (12, 34)
     * $query->filterByIdQueueItem(array('min' => 12)); // WHERE id_queue_item > 12
     * </code>
     *
     * @param     mixed $idQueueItem The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSpyQueueItemQuery The current query, for fluid interface
     */
    public function filterByIdQueueItem($idQueueItem = null, $comparison = null)
    {
        if (is_array($idQueueItem)) {
            $useMinMax = false;
            if (isset($idQueueItem['min'])) {
                $this->addUsingAlias(SpyQueueItemTableMap::COL_ID_QUEUE_ITEM, $idQueueItem['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idQueueItem['max'])) {
                $this->addUsingAlias(SpyQueueItemTableMap::COL_ID_QUEUE_ITEM, $idQueueItem['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SpyQueueItemTableMap::COL_ID_QUEUE_ITEM, $idQueueItem, $comparison);
    }

    /**
     * Filter the query on the item_event column
     *
     * @param     mixed $itemEvent The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSpyQueueItemQuery The current query, for fluid interface
     */
    public function filterByItemEvent($itemEvent = null, $comparison = null)
    {
        $valueSet = SpyQueueItemTableMap::getValueSet(SpyQueueItemTableMap::COL_ITEM_EVENT);
        if (is_scalar($itemEvent)) {
            if (!in_array($itemEvent, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $itemEvent));
            }
            $itemEvent = array_search($itemEvent, $valueSet);
        } elseif (is_array($itemEvent)) {
            $convertedValues = array();
            foreach ($itemEvent as $value) {
                if (!in_array($value, $valueSet)) {
                    throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $value));
                }
                $convertedValues []= array_search($value, $valueSet);
            }
            $itemEvent = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SpyQueueItemTableMap::COL_ITEM_EVENT, $itemEvent, $comparison);
    }

    /**
     * Filter the query on the touched column
     *
     * Example usage:
     * <code>
     * $query->filterByTouched('2011-03-14'); // WHERE touched = '2011-03-14'
     * $query->filterByTouched('now'); // WHERE touched = '2011-03-14'
     * $query->filterByTouched(array('max' => 'yesterday')); // WHERE touched > '2011-03-13'
     * </code>
     *
     * @param     mixed $touched The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSpyQueueItemQuery The current query, for fluid interface
     */
    public function filterByTouched($touched = null, $comparison = null)
    {
        if (is_array($touched)) {
            $useMinMax = false;
            if (isset($touched['min'])) {
                $this->addUsingAlias(SpyQueueItemTableMap::COL_TOUCHED, $touched['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($touched['max'])) {
                $this->addUsingAlias(SpyQueueItemTableMap::COL_TOUCHED, $touched['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SpyQueueItemTableMap::COL_TOUCHED, $touched, $comparison);
    }

    /**
     * Filter the query on the fk_item_type column
     *
     * Example usage:
     * <code>
     * $query->filterByFkItemType(1234); // WHERE fk_item_type = 1234
     * $query->filterByFkItemType(array(12, 34)); // WHERE fk_item_type IN (12, 34)
     * $query->filterByFkItemType(array('min' => 12)); // WHERE fk_item_type > 12
     * </code>
     *
     * @see       filterBySpyQueueItemType()
     *
     * @param     mixed $fkItemType The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSpyQueueItemQuery The current query, for fluid interface
     */
    public function filterByFkItemType($fkItemType = null, $comparison = null)
    {
        if (is_array($fkItemType)) {
            $useMinMax = false;
            if (isset($fkItemType['min'])) {
                $this->addUsingAlias(SpyQueueItemTableMap::COL_FK_ITEM_TYPE, $fkItemType['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($fkItemType['max'])) {
                $this->addUsingAlias(SpyQueueItemTableMap::COL_FK_ITEM_TYPE, $fkItemType['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SpyQueueItemTableMap::COL_FK_ITEM_TYPE, $fkItemType, $comparison);
    }

    /**
     * Filter the query by a related \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItemType object
     *
     * @param \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItemType|ObjectCollection $spyQueueItemType The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSpyQueueItemQuery The current query, for fluid interface
     */
    public function filterBySpyQueueItemType($spyQueueItemType, $comparison = null)
    {
        if ($spyQueueItemType instanceof \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItemType) {
            return $this
                ->addUsingAlias(SpyQueueItemTableMap::COL_FK_ITEM_TYPE, $spyQueueItemType->getIdQueueItemType(), $comparison);
        } elseif ($spyQueueItemType instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SpyQueueItemTableMap::COL_FK_ITEM_TYPE, $spyQueueItemType->toKeyValue('PrimaryKey', 'IdQueueItemType'), $comparison);
        } else {
            throw new PropelException('filterBySpyQueueItemType() only accepts arguments of type \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItemType or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SpyQueueItemType relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSpyQueueItemQuery The current query, for fluid interface
     */
    public function joinSpyQueueItemType($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SpyQueueItemType');

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
            $this->addJoinObject($join, 'SpyQueueItemType');
        }

        return $this;
    }

    /**
     * Use the SpyQueueItemType relation SpyQueueItemType object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItemTypeQuery A secondary query class using the current class as primary query
     */
    public function useSpyQueueItemTypeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSpyQueueItemType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SpyQueueItemType', '\SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItemTypeQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSpyQueueItem $spyQueueItem Object to remove from the list of results
     *
     * @return $this|ChildSpyQueueItemQuery The current query, for fluid interface
     */
    public function prune($spyQueueItem = null)
    {
        if ($spyQueueItem) {
            $this->addCond('pruneCond0', $this->getAliasedColName(SpyQueueItemTableMap::COL_ID_QUEUE_ITEM), $spyQueueItem->getIdQueueItem(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(SpyQueueItemTableMap::COL_FK_ITEM_TYPE), $spyQueueItem->getFkItemType(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the spy_queue_item table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SpyQueueItemTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SpyQueueItemTableMap::clearInstancePool();
            SpyQueueItemTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(SpyQueueItemTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SpyQueueItemTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SpyQueueItemTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SpyQueueItemTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // SpyQueueItemQuery
