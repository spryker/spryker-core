<?php

namespace SprykerFeature\Zed\QueueDistributor\Persistence\Propel\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueReceiver as ChildSpyQueueReceiver;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueReceiverQuery as ChildSpyQueueReceiverQuery;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\Map\SpyQueueReceiverTableMap;

/**
 * Base class that represents a query for the 'spy_queue_receiver' table.
 *
 *
 *
 * @method     ChildSpyQueueReceiverQuery orderByIdQueueReceiver($order = Criteria::ASC) Order by the id_queue_receiver column
 * @method     ChildSpyQueueReceiverQuery orderByKey($order = Criteria::ASC) Order by the key column
 *
 * @method     ChildSpyQueueReceiverQuery groupByIdQueueReceiver() Group by the id_queue_receiver column
 * @method     ChildSpyQueueReceiverQuery groupByKey() Group by the key column
 *
 * @method     ChildSpyQueueReceiverQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSpyQueueReceiverQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSpyQueueReceiverQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSpyQueueReceiver findOne(ConnectionInterface $con = null) Return the first ChildSpyQueueReceiver matching the query
 * @method     ChildSpyQueueReceiver findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSpyQueueReceiver matching the query, or a new ChildSpyQueueReceiver object populated from the query conditions when no match is found
 *
 * @method     ChildSpyQueueReceiver findOneByIdQueueReceiver(int $id_queue_receiver) Return the first ChildSpyQueueReceiver filtered by the id_queue_receiver column
 * @method     ChildSpyQueueReceiver findOneByKey(string $key) Return the first ChildSpyQueueReceiver filtered by the key column
 *
 * @method     ChildSpyQueueReceiver[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSpyQueueReceiver objects based on current ModelCriteria
 * @method     ChildSpyQueueReceiver[]|ObjectCollection findByIdQueueReceiver(int $id_queue_receiver) Return ChildSpyQueueReceiver objects filtered by the id_queue_receiver column
 * @method     ChildSpyQueueReceiver[]|ObjectCollection findByKey(string $key) Return ChildSpyQueueReceiver objects filtered by the key column
 * @method     ChildSpyQueueReceiver[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SpyQueueReceiverQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\Base\SpyQueueReceiverQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'zed', $modelName = '\\SprykerFeature\\Zed\\QueueDistributor\\Persistence\\Propel\\SpyQueueReceiver', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSpyQueueReceiverQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSpyQueueReceiverQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSpyQueueReceiverQuery) {
            return $criteria;
        }
        $query = new ChildSpyQueueReceiverQuery();
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
     * @return ChildSpyQueueReceiver|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SpyQueueReceiverTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SpyQueueReceiverTableMap::DATABASE_NAME);
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
     * @return ChildSpyQueueReceiver A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id_queue_receiver, key FROM spy_queue_receiver WHERE id_queue_receiver = :p0';
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
            /** @var ChildSpyQueueReceiver $obj */

            /* @var $locator \Generated\Zed\Ide\AutoCompletion */
            $locator = \SprykerEngine\Zed\Kernel\Locator::getInstance();
            $obj = $locator->queueDistributor()->entitySpyQueueReceiver();

            $obj->hydrate($row);
            SpyQueueReceiverTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildSpyQueueReceiver|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildSpyQueueReceiverQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SpyQueueReceiverTableMap::COL_ID_QUEUE_RECEIVER, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSpyQueueReceiverQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SpyQueueReceiverTableMap::COL_ID_QUEUE_RECEIVER, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id_queue_receiver column
     *
     * Example usage:
     * <code>
     * $query->filterByIdQueueReceiver(1234); // WHERE id_queue_receiver = 1234
     * $query->filterByIdQueueReceiver(array(12, 34)); // WHERE id_queue_receiver IN (12, 34)
     * $query->filterByIdQueueReceiver(array('min' => 12)); // WHERE id_queue_receiver > 12
     * </code>
     *
     * @param     mixed $idQueueReceiver The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSpyQueueReceiverQuery The current query, for fluid interface
     */
    public function filterByIdQueueReceiver($idQueueReceiver = null, $comparison = null)
    {
        if (is_array($idQueueReceiver)) {
            $useMinMax = false;
            if (isset($idQueueReceiver['min'])) {
                $this->addUsingAlias(SpyQueueReceiverTableMap::COL_ID_QUEUE_RECEIVER, $idQueueReceiver['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idQueueReceiver['max'])) {
                $this->addUsingAlias(SpyQueueReceiverTableMap::COL_ID_QUEUE_RECEIVER, $idQueueReceiver['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SpyQueueReceiverTableMap::COL_ID_QUEUE_RECEIVER, $idQueueReceiver, $comparison);
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
     * @return $this|ChildSpyQueueReceiverQuery The current query, for fluid interface
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

        return $this->addUsingAlias(SpyQueueReceiverTableMap::COL_KEY, $key, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSpyQueueReceiver $spyQueueReceiver Object to remove from the list of results
     *
     * @return $this|ChildSpyQueueReceiverQuery The current query, for fluid interface
     */
    public function prune($spyQueueReceiver = null)
    {
        if ($spyQueueReceiver) {
            $this->addUsingAlias(SpyQueueReceiverTableMap::COL_ID_QUEUE_RECEIVER, $spyQueueReceiver->getIdQueueReceiver(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the spy_queue_receiver table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SpyQueueReceiverTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SpyQueueReceiverTableMap::clearInstancePool();
            SpyQueueReceiverTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(SpyQueueReceiverTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SpyQueueReceiverTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SpyQueueReceiverTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SpyQueueReceiverTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // SpyQueueReceiverQuery
