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
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueTouch as ChildSpyQueueTouch;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueTouchQuery as ChildSpyQueueTouchQuery;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\Map\SpyQueueTouchTableMap;

/**
 * Base class that represents a query for the 'spy_queue_touch' table.
 *
 *
 *
 * @method     ChildSpyQueueTouchQuery orderByIdQueueTouch($order = Criteria::ASC) Order by the id_queue_touch column
 * @method     ChildSpyQueueTouchQuery orderByItemEvent($order = Criteria::ASC) Order by the item_event column
 * @method     ChildSpyQueueTouchQuery orderByTouched($order = Criteria::ASC) Order by the touched column
 * @method     ChildSpyQueueTouchQuery orderByFkQueueType($order = Criteria::ASC) Order by the fk_queue_type column
 *
 * @method     ChildSpyQueueTouchQuery groupByIdQueueTouch() Group by the id_queue_touch column
 * @method     ChildSpyQueueTouchQuery groupByItemEvent() Group by the item_event column
 * @method     ChildSpyQueueTouchQuery groupByTouched() Group by the touched column
 * @method     ChildSpyQueueTouchQuery groupByFkQueueType() Group by the fk_queue_type column
 *
 * @method     ChildSpyQueueTouchQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSpyQueueTouchQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSpyQueueTouchQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSpyQueueTouchQuery leftJoinSpyQueueType($relationAlias = null) Adds a LEFT JOIN clause to the query using the SpyQueueType relation
 * @method     ChildSpyQueueTouchQuery rightJoinSpyQueueType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SpyQueueType relation
 * @method     ChildSpyQueueTouchQuery innerJoinSpyQueueType($relationAlias = null) Adds a INNER JOIN clause to the query using the SpyQueueType relation
 *
 * @method     \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueTypeQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildSpyQueueTouch findOne(ConnectionInterface $con = null) Return the first ChildSpyQueueTouch matching the query
 * @method     ChildSpyQueueTouch findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSpyQueueTouch matching the query, or a new ChildSpyQueueTouch object populated from the query conditions when no match is found
 *
 * @method     ChildSpyQueueTouch findOneByIdQueueTouch(int $id_queue_touch) Return the first ChildSpyQueueTouch filtered by the id_queue_touch column
 * @method     ChildSpyQueueTouch findOneByItemEvent(int $item_event) Return the first ChildSpyQueueTouch filtered by the item_event column
 * @method     ChildSpyQueueTouch findOneByTouched(string $touched) Return the first ChildSpyQueueTouch filtered by the touched column
 * @method     ChildSpyQueueTouch findOneByFkQueueType(int $fk_queue_type) Return the first ChildSpyQueueTouch filtered by the fk_queue_type column
 *
 * @method     ChildSpyQueueTouch[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSpyQueueTouch objects based on current ModelCriteria
 * @method     ChildSpyQueueTouch[]|ObjectCollection findByIdQueueTouch(int $id_queue_touch) Return ChildSpyQueueTouch objects filtered by the id_queue_touch column
 * @method     ChildSpyQueueTouch[]|ObjectCollection findByItemEvent(int $item_event) Return ChildSpyQueueTouch objects filtered by the item_event column
 * @method     ChildSpyQueueTouch[]|ObjectCollection findByTouched(string $touched) Return ChildSpyQueueTouch objects filtered by the touched column
 * @method     ChildSpyQueueTouch[]|ObjectCollection findByFkQueueType(int $fk_queue_type) Return ChildSpyQueueTouch objects filtered by the fk_queue_type column
 * @method     ChildSpyQueueTouch[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SpyQueueTouchQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\Base\SpyQueueTouchQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'zed', $modelName = '\\SprykerFeature\\Zed\\QueueDistributor\\Persistence\\Propel\\SpyQueueTouch', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSpyQueueTouchQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSpyQueueTouchQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSpyQueueTouchQuery) {
            return $criteria;
        }
        $query = new ChildSpyQueueTouchQuery();
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
     * @param array[$id_queue_touch, $fk_queue_type] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildSpyQueueTouch|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SpyQueueTouchTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SpyQueueTouchTableMap::DATABASE_NAME);
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
     * @return ChildSpyQueueTouch A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id_queue_touch, item_event, touched, fk_queue_type FROM spy_queue_touch WHERE id_queue_touch = :p0 AND fk_queue_type = :p1';
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
            /** @var ChildSpyQueueTouch $obj */

            /* @var $locator \Generated\Zed\Ide\AutoCompletion */
            $locator = \SprykerEngine\Zed\Kernel\Locator::getInstance();
            $obj = $locator->queueDistributor()->entitySpyQueueTouch();

            $obj->hydrate($row);
            SpyQueueTouchTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return ChildSpyQueueTouch|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildSpyQueueTouchQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(SpyQueueTouchTableMap::COL_ID_QUEUE_TOUCH, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(SpyQueueTouchTableMap::COL_FK_QUEUE_TYPE, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSpyQueueTouchQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(SpyQueueTouchTableMap::COL_ID_QUEUE_TOUCH, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(SpyQueueTouchTableMap::COL_FK_QUEUE_TYPE, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the id_queue_touch column
     *
     * Example usage:
     * <code>
     * $query->filterByIdQueueTouch(1234); // WHERE id_queue_touch = 1234
     * $query->filterByIdQueueTouch(array(12, 34)); // WHERE id_queue_touch IN (12, 34)
     * $query->filterByIdQueueTouch(array('min' => 12)); // WHERE id_queue_touch > 12
     * </code>
     *
     * @param     mixed $idQueueTouch The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSpyQueueTouchQuery The current query, for fluid interface
     */
    public function filterByIdQueueTouch($idQueueTouch = null, $comparison = null)
    {
        if (is_array($idQueueTouch)) {
            $useMinMax = false;
            if (isset($idQueueTouch['min'])) {
                $this->addUsingAlias(SpyQueueTouchTableMap::COL_ID_QUEUE_TOUCH, $idQueueTouch['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idQueueTouch['max'])) {
                $this->addUsingAlias(SpyQueueTouchTableMap::COL_ID_QUEUE_TOUCH, $idQueueTouch['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SpyQueueTouchTableMap::COL_ID_QUEUE_TOUCH, $idQueueTouch, $comparison);
    }

    /**
     * Filter the query on the item_event column
     *
     * @param     mixed $itemEvent The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSpyQueueTouchQuery The current query, for fluid interface
     */
    public function filterByItemEvent($itemEvent = null, $comparison = null)
    {
        $valueSet = SpyQueueTouchTableMap::getValueSet(SpyQueueTouchTableMap::COL_ITEM_EVENT);
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

        return $this->addUsingAlias(SpyQueueTouchTableMap::COL_ITEM_EVENT, $itemEvent, $comparison);
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
     * @return $this|ChildSpyQueueTouchQuery The current query, for fluid interface
     */
    public function filterByTouched($touched = null, $comparison = null)
    {
        if (is_array($touched)) {
            $useMinMax = false;
            if (isset($touched['min'])) {
                $this->addUsingAlias(SpyQueueTouchTableMap::COL_TOUCHED, $touched['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($touched['max'])) {
                $this->addUsingAlias(SpyQueueTouchTableMap::COL_TOUCHED, $touched['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SpyQueueTouchTableMap::COL_TOUCHED, $touched, $comparison);
    }

    /**
     * Filter the query on the fk_queue_type column
     *
     * Example usage:
     * <code>
     * $query->filterByFkQueueType(1234); // WHERE fk_queue_type = 1234
     * $query->filterByFkQueueType(array(12, 34)); // WHERE fk_queue_type IN (12, 34)
     * $query->filterByFkQueueType(array('min' => 12)); // WHERE fk_queue_type > 12
     * </code>
     *
     * @see       filterBySpyQueueType()
     *
     * @param     mixed $fkQueueType The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSpyQueueTouchQuery The current query, for fluid interface
     */
    public function filterByFkQueueType($fkQueueType = null, $comparison = null)
    {
        if (is_array($fkQueueType)) {
            $useMinMax = false;
            if (isset($fkQueueType['min'])) {
                $this->addUsingAlias(SpyQueueTouchTableMap::COL_FK_QUEUE_TYPE, $fkQueueType['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($fkQueueType['max'])) {
                $this->addUsingAlias(SpyQueueTouchTableMap::COL_FK_QUEUE_TYPE, $fkQueueType['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SpyQueueTouchTableMap::COL_FK_QUEUE_TYPE, $fkQueueType, $comparison);
    }

    /**
     * Filter the query by a related \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueType object
     *
     * @param \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueType|ObjectCollection $spyQueueType The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSpyQueueTouchQuery The current query, for fluid interface
     */
    public function filterBySpyQueueType($spyQueueType, $comparison = null)
    {
        if ($spyQueueType instanceof \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueType) {
            return $this
                ->addUsingAlias(SpyQueueTouchTableMap::COL_FK_QUEUE_TYPE, $spyQueueType->getIdQueueType(), $comparison);
        } elseif ($spyQueueType instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SpyQueueTouchTableMap::COL_FK_QUEUE_TYPE, $spyQueueType->toKeyValue('PrimaryKey', 'IdQueueType'), $comparison);
        } else {
            throw new PropelException('filterBySpyQueueType() only accepts arguments of type \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueType or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SpyQueueType relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSpyQueueTouchQuery The current query, for fluid interface
     */
    public function joinSpyQueueType($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SpyQueueType');

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
            $this->addJoinObject($join, 'SpyQueueType');
        }

        return $this;
    }

    /**
     * Use the SpyQueueType relation SpyQueueType object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueTypeQuery A secondary query class using the current class as primary query
     */
    public function useSpyQueueTypeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSpyQueueType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SpyQueueType', '\SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueTypeQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSpyQueueTouch $spyQueueTouch Object to remove from the list of results
     *
     * @return $this|ChildSpyQueueTouchQuery The current query, for fluid interface
     */
    public function prune($spyQueueTouch = null)
    {
        if ($spyQueueTouch) {
            $this->addCond('pruneCond0', $this->getAliasedColName(SpyQueueTouchTableMap::COL_ID_QUEUE_TOUCH), $spyQueueTouch->getIdQueueTouch(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(SpyQueueTouchTableMap::COL_FK_QUEUE_TYPE), $spyQueueTouch->getFkQueueType(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the spy_queue_touch table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SpyQueueTouchTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SpyQueueTouchTableMap::clearInstancePool();
            SpyQueueTouchTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(SpyQueueTouchTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SpyQueueTouchTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SpyQueueTouchTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SpyQueueTouchTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // SpyQueueTouchQuery
