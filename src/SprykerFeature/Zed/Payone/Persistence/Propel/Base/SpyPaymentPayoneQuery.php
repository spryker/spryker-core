<?php

namespace SprykerFeature\Zed\Payone\Persistence\Propel\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayone as ChildSpyPaymentPayone;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneQuery as ChildSpyPaymentPayoneQuery;
use SprykerFeature\Zed\Payone\Persistence\Propel\Map\SpyPaymentPayoneTableMap;

/**
 * Base class that represents a query for the 'spy_payment_payone' table.
 *
 *
 *
 * @method     ChildSpyPaymentPayoneQuery orderByIdPaymentPayone($order = Criteria::ASC) Order by the id_payment_payone column
 * @method     ChildSpyPaymentPayoneQuery orderByPaymentMethod($order = Criteria::ASC) Order by the payment_method column
 * @method     ChildSpyPaymentPayoneQuery orderByTransactionId($order = Criteria::ASC) Order by the transaction_id column
 * @method     ChildSpyPaymentPayoneQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildSpyPaymentPayoneQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildSpyPaymentPayoneQuery groupByIdPaymentPayone() Group by the id_payment_payone column
 * @method     ChildSpyPaymentPayoneQuery groupByPaymentMethod() Group by the payment_method column
 * @method     ChildSpyPaymentPayoneQuery groupByTransactionId() Group by the transaction_id column
 * @method     ChildSpyPaymentPayoneQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildSpyPaymentPayoneQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildSpyPaymentPayoneQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSpyPaymentPayoneQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSpyPaymentPayoneQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSpyPaymentPayoneQuery leftJoinSpyPaymentPayoneApiLog($relationAlias = null) Adds a LEFT JOIN clause to the query using the SpyPaymentPayoneApiLog relation
 * @method     ChildSpyPaymentPayoneQuery rightJoinSpyPaymentPayoneApiLog($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SpyPaymentPayoneApiLog relation
 * @method     ChildSpyPaymentPayoneQuery innerJoinSpyPaymentPayoneApiLog($relationAlias = null) Adds a INNER JOIN clause to the query using the SpyPaymentPayoneApiLog relation
 *
 * @method     ChildSpyPaymentPayoneQuery leftJoinSpyPaymentPayoneTransactionStatusLog($relationAlias = null) Adds a LEFT JOIN clause to the query using the SpyPaymentPayoneTransactionStatusLog relation
 * @method     ChildSpyPaymentPayoneQuery rightJoinSpyPaymentPayoneTransactionStatusLog($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SpyPaymentPayoneTransactionStatusLog relation
 * @method     ChildSpyPaymentPayoneQuery innerJoinSpyPaymentPayoneTransactionStatusLog($relationAlias = null) Adds a INNER JOIN clause to the query using the SpyPaymentPayoneTransactionStatusLog relation
 *
 * @method     \SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneApiLogQuery|\SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLogQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildSpyPaymentPayone findOne(ConnectionInterface $con = null) Return the first ChildSpyPaymentPayone matching the query
 * @method     ChildSpyPaymentPayone findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSpyPaymentPayone matching the query, or a new ChildSpyPaymentPayone object populated from the query conditions when no match is found
 *
 * @method     ChildSpyPaymentPayone findOneByIdPaymentPayone(int $id_payment_payone) Return the first ChildSpyPaymentPayone filtered by the id_payment_payone column
 * @method     ChildSpyPaymentPayone findOneByPaymentMethod(string $payment_method) Return the first ChildSpyPaymentPayone filtered by the payment_method column
 * @method     ChildSpyPaymentPayone findOneByTransactionId(int $transaction_id) Return the first ChildSpyPaymentPayone filtered by the transaction_id column
 * @method     ChildSpyPaymentPayone findOneByCreatedAt(string $created_at) Return the first ChildSpyPaymentPayone filtered by the created_at column
 * @method     ChildSpyPaymentPayone findOneByUpdatedAt(string $updated_at) Return the first ChildSpyPaymentPayone filtered by the updated_at column
 *
 * @method     ChildSpyPaymentPayone[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSpyPaymentPayone objects based on current ModelCriteria
 * @method     ChildSpyPaymentPayone[]|ObjectCollection findByIdPaymentPayone(int $id_payment_payone) Return ChildSpyPaymentPayone objects filtered by the id_payment_payone column
 * @method     ChildSpyPaymentPayone[]|ObjectCollection findByPaymentMethod(string $payment_method) Return ChildSpyPaymentPayone objects filtered by the payment_method column
 * @method     ChildSpyPaymentPayone[]|ObjectCollection findByTransactionId(int $transaction_id) Return ChildSpyPaymentPayone objects filtered by the transaction_id column
 * @method     ChildSpyPaymentPayone[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildSpyPaymentPayone objects filtered by the created_at column
 * @method     ChildSpyPaymentPayone[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildSpyPaymentPayone objects filtered by the updated_at column
 * @method     ChildSpyPaymentPayone[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SpyPaymentPayoneQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \SprykerFeature\Zed\Payone\Persistence\Propel\Base\SpyPaymentPayoneQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'zed', $modelName = '\\SprykerFeature\\Zed\\Payone\\Persistence\\Propel\\SpyPaymentPayone', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSpyPaymentPayoneQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSpyPaymentPayoneQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSpyPaymentPayoneQuery) {
            return $criteria;
        }
        $query = new ChildSpyPaymentPayoneQuery();
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
     * @return ChildSpyPaymentPayone|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SpyPaymentPayoneTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SpyPaymentPayoneTableMap::DATABASE_NAME);
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
     * @return ChildSpyPaymentPayone A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id_payment_payone`, `payment_method`, `transaction_id`, `created_at`, `updated_at` FROM `spy_payment_payone` WHERE `id_payment_payone` = :p0';
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
            /** @var ChildSpyPaymentPayone $obj */

            /* @var $locator \Generated\Zed\Ide\AutoCompletion */
            $locator = \SprykerEngine\Zed\Kernel\Locator::getInstance();
            $obj = $locator->payone()->entitySpyPaymentPayone();

            $obj->hydrate($row);
            SpyPaymentPayoneTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildSpyPaymentPayone|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildSpyPaymentPayoneQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SpyPaymentPayoneTableMap::COL_ID_PAYMENT_PAYONE, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSpyPaymentPayoneQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SpyPaymentPayoneTableMap::COL_ID_PAYMENT_PAYONE, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id_payment_payone column
     *
     * Example usage:
     * <code>
     * $query->filterByIdPaymentPayone(1234); // WHERE id_payment_payone = 1234
     * $query->filterByIdPaymentPayone(array(12, 34)); // WHERE id_payment_payone IN (12, 34)
     * $query->filterByIdPaymentPayone(array('min' => 12)); // WHERE id_payment_payone > 12
     * </code>
     *
     * @param     mixed $idPaymentPayone The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSpyPaymentPayoneQuery The current query, for fluid interface
     */
    public function filterByIdPaymentPayone($idPaymentPayone = null, $comparison = null)
    {
        if (is_array($idPaymentPayone)) {
            $useMinMax = false;
            if (isset($idPaymentPayone['min'])) {
                $this->addUsingAlias(SpyPaymentPayoneTableMap::COL_ID_PAYMENT_PAYONE, $idPaymentPayone['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idPaymentPayone['max'])) {
                $this->addUsingAlias(SpyPaymentPayoneTableMap::COL_ID_PAYMENT_PAYONE, $idPaymentPayone['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SpyPaymentPayoneTableMap::COL_ID_PAYMENT_PAYONE, $idPaymentPayone, $comparison);
    }

    /**
     * Filter the query on the payment_method column
     *
     * Example usage:
     * <code>
     * $query->filterByPaymentMethod('fooValue');   // WHERE payment_method = 'fooValue'
     * $query->filterByPaymentMethod('%fooValue%'); // WHERE payment_method LIKE '%fooValue%'
     * </code>
     *
     * @param     string $paymentMethod The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSpyPaymentPayoneQuery The current query, for fluid interface
     */
    public function filterByPaymentMethod($paymentMethod = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($paymentMethod)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $paymentMethod)) {
                $paymentMethod = str_replace('*', '%', $paymentMethod);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SpyPaymentPayoneTableMap::COL_PAYMENT_METHOD, $paymentMethod, $comparison);
    }

    /**
     * Filter the query on the transaction_id column
     *
     * Example usage:
     * <code>
     * $query->filterByTransactionId(1234); // WHERE transaction_id = 1234
     * $query->filterByTransactionId(array(12, 34)); // WHERE transaction_id IN (12, 34)
     * $query->filterByTransactionId(array('min' => 12)); // WHERE transaction_id > 12
     * </code>
     *
     * @param     mixed $transactionId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSpyPaymentPayoneQuery The current query, for fluid interface
     */
    public function filterByTransactionId($transactionId = null, $comparison = null)
    {
        if (is_array($transactionId)) {
            $useMinMax = false;
            if (isset($transactionId['min'])) {
                $this->addUsingAlias(SpyPaymentPayoneTableMap::COL_TRANSACTION_ID, $transactionId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($transactionId['max'])) {
                $this->addUsingAlias(SpyPaymentPayoneTableMap::COL_TRANSACTION_ID, $transactionId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SpyPaymentPayoneTableMap::COL_TRANSACTION_ID, $transactionId, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSpyPaymentPayoneQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(SpyPaymentPayoneTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(SpyPaymentPayoneTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SpyPaymentPayoneTableMap::COL_CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSpyPaymentPayoneQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(SpyPaymentPayoneTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(SpyPaymentPayoneTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SpyPaymentPayoneTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneApiLog object
     *
     * @param \SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneApiLog|ObjectCollection $spyPaymentPayoneApiLog  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSpyPaymentPayoneQuery The current query, for fluid interface
     */
    public function filterBySpyPaymentPayoneApiLog($spyPaymentPayoneApiLog, $comparison = null)
    {
        if ($spyPaymentPayoneApiLog instanceof \SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneApiLog) {
            return $this
                ->addUsingAlias(SpyPaymentPayoneTableMap::COL_ID_PAYMENT_PAYONE, $spyPaymentPayoneApiLog->getFkPaymentPayone(), $comparison);
        } elseif ($spyPaymentPayoneApiLog instanceof ObjectCollection) {
            return $this
                ->useSpyPaymentPayoneApiLogQuery()
                ->filterByPrimaryKeys($spyPaymentPayoneApiLog->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySpyPaymentPayoneApiLog() only accepts arguments of type \SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneApiLog or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SpyPaymentPayoneApiLog relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSpyPaymentPayoneQuery The current query, for fluid interface
     */
    public function joinSpyPaymentPayoneApiLog($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SpyPaymentPayoneApiLog');

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
            $this->addJoinObject($join, 'SpyPaymentPayoneApiLog');
        }

        return $this;
    }

    /**
     * Use the SpyPaymentPayoneApiLog relation SpyPaymentPayoneApiLog object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneApiLogQuery A secondary query class using the current class as primary query
     */
    public function useSpyPaymentPayoneApiLogQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSpyPaymentPayoneApiLog($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SpyPaymentPayoneApiLog', '\SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneApiLogQuery');
    }

    /**
     * Filter the query by a related \SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLog object
     *
     * @param \SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLog|ObjectCollection $spyPaymentPayoneTransactionStatusLog  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSpyPaymentPayoneQuery The current query, for fluid interface
     */
    public function filterBySpyPaymentPayoneTransactionStatusLog($spyPaymentPayoneTransactionStatusLog, $comparison = null)
    {
        if ($spyPaymentPayoneTransactionStatusLog instanceof \SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLog) {
            return $this
                ->addUsingAlias(SpyPaymentPayoneTableMap::COL_ID_PAYMENT_PAYONE, $spyPaymentPayoneTransactionStatusLog->getFkPaymentPayone(), $comparison);
        } elseif ($spyPaymentPayoneTransactionStatusLog instanceof ObjectCollection) {
            return $this
                ->useSpyPaymentPayoneTransactionStatusLogQuery()
                ->filterByPrimaryKeys($spyPaymentPayoneTransactionStatusLog->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySpyPaymentPayoneTransactionStatusLog() only accepts arguments of type \SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLog or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SpyPaymentPayoneTransactionStatusLog relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSpyPaymentPayoneQuery The current query, for fluid interface
     */
    public function joinSpyPaymentPayoneTransactionStatusLog($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SpyPaymentPayoneTransactionStatusLog');

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
            $this->addJoinObject($join, 'SpyPaymentPayoneTransactionStatusLog');
        }

        return $this;
    }

    /**
     * Use the SpyPaymentPayoneTransactionStatusLog relation SpyPaymentPayoneTransactionStatusLog object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLogQuery A secondary query class using the current class as primary query
     */
    public function useSpyPaymentPayoneTransactionStatusLogQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSpyPaymentPayoneTransactionStatusLog($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SpyPaymentPayoneTransactionStatusLog', '\SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLogQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSpyPaymentPayone $spyPaymentPayone Object to remove from the list of results
     *
     * @return $this|ChildSpyPaymentPayoneQuery The current query, for fluid interface
     */
    public function prune($spyPaymentPayone = null)
    {
        if ($spyPaymentPayone) {
            $this->addUsingAlias(SpyPaymentPayoneTableMap::COL_ID_PAYMENT_PAYONE, $spyPaymentPayone->getIdPaymentPayone(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the spy_payment_payone table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SpyPaymentPayoneTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SpyPaymentPayoneTableMap::clearInstancePool();
            SpyPaymentPayoneTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(SpyPaymentPayoneTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SpyPaymentPayoneTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SpyPaymentPayoneTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SpyPaymentPayoneTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildSpyPaymentPayoneQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(SpyPaymentPayoneTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildSpyPaymentPayoneQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(SpyPaymentPayoneTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildSpyPaymentPayoneQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(SpyPaymentPayoneTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildSpyPaymentPayoneQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(SpyPaymentPayoneTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildSpyPaymentPayoneQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(SpyPaymentPayoneTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildSpyPaymentPayoneQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(SpyPaymentPayoneTableMap::COL_CREATED_AT);
    }

} // SpyPaymentPayoneQuery
