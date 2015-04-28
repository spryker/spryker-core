<?php

namespace SprykerFeature\Zed\Payone\Persistence\Propel\Map;

use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLog;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLogQuery;


/**
 * This class defines the structure of the 'spy_payment_payone_transaction_status_log' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class SpyPaymentPayoneTransactionStatusLogTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'vendor.spryker.payone.src.SprykerFeature.Zed.Payone.Persistence.Propel.Map.SpyPaymentPayoneTransactionStatusLogTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'zed';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'spy_payment_payone_transaction_status_log';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\SprykerFeature\\Zed\\Payone\\Persistence\\Propel\\SpyPaymentPayoneTransactionStatusLog';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'vendor.spryker.payone.src.SprykerFeature.Zed.Payone.Persistence.Propel.SpyPaymentPayoneTransactionStatusLog';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 13;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 13;

    /**
     * the column name for the id_payment_payone_transaction_status_log field
     */
    const COL_ID_PAYMENT_PAYONE_TRANSACTION_STATUS_LOG = 'spy_payment_payone_transaction_status_log.id_payment_payone_transaction_status_log';

    /**
     * the column name for the transaction_id field
     */
    const COL_TRANSACTION_ID = 'spy_payment_payone_transaction_status_log.transaction_id';

    /**
     * the column name for the reference_id field
     */
    const COL_REFERENCE_ID = 'spy_payment_payone_transaction_status_log.reference_id';

    /**
     * the column name for the mode field
     */
    const COL_MODE = 'spy_payment_payone_transaction_status_log.mode';

    /**
     * the column name for the status field
     */
    const COL_STATUS = 'spy_payment_payone_transaction_status_log.status';

    /**
     * the column name for the transaction_time field
     */
    const COL_TRANSACTION_TIME = 'spy_payment_payone_transaction_status_log.transaction_time';

    /**
     * the column name for the sequence_number field
     */
    const COL_SEQUENCE_NUMBER = 'spy_payment_payone_transaction_status_log.sequence_number';

    /**
     * the column name for the clearing_type field
     */
    const COL_CLEARING_TYPE = 'spy_payment_payone_transaction_status_log.clearing_type';

    /**
     * the column name for the portal_id field
     */
    const COL_PORTAL_ID = 'spy_payment_payone_transaction_status_log.portal_id';

    /**
     * the column name for the balance field
     */
    const COL_BALANCE = 'spy_payment_payone_transaction_status_log.balance';

    /**
     * the column name for the receivable field
     */
    const COL_RECEIVABLE = 'spy_payment_payone_transaction_status_log.receivable';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'spy_payment_payone_transaction_status_log.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'spy_payment_payone_transaction_status_log.updated_at';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('IdPaymentPayoneTransactionStatusLog', 'TransactionId', 'ReferenceId', 'Mode', 'Status', 'TransactionTime', 'SequenceNumber', 'ClearingType', 'PortalId', 'Balance', 'Receivable', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('idPaymentPayoneTransactionStatusLog', 'transactionId', 'referenceId', 'mode', 'status', 'transactionTime', 'sequenceNumber', 'clearingType', 'portalId', 'balance', 'receivable', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(SpyPaymentPayoneTransactionStatusLogTableMap::COL_ID_PAYMENT_PAYONE_TRANSACTION_STATUS_LOG, SpyPaymentPayoneTransactionStatusLogTableMap::COL_TRANSACTION_ID, SpyPaymentPayoneTransactionStatusLogTableMap::COL_REFERENCE_ID, SpyPaymentPayoneTransactionStatusLogTableMap::COL_MODE, SpyPaymentPayoneTransactionStatusLogTableMap::COL_STATUS, SpyPaymentPayoneTransactionStatusLogTableMap::COL_TRANSACTION_TIME, SpyPaymentPayoneTransactionStatusLogTableMap::COL_SEQUENCE_NUMBER, SpyPaymentPayoneTransactionStatusLogTableMap::COL_CLEARING_TYPE, SpyPaymentPayoneTransactionStatusLogTableMap::COL_PORTAL_ID, SpyPaymentPayoneTransactionStatusLogTableMap::COL_BALANCE, SpyPaymentPayoneTransactionStatusLogTableMap::COL_RECEIVABLE, SpyPaymentPayoneTransactionStatusLogTableMap::COL_CREATED_AT, SpyPaymentPayoneTransactionStatusLogTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('id_payment_payone_transaction_status_log', 'transaction_id', 'reference_id', 'mode', 'status', 'transaction_time', 'sequence_number', 'clearing_type', 'portal_id', 'balance', 'receivable', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('IdPaymentPayoneTransactionStatusLog' => 0, 'TransactionId' => 1, 'ReferenceId' => 2, 'Mode' => 3, 'Status' => 4, 'TransactionTime' => 5, 'SequenceNumber' => 6, 'ClearingType' => 7, 'PortalId' => 8, 'Balance' => 9, 'Receivable' => 10, 'CreatedAt' => 11, 'UpdatedAt' => 12, ),
        self::TYPE_CAMELNAME     => array('idPaymentPayoneTransactionStatusLog' => 0, 'transactionId' => 1, 'referenceId' => 2, 'mode' => 3, 'status' => 4, 'transactionTime' => 5, 'sequenceNumber' => 6, 'clearingType' => 7, 'portalId' => 8, 'balance' => 9, 'receivable' => 10, 'createdAt' => 11, 'updatedAt' => 12, ),
        self::TYPE_COLNAME       => array(SpyPaymentPayoneTransactionStatusLogTableMap::COL_ID_PAYMENT_PAYONE_TRANSACTION_STATUS_LOG => 0, SpyPaymentPayoneTransactionStatusLogTableMap::COL_TRANSACTION_ID => 1, SpyPaymentPayoneTransactionStatusLogTableMap::COL_REFERENCE_ID => 2, SpyPaymentPayoneTransactionStatusLogTableMap::COL_MODE => 3, SpyPaymentPayoneTransactionStatusLogTableMap::COL_STATUS => 4, SpyPaymentPayoneTransactionStatusLogTableMap::COL_TRANSACTION_TIME => 5, SpyPaymentPayoneTransactionStatusLogTableMap::COL_SEQUENCE_NUMBER => 6, SpyPaymentPayoneTransactionStatusLogTableMap::COL_CLEARING_TYPE => 7, SpyPaymentPayoneTransactionStatusLogTableMap::COL_PORTAL_ID => 8, SpyPaymentPayoneTransactionStatusLogTableMap::COL_BALANCE => 9, SpyPaymentPayoneTransactionStatusLogTableMap::COL_RECEIVABLE => 10, SpyPaymentPayoneTransactionStatusLogTableMap::COL_CREATED_AT => 11, SpyPaymentPayoneTransactionStatusLogTableMap::COL_UPDATED_AT => 12, ),
        self::TYPE_FIELDNAME     => array('id_payment_payone_transaction_status_log' => 0, 'transaction_id' => 1, 'reference_id' => 2, 'mode' => 3, 'status' => 4, 'transaction_time' => 5, 'sequence_number' => 6, 'clearing_type' => 7, 'portal_id' => 8, 'balance' => 9, 'receivable' => 10, 'created_at' => 11, 'updated_at' => 12, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('spy_payment_payone_transaction_status_log');
        $this->setPhpName('SpyPaymentPayoneTransactionStatusLog');
        $this->setIdentifierQuoting(true);
        $this->setClassName('\\SprykerFeature\\Zed\\Payone\\Persistence\\Propel\\SpyPaymentPayoneTransactionStatusLog');
        $this->setPackage('vendor.spryker.payone.src.SprykerFeature.Zed.Payone.Persistence.Propel');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id_payment_payone_transaction_status_log', 'IdPaymentPayoneTransactionStatusLog', 'INTEGER', true, null, null);
        $this->addColumn('transaction_id', 'TransactionId', 'INTEGER', false, null, null);
        $this->addColumn('reference_id', 'ReferenceId', 'INTEGER', false, null, null);
        $this->addColumn('mode', 'Mode', 'VARCHAR', false, 255, null);
        $this->addColumn('status', 'Status', 'VARCHAR', false, 255, null);
        $this->addColumn('transaction_time', 'TransactionTime', 'TIMESTAMP', false, null, null);
        $this->addColumn('sequence_number', 'SequenceNumber', 'INTEGER', false, null, null);
        $this->addColumn('clearing_type', 'ClearingType', 'VARCHAR', false, 255, null);
        $this->addColumn('portal_id', 'PortalId', 'VARCHAR', false, 255, null);
        $this->addColumn('balance', 'Balance', 'INTEGER', false, null, null);
        $this->addColumn('receivable', 'Receivable', 'INTEGER', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', 'disable_created_at' => 'false', 'disable_updated_at' => 'false', ),
        );
    } // getBehaviors()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('IdPaymentPayoneTransactionStatusLog', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('IdPaymentPayoneTransactionStatusLog', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('IdPaymentPayoneTransactionStatusLog', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? SpyPaymentPayoneTransactionStatusLogTableMap::CLASS_DEFAULT : SpyPaymentPayoneTransactionStatusLogTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (SpyPaymentPayoneTransactionStatusLog object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = SpyPaymentPayoneTransactionStatusLogTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = SpyPaymentPayoneTransactionStatusLogTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + SpyPaymentPayoneTransactionStatusLogTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = SpyPaymentPayoneTransactionStatusLogTableMap::OM_CLASS;
            /** @var SpyPaymentPayoneTransactionStatusLog $obj */

                /* @var $locator \Generated\Zed\Ide\AutoCompletion */
                $locator = \SprykerEngine\Zed\Kernel\Locator::getInstance();
                $obj = $locator->payone()->entitySpyPaymentPayoneTransactionStatusLog();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            SpyPaymentPayoneTransactionStatusLogTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = SpyPaymentPayoneTransactionStatusLogTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = SpyPaymentPayoneTransactionStatusLogTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var SpyPaymentPayoneTransactionStatusLog $obj */

                /* @var $locator \Generated\Zed\Ide\AutoCompletion */
                $locator = \SprykerEngine\Zed\Kernel\Locator::getInstance();
                $obj = $locator->payone()->entitySpyPaymentPayoneTransactionStatusLog();
                $obj->hydrate($row);
                $results[] = $obj;
                SpyPaymentPayoneTransactionStatusLogTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(SpyPaymentPayoneTransactionStatusLogTableMap::COL_ID_PAYMENT_PAYONE_TRANSACTION_STATUS_LOG);
            $criteria->addSelectColumn(SpyPaymentPayoneTransactionStatusLogTableMap::COL_TRANSACTION_ID);
            $criteria->addSelectColumn(SpyPaymentPayoneTransactionStatusLogTableMap::COL_REFERENCE_ID);
            $criteria->addSelectColumn(SpyPaymentPayoneTransactionStatusLogTableMap::COL_MODE);
            $criteria->addSelectColumn(SpyPaymentPayoneTransactionStatusLogTableMap::COL_STATUS);
            $criteria->addSelectColumn(SpyPaymentPayoneTransactionStatusLogTableMap::COL_TRANSACTION_TIME);
            $criteria->addSelectColumn(SpyPaymentPayoneTransactionStatusLogTableMap::COL_SEQUENCE_NUMBER);
            $criteria->addSelectColumn(SpyPaymentPayoneTransactionStatusLogTableMap::COL_CLEARING_TYPE);
            $criteria->addSelectColumn(SpyPaymentPayoneTransactionStatusLogTableMap::COL_PORTAL_ID);
            $criteria->addSelectColumn(SpyPaymentPayoneTransactionStatusLogTableMap::COL_BALANCE);
            $criteria->addSelectColumn(SpyPaymentPayoneTransactionStatusLogTableMap::COL_RECEIVABLE);
            $criteria->addSelectColumn(SpyPaymentPayoneTransactionStatusLogTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(SpyPaymentPayoneTransactionStatusLogTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id_payment_payone_transaction_status_log');
            $criteria->addSelectColumn($alias . '.transaction_id');
            $criteria->addSelectColumn($alias . '.reference_id');
            $criteria->addSelectColumn($alias . '.mode');
            $criteria->addSelectColumn($alias . '.status');
            $criteria->addSelectColumn($alias . '.transaction_time');
            $criteria->addSelectColumn($alias . '.sequence_number');
            $criteria->addSelectColumn($alias . '.clearing_type');
            $criteria->addSelectColumn($alias . '.portal_id');
            $criteria->addSelectColumn($alias . '.balance');
            $criteria->addSelectColumn($alias . '.receivable');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(SpyPaymentPayoneTransactionStatusLogTableMap::DATABASE_NAME)->getTable(SpyPaymentPayoneTransactionStatusLogTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(SpyPaymentPayoneTransactionStatusLogTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(SpyPaymentPayoneTransactionStatusLogTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new SpyPaymentPayoneTransactionStatusLogTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a SpyPaymentPayoneTransactionStatusLog or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or SpyPaymentPayoneTransactionStatusLog object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SpyPaymentPayoneTransactionStatusLogTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLog) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(SpyPaymentPayoneTransactionStatusLogTableMap::DATABASE_NAME);
            $criteria->add(SpyPaymentPayoneTransactionStatusLogTableMap::COL_ID_PAYMENT_PAYONE_TRANSACTION_STATUS_LOG, (array) $values, Criteria::IN);
        }

        $query = SpyPaymentPayoneTransactionStatusLogQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            SpyPaymentPayoneTransactionStatusLogTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                SpyPaymentPayoneTransactionStatusLogTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the spy_payment_payone_transaction_status_log table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return SpyPaymentPayoneTransactionStatusLogQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a SpyPaymentPayoneTransactionStatusLog or Criteria object.
     *
     * @param mixed               $criteria Criteria or SpyPaymentPayoneTransactionStatusLog object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SpyPaymentPayoneTransactionStatusLogTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from SpyPaymentPayoneTransactionStatusLog object
        }

        if ($criteria->containsKey(SpyPaymentPayoneTransactionStatusLogTableMap::COL_ID_PAYMENT_PAYONE_TRANSACTION_STATUS_LOG) && $criteria->keyContainsValue(SpyPaymentPayoneTransactionStatusLogTableMap::COL_ID_PAYMENT_PAYONE_TRANSACTION_STATUS_LOG) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.SpyPaymentPayoneTransactionStatusLogTableMap::COL_ID_PAYMENT_PAYONE_TRANSACTION_STATUS_LOG.')');
        }


        // Set the correct dbName
        $query = SpyPaymentPayoneTransactionStatusLogQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // SpyPaymentPayoneTransactionStatusLogTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
SpyPaymentPayoneTransactionStatusLogTableMap::buildTableMap();
