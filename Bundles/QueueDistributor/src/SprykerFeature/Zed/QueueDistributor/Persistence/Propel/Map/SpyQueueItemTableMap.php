<?php

namespace SprykerFeature\Zed\QueueDistributor\Persistence\Propel\Map;

use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItem;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItemQuery;


/**
 * This class defines the structure of the 'spy_queue_item' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class SpyQueueItemTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'vendor.spryker.spryker.Bundles.QueueDistributor.src.SprykerFeature.Zed.QueueDistributor.Persistence.Propel.Map.SpyQueueItemTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'zed';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'spy_queue_item';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\SprykerFeature\\Zed\\QueueDistributor\\Persistence\\Propel\\SpyQueueItem';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'vendor.spryker.spryker.Bundles.QueueDistributor.src.SprykerFeature.Zed.QueueDistributor.Persistence.Propel.SpyQueueItem';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 4;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 4;

    /**
     * the column name for the id_queue_item field
     */
    const COL_ID_QUEUE_ITEM = 'spy_queue_item.id_queue_item';

    /**
     * the column name for the item_event field
     */
    const COL_ITEM_EVENT = 'spy_queue_item.item_event';

    /**
     * the column name for the touched field
     */
    const COL_TOUCHED = 'spy_queue_item.touched';

    /**
     * the column name for the fk_item_type field
     */
    const COL_FK_ITEM_TYPE = 'spy_queue_item.fk_item_type';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /** The enumerated values for the item_event field */
    const COL_ITEM_EVENT_ACTIVE = 'active';
    const COL_ITEM_EVENT_INACTIVE = 'inactive';
    const COL_ITEM_EVENT_DELETED = 'deleted';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('IdQueueItem', 'ItemEvent', 'Touched', 'FkItemType', ),
        self::TYPE_CAMELNAME     => array('idQueueItem', 'itemEvent', 'touched', 'fkItemType', ),
        self::TYPE_COLNAME       => array(SpyQueueItemTableMap::COL_ID_QUEUE_ITEM, SpyQueueItemTableMap::COL_ITEM_EVENT, SpyQueueItemTableMap::COL_TOUCHED, SpyQueueItemTableMap::COL_FK_ITEM_TYPE, ),
        self::TYPE_FIELDNAME     => array('id_queue_item', 'item_event', 'touched', 'fk_item_type', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('IdQueueItem' => 0, 'ItemEvent' => 1, 'Touched' => 2, 'FkItemType' => 3, ),
        self::TYPE_CAMELNAME     => array('idQueueItem' => 0, 'itemEvent' => 1, 'touched' => 2, 'fkItemType' => 3, ),
        self::TYPE_COLNAME       => array(SpyQueueItemTableMap::COL_ID_QUEUE_ITEM => 0, SpyQueueItemTableMap::COL_ITEM_EVENT => 1, SpyQueueItemTableMap::COL_TOUCHED => 2, SpyQueueItemTableMap::COL_FK_ITEM_TYPE => 3, ),
        self::TYPE_FIELDNAME     => array('id_queue_item' => 0, 'item_event' => 1, 'touched' => 2, 'fk_item_type' => 3, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, )
    );

    /** The enumerated values for this table */
    protected static $enumValueSets = array(
                SpyQueueItemTableMap::COL_ITEM_EVENT => array(
                            self::COL_ITEM_EVENT_ACTIVE,
            self::COL_ITEM_EVENT_INACTIVE,
            self::COL_ITEM_EVENT_DELETED,
        ),
    );

    /**
     * Gets the list of values for all ENUM columns
     * @return array
     */
    public static function getValueSets()
    {
      return static::$enumValueSets;
    }

    /**
     * Gets the list of values for an ENUM column
     * @param string $colname
     * @return array list of possible values for the column
     */
    public static function getValueSet($colname)
    {
        $valueSets = self::getValueSets();

        return $valueSets[$colname];
    }

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
        $this->setName('spy_queue_item');
        $this->setPhpName('SpyQueueItem');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\SprykerFeature\\Zed\\QueueDistributor\\Persistence\\Propel\\SpyQueueItem');
        $this->setPackage('vendor.spryker.spryker.Bundles.QueueDistributor.src.SprykerFeature.Zed.QueueDistributor.Persistence.Propel');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id_queue_item', 'IdQueueItem', 'INTEGER', true, null, null);
        $this->addColumn('item_event', 'ItemEvent', 'ENUM', true, null, null);
        $this->getColumn('item_event')->setValueSet(array (
  0 => 'active',
  1 => 'inactive',
  2 => 'deleted',
));
        $this->addColumn('touched', 'Touched', 'TIMESTAMP', true, null, null);
        $this->addForeignPrimaryKey('fk_item_type', 'FkItemType', 'INTEGER' , 'spy_queue_item_type', 'id_queue_item_type', true, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('SpyQueueItemType', '\\SprykerFeature\\Zed\\QueueDistributor\\Persistence\\Propel\\SpyQueueItemType', RelationMap::MANY_TO_ONE, array('fk_item_type' => 'id_queue_item_type', ), null, null);
    } // buildRelations()

    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database. In some cases you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by find*()
     * and findPk*() calls.
     *
     * @param \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItem $obj A \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItem object.
     * @param string $key             (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (null === $key) {
                $key = serialize(array((string) $obj->getIdQueueItem(), (string) $obj->getFkItemType()));
            } // if key === null
            self::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param mixed $value A \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItem object or a primary key value.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && null !== $value) {
            if (is_object($value) && $value instanceof \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItem) {
                $key = serialize(array((string) $value->getIdQueueItem(), (string) $value->getFkItemType()));

            } elseif (is_array($value) && count($value) === 2) {
                // assume we've been passed a primary key";
                $key = serialize(array((string) $value[0], (string) $value[1]));
            } elseif ($value instanceof Criteria) {
                self::$instances = [];

                return;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItem object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value, true)));
                throw $e;
            }

            unset(self::$instances[$key]);
        }
    }

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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('IdQueueItem', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 3 + $offset : static::translateFieldName('FkItemType', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return serialize(array((string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('IdQueueItem', TableMap::TYPE_PHPNAME, $indexType)], (string) $row[TableMap::TYPE_NUM == $indexType ? 3 + $offset : static::translateFieldName('FkItemType', TableMap::TYPE_PHPNAME, $indexType)]));
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
            $pks = [];

        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('IdQueueItem', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 3 + $offset
                : self::translateFieldName('FkItemType', TableMap::TYPE_PHPNAME, $indexType)
        ];

        return $pks;
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
        return $withPrefix ? SpyQueueItemTableMap::CLASS_DEFAULT : SpyQueueItemTableMap::OM_CLASS;
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
     * @return array           (SpyQueueItem object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = SpyQueueItemTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = SpyQueueItemTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + SpyQueueItemTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = SpyQueueItemTableMap::OM_CLASS;
            /** @var SpyQueueItem $obj */

                /* @var $locator \Generated\Zed\Ide\AutoCompletion */
                $locator = \SprykerEngine\Zed\Kernel\Locator::getInstance();
                $obj = $locator->queueDistributor()->entitySpyQueueItem();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            SpyQueueItemTableMap::addInstanceToPool($obj, $key);
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
            $key = SpyQueueItemTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = SpyQueueItemTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var SpyQueueItem $obj */

                /* @var $locator \Generated\Zed\Ide\AutoCompletion */
                $locator = \SprykerEngine\Zed\Kernel\Locator::getInstance();
                $obj = $locator->queueDistributor()->entitySpyQueueItem();
                $obj->hydrate($row);
                $results[] = $obj;
                SpyQueueItemTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(SpyQueueItemTableMap::COL_ID_QUEUE_ITEM);
            $criteria->addSelectColumn(SpyQueueItemTableMap::COL_ITEM_EVENT);
            $criteria->addSelectColumn(SpyQueueItemTableMap::COL_TOUCHED);
            $criteria->addSelectColumn(SpyQueueItemTableMap::COL_FK_ITEM_TYPE);
        } else {
            $criteria->addSelectColumn($alias . '.id_queue_item');
            $criteria->addSelectColumn($alias . '.item_event');
            $criteria->addSelectColumn($alias . '.touched');
            $criteria->addSelectColumn($alias . '.fk_item_type');
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
        return Propel::getServiceContainer()->getDatabaseMap(SpyQueueItemTableMap::DATABASE_NAME)->getTable(SpyQueueItemTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(SpyQueueItemTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(SpyQueueItemTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new SpyQueueItemTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a SpyQueueItem or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or SpyQueueItem object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(SpyQueueItemTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItem) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(SpyQueueItemTableMap::DATABASE_NAME);
            // primary key is composite; we therefore, expect
            // the primary key passed to be an array of pkey values
            if (count($values) == count($values, COUNT_RECURSIVE)) {
                // array is not multi-dimensional
                $values = array($values);
            }
            foreach ($values as $value) {
                $criterion = $criteria->getNewCriterion(SpyQueueItemTableMap::COL_ID_QUEUE_ITEM, $value[0]);
                $criterion->addAnd($criteria->getNewCriterion(SpyQueueItemTableMap::COL_FK_ITEM_TYPE, $value[1]));
                $criteria->addOr($criterion);
            }
        }

        $query = SpyQueueItemQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            SpyQueueItemTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                SpyQueueItemTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the spy_queue_item table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return SpyQueueItemQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a SpyQueueItem or Criteria object.
     *
     * @param mixed               $criteria Criteria or SpyQueueItem object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SpyQueueItemTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from SpyQueueItem object
        }

        if ($criteria->containsKey(SpyQueueItemTableMap::COL_ID_QUEUE_ITEM) && $criteria->keyContainsValue(SpyQueueItemTableMap::COL_ID_QUEUE_ITEM) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.SpyQueueItemTableMap::COL_ID_QUEUE_ITEM.')');
        }


        // Set the correct dbName
        $query = SpyQueueItemQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // SpyQueueItemTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
SpyQueueItemTableMap::buildTableMap();
