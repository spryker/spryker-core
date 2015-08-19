<?php

namespace SprykerFeature\Zed\FrontendExporter\Persistence\Propel\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\FrontendExporter\Persistence\Propel\SpyFrontendExporterTouch as ChildSpyFrontendExporterTouch;
use SprykerFeature\Zed\FrontendExporter\Persistence\Propel\SpyFrontendExporterTouchQuery as ChildSpyFrontendExporterTouchQuery;
use SprykerFeature\Zed\FrontendExporter\Persistence\Propel\Map\SpyFrontendExporterTouchTableMap;

/**
 * Base class that represents a query for the 'spy_frontend_exporter_touch' table.
 *
 *
 *
 * @method     ChildSpyFrontendExporterTouchQuery orderByIdFrontendExporterTouch($order = Criteria::ASC) Order by the id_frontend_exporter_touch column
 * @method     ChildSpyFrontendExporterTouchQuery orderByItemType($order = Criteria::ASC) Order by the item_type column
 * @method     ChildSpyFrontendExporterTouchQuery orderByItemEvent($order = Criteria::ASC) Order by the item_event column
 * @method     ChildSpyFrontendExporterTouchQuery orderByExportType($order = Criteria::ASC) Order by the export_type column
 * @method     ChildSpyFrontendExporterTouchQuery orderByItemId($order = Criteria::ASC) Order by the item_id column
 * @method     ChildSpyFrontendExporterTouchQuery orderByTouched($order = Criteria::ASC) Order by the touched column
 *
 * @method     ChildSpyFrontendExporterTouchQuery groupByIdFrontendExporterTouch() Group by the id_frontend_exporter_touch column
 * @method     ChildSpyFrontendExporterTouchQuery groupByItemType() Group by the item_type column
 * @method     ChildSpyFrontendExporterTouchQuery groupByItemEvent() Group by the item_event column
 * @method     ChildSpyFrontendExporterTouchQuery groupByExportType() Group by the export_type column
 * @method     ChildSpyFrontendExporterTouchQuery groupByItemId() Group by the item_id column
 * @method     ChildSpyFrontendExporterTouchQuery groupByTouched() Group by the touched column
 *
 * @method     ChildSpyFrontendExporterTouchQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSpyFrontendExporterTouchQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSpyFrontendExporterTouchQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSpyFrontendExporterTouchQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildSpyFrontendExporterTouchQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildSpyFrontendExporterTouchQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildSpyFrontendExporterTouch findOne(ConnectionInterface $con = null) Return the first ChildSpyFrontendExporterTouch matching the query
 * @method     ChildSpyFrontendExporterTouch findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSpyFrontendExporterTouch matching the query, or a new ChildSpyFrontendExporterTouch object populated from the query conditions when no match is found
 *
 * @method     ChildSpyFrontendExporterTouch findOneByIdFrontendExporterTouch(int $id_frontend_exporter_touch) Return the first ChildSpyFrontendExporterTouch filtered by the id_frontend_exporter_touch column
 * @method     ChildSpyFrontendExporterTouch findOneByItemType(string $item_type) Return the first ChildSpyFrontendExporterTouch filtered by the item_type column
 * @method     ChildSpyFrontendExporterTouch findOneByItemEvent(int $item_event) Return the first ChildSpyFrontendExporterTouch filtered by the item_event column
 * @method     ChildSpyFrontendExporterTouch findOneByExportType(int $export_type) Return the first ChildSpyFrontendExporterTouch filtered by the export_type column
 * @method     ChildSpyFrontendExporterTouch findOneByItemId(string $item_id) Return the first ChildSpyFrontendExporterTouch filtered by the item_id column
 * @method     ChildSpyFrontendExporterTouch findOneByTouched(string $touched) Return the first ChildSpyFrontendExporterTouch filtered by the touched column *

 * @method     ChildSpyFrontendExporterTouch requirePk($key, ConnectionInterface $con = null) Return the ChildSpyFrontendExporterTouch by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSpyFrontendExporterTouch requireOne(ConnectionInterface $con = null) Return the first ChildSpyFrontendExporterTouch matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSpyFrontendExporterTouch requireOneByIdFrontendExporterTouch(int $id_frontend_exporter_touch) Return the first ChildSpyFrontendExporterTouch filtered by the id_frontend_exporter_touch column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSpyFrontendExporterTouch requireOneByItemType(string $item_type) Return the first ChildSpyFrontendExporterTouch filtered by the item_type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSpyFrontendExporterTouch requireOneByItemEvent(int $item_event) Return the first ChildSpyFrontendExporterTouch filtered by the item_event column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSpyFrontendExporterTouch requireOneByExportType(int $export_type) Return the first ChildSpyFrontendExporterTouch filtered by the export_type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSpyFrontendExporterTouch requireOneByItemId(string $item_id) Return the first ChildSpyFrontendExporterTouch filtered by the item_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSpyFrontendExporterTouch requireOneByTouched(string $touched) Return the first ChildSpyFrontendExporterTouch filtered by the touched column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSpyFrontendExporterTouch[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSpyFrontendExporterTouch objects based on current ModelCriteria
 * @method     ChildSpyFrontendExporterTouch[]|ObjectCollection findByIdFrontendExporterTouch(int $id_frontend_exporter_touch) Return ChildSpyFrontendExporterTouch objects filtered by the id_frontend_exporter_touch column
 * @method     ChildSpyFrontendExporterTouch[]|ObjectCollection findByItemType(string $item_type) Return ChildSpyFrontendExporterTouch objects filtered by the item_type column
 * @method     ChildSpyFrontendExporterTouch[]|ObjectCollection findByItemEvent(int $item_event) Return ChildSpyFrontendExporterTouch objects filtered by the item_event column
 * @method     ChildSpyFrontendExporterTouch[]|ObjectCollection findByExportType(int $export_type) Return ChildSpyFrontendExporterTouch objects filtered by the export_type column
 * @method     ChildSpyFrontendExporterTouch[]|ObjectCollection findByItemId(string $item_id) Return ChildSpyFrontendExporterTouch objects filtered by the item_id column
 * @method     ChildSpyFrontendExporterTouch[]|ObjectCollection findByTouched(string $touched) Return ChildSpyFrontendExporterTouch objects filtered by the touched column
 * @method     ChildSpyFrontendExporterTouch[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SpyFrontendExporterTouchQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \SprykerFeature\Zed\FrontendExporter\Persistence\Propel\Base\SpyFrontendExporterTouchQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'zed', $modelName = '\\SprykerFeature\\Zed\\FrontendExporter\\Persistence\\Propel\\SpyFrontendExporterTouch', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSpyFrontendExporterTouchQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSpyFrontendExporterTouchQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSpyFrontendExporterTouchQuery) {
            return $criteria;
        }
        $query = new ChildSpyFrontendExporterTouchQuery();
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
     * @return ChildSpyFrontendExporterTouch|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SpyFrontendExporterTouchTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SpyFrontendExporterTouchTableMap::DATABASE_NAME);
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
     * @return ChildSpyFrontendExporterTouch A model object, or null if the key is not found
     * @throws \Propel\Runtime\Exception\PropelException
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id_frontend_exporter_touch, item_type, item_event, export_type, item_id, touched FROM spy_frontend_exporter_touch WHERE id_frontend_exporter_touch = :p0';
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
            /** @var ChildSpyFrontendExporterTouch $obj */

            /** @var \Generated\Zed\Ide\AutoCompletion $locator */
            $locator = \SprykerEngine\Zed\Kernel\Locator::getInstance();
            $obj = $locator->frontendExporter()->entitySpyFrontendExporterTouch();

            $obj->hydrate($row);
            SpyFrontendExporterTouchTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildSpyFrontendExporterTouch|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildSpyFrontendExporterTouchQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SpyFrontendExporterTouchTableMap::COL_ID_FRONTEND_EXPORTER_TOUCH, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSpyFrontendExporterTouchQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SpyFrontendExporterTouchTableMap::COL_ID_FRONTEND_EXPORTER_TOUCH, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id_frontend_exporter_touch column
     *
     * Example usage:
     * <code>
     * $query->filterByIdFrontendExporterTouch(1234); // WHERE id_frontend_exporter_touch = 1234
     * $query->filterByIdFrontendExporterTouch(array(12, 34)); // WHERE id_frontend_exporter_touch IN (12, 34)
     * $query->filterByIdFrontendExporterTouch(array('min' => 12)); // WHERE id_frontend_exporter_touch > 12
     * </code>
     *
     * @param     mixed $idFrontendExporterTouch The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSpyFrontendExporterTouchQuery The current query, for fluid interface
     */
    public function filterByIdFrontendExporterTouch($idFrontendExporterTouch = null, $comparison = null)
    {
        if (is_array($idFrontendExporterTouch)) {
            $useMinMax = false;
            if (isset($idFrontendExporterTouch['min'])) {
                $this->addUsingAlias(SpyFrontendExporterTouchTableMap::COL_ID_FRONTEND_EXPORTER_TOUCH, $idFrontendExporterTouch['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idFrontendExporterTouch['max'])) {
                $this->addUsingAlias(SpyFrontendExporterTouchTableMap::COL_ID_FRONTEND_EXPORTER_TOUCH, $idFrontendExporterTouch['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SpyFrontendExporterTouchTableMap::COL_ID_FRONTEND_EXPORTER_TOUCH, $idFrontendExporterTouch, $comparison);
    }

    /**
     * Filter the query on the item_type column
     *
     * Example usage:
     * <code>
     * $query->filterByItemType('fooValue');   // WHERE item_type = 'fooValue'
     * $query->filterByItemType('%fooValue%'); // WHERE item_type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $itemType The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSpyFrontendExporterTouchQuery The current query, for fluid interface
     */
    public function filterByItemType($itemType = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($itemType)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $itemType)) {
                $itemType = str_replace('*', '%', $itemType);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SpyFrontendExporterTouchTableMap::COL_ITEM_TYPE, $itemType, $comparison);
    }

    /**
     * Filter the query on the item_event column
     *
     * @param     mixed $itemEvent The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSpyFrontendExporterTouchQuery The current query, for fluid interface
     */
    public function filterByItemEvent($itemEvent = null, $comparison = null)
    {
        $valueSet = SpyFrontendExporterTouchTableMap::getValueSet(SpyFrontendExporterTouchTableMap::COL_ITEM_EVENT);
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

        return $this->addUsingAlias(SpyFrontendExporterTouchTableMap::COL_ITEM_EVENT, $itemEvent, $comparison);
    }

    /**
     * Filter the query on the export_type column
     *
     * @param     mixed $exportType The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSpyFrontendExporterTouchQuery The current query, for fluid interface
     */
    public function filterByExportType($exportType = null, $comparison = null)
    {
        $valueSet = SpyFrontendExporterTouchTableMap::getValueSet(SpyFrontendExporterTouchTableMap::COL_EXPORT_TYPE);
        if (is_scalar($exportType)) {
            if (!in_array($exportType, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $exportType));
            }
            $exportType = array_search($exportType, $valueSet);
        } elseif (is_array($exportType)) {
            $convertedValues = array();
            foreach ($exportType as $value) {
                if (!in_array($value, $valueSet)) {
                    throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $value));
                }
                $convertedValues []= array_search($value, $valueSet);
            }
            $exportType = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SpyFrontendExporterTouchTableMap::COL_EXPORT_TYPE, $exportType, $comparison);
    }

    /**
     * Filter the query on the item_id column
     *
     * Example usage:
     * <code>
     * $query->filterByItemId('fooValue');   // WHERE item_id = 'fooValue'
     * $query->filterByItemId('%fooValue%'); // WHERE item_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $itemId The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSpyFrontendExporterTouchQuery The current query, for fluid interface
     */
    public function filterByItemId($itemId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($itemId)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $itemId)) {
                $itemId = str_replace('*', '%', $itemId);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SpyFrontendExporterTouchTableMap::COL_ITEM_ID, $itemId, $comparison);
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
     * @return $this|ChildSpyFrontendExporterTouchQuery The current query, for fluid interface
     */
    public function filterByTouched($touched = null, $comparison = null)
    {
        if (is_array($touched)) {
            $useMinMax = false;
            if (isset($touched['min'])) {
                $this->addUsingAlias(SpyFrontendExporterTouchTableMap::COL_TOUCHED, $touched['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($touched['max'])) {
                $this->addUsingAlias(SpyFrontendExporterTouchTableMap::COL_TOUCHED, $touched['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SpyFrontendExporterTouchTableMap::COL_TOUCHED, $touched, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSpyFrontendExporterTouch $spyFrontendExporterTouch Object to remove from the list of results
     *
     * @return $this|ChildSpyFrontendExporterTouchQuery The current query, for fluid interface
     */
    public function prune($spyFrontendExporterTouch = null)
    {
        if ($spyFrontendExporterTouch) {
            $this->addUsingAlias(SpyFrontendExporterTouchTableMap::COL_ID_FRONTEND_EXPORTER_TOUCH, $spyFrontendExporterTouch->getIdFrontendExporterTouch(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the spy_frontend_exporter_touch table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SpyFrontendExporterTouchTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SpyFrontendExporterTouchTableMap::clearInstancePool();
            SpyFrontendExporterTouchTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(SpyFrontendExporterTouchTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SpyFrontendExporterTouchTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SpyFrontendExporterTouchTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SpyFrontendExporterTouchTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // SpyFrontendExporterTouchQuery
