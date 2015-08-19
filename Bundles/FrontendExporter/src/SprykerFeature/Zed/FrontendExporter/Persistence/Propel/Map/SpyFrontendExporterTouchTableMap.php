<?php

namespace SprykerFeature\Zed\FrontendExporter\Persistence\Propel\Map;

use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;
use SprykerFeature\Zed\FrontendExporter\Persistence\Propel\SpyFrontendExporterTouch;
use SprykerFeature\Zed\FrontendExporter\Persistence\Propel\SpyFrontendExporterTouchQuery;


/**
 * This class defines the structure of the 'spy_frontend_exporter_touch' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class SpyFrontendExporterTouchTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'vendor.spryker.spryker.Bundles.FrontendExporter.src.SprykerFeature.Zed.FrontendExporter.Persistence.Propel.Map.SpyFrontendExporterTouchTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'zed';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'spy_frontend_exporter_touch';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\SprykerFeature\\Zed\\FrontendExporter\\Persistence\\Propel\\SpyFrontendExporterTouch';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'vendor.spryker.spryker.Bundles.FrontendExporter.src.SprykerFeature.Zed.FrontendExporter.Persistence.Propel.SpyFrontendExporterTouch';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 6;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 6;

    /**
     * the column name for the id_frontend_exporter_touch field
     */
    const COL_ID_FRONTEND_EXPORTER_TOUCH = 'spy_frontend_exporter_touch.id_frontend_exporter_touch';

    /**
     * the column name for the item_type field
     */
    const COL_ITEM_TYPE = 'spy_frontend_exporter_touch.item_type';

    /**
     * the column name for the item_event field
     */
    const COL_ITEM_EVENT = 'spy_frontend_exporter_touch.item_event';

    /**
     * the column name for the export_type field
     */
    const COL_EXPORT_TYPE = 'spy_frontend_exporter_touch.export_type';

    /**
     * the column name for the item_id field
     */
    const COL_ITEM_ID = 'spy_frontend_exporter_touch.item_id';

    /**
     * the column name for the touched field
     */
    const COL_TOUCHED = 'spy_frontend_exporter_touch.touched';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /** The enumerated values for the item_event field */
    const COL_ITEM_EVENT_ACTIVE = 'active';
    const COL_ITEM_EVENT_INACTIVE = 'inactive';
    const COL_ITEM_EVENT_DELETED = 'deleted';

    /** The enumerated values for the export_type field */
    const COL_EXPORT_TYPE_SEARCH = 'search';
    const COL_EXPORT_TYPE_KEYVALUE = 'keyvalue';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('IdFrontendExporterTouch', 'ItemType', 'ItemEvent', 'ExportType', 'ItemId', 'Touched', ),
        self::TYPE_CAMELNAME     => array('idFrontendExporterTouch', 'itemType', 'itemEvent', 'exportType', 'itemId', 'touched', ),
        self::TYPE_COLNAME       => array(SpyFrontendExporterTouchTableMap::COL_ID_FRONTEND_EXPORTER_TOUCH, SpyFrontendExporterTouchTableMap::COL_ITEM_TYPE, SpyFrontendExporterTouchTableMap::COL_ITEM_EVENT, SpyFrontendExporterTouchTableMap::COL_EXPORT_TYPE, SpyFrontendExporterTouchTableMap::COL_ITEM_ID, SpyFrontendExporterTouchTableMap::COL_TOUCHED, ),
        self::TYPE_FIELDNAME     => array('id_frontend_exporter_touch', 'item_type', 'item_event', 'export_type', 'item_id', 'touched', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('IdFrontendExporterTouch' => 0, 'ItemType' => 1, 'ItemEvent' => 2, 'ExportType' => 3, 'ItemId' => 4, 'Touched' => 5, ),
        self::TYPE_CAMELNAME     => array('idFrontendExporterTouch' => 0, 'itemType' => 1, 'itemEvent' => 2, 'exportType' => 3, 'itemId' => 4, 'touched' => 5, ),
        self::TYPE_COLNAME       => array(SpyFrontendExporterTouchTableMap::COL_ID_FRONTEND_EXPORTER_TOUCH => 0, SpyFrontendExporterTouchTableMap::COL_ITEM_TYPE => 1, SpyFrontendExporterTouchTableMap::COL_ITEM_EVENT => 2, SpyFrontendExporterTouchTableMap::COL_EXPORT_TYPE => 3, SpyFrontendExporterTouchTableMap::COL_ITEM_ID => 4, SpyFrontendExporterTouchTableMap::COL_TOUCHED => 5, ),
        self::TYPE_FIELDNAME     => array('id_frontend_exporter_touch' => 0, 'item_type' => 1, 'item_event' => 2, 'export_type' => 3, 'item_id' => 4, 'touched' => 5, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /** The enumerated values for this table */
    protected static $enumValueSets = array(
                SpyFrontendExporterTouchTableMap::COL_ITEM_EVENT => array(
                            self::COL_ITEM_EVENT_ACTIVE,
            self::COL_ITEM_EVENT_INACTIVE,
            self::COL_ITEM_EVENT_DELETED,
        ),
                SpyFrontendExporterTouchTableMap::COL_EXPORT_TYPE => array(
                            self::COL_EXPORT_TYPE_SEARCH,
            self::COL_EXPORT_TYPE_KEYVALUE,
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
        $this->setName('spy_frontend_exporter_touch');
        $this->setPhpName('SpyFrontendExporterTouch');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\SprykerFeature\\Zed\\FrontendExporter\\Persistence\\Propel\\SpyFrontendExporterTouch');
        $this->setPackage('vendor.spryker.spryker.Bundles.FrontendExporter.src.SprykerFeature.Zed.FrontendExporter.Persistence.Propel');
        $this->setUseIdGenerator(true);
        $this->setPrimaryKeyMethodInfo('spy_frontend_exporter_touch_pk_seq');
        // columns
        $this->addPrimaryKey('id_frontend_exporter_touch', 'IdFrontendExporterTouch', 'INTEGER', true, null, null);
        $this->addColumn('item_type', 'ItemType', 'VARCHAR', true, 255, null);
        $this->addColumn('item_event', 'ItemEvent', 'ENUM', true, null, null);
        $this->getColumn('item_event')->setValueSet(array (
  0 => 'active',
  1 => 'inactive',
  2 => 'deleted',
));
        $this->addColumn('export_type', 'ExportType', 'ENUM', true, null, 'keyvalue');
        $this->getColumn('export_type')->setValueSet(array (
  0 => 'search',
  1 => 'keyvalue',
));
        $this->addColumn('item_id', 'ItemId', 'VARCHAR', true, 255, null);
        $this->addColumn('touched', 'Touched', 'TIMESTAMP', true, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
    } // buildRelations()

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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('IdFrontendExporterTouch', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('IdFrontendExporterTouch', TableMap::TYPE_PHPNAME, $indexType)];
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
                : self::translateFieldName('IdFrontendExporterTouch', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? SpyFrontendExporterTouchTableMap::CLASS_DEFAULT : SpyFrontendExporterTouchTableMap::OM_CLASS;
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
     * @return array           (SpyFrontendExporterTouch object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = SpyFrontendExporterTouchTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = SpyFrontendExporterTouchTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + SpyFrontendExporterTouchTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = SpyFrontendExporterTouchTableMap::OM_CLASS;
            /** @var SpyFrontendExporterTouch $obj */

                /** @var \Generated\Zed\Ide\AutoCompletion $locator */
                $locator = \SprykerEngine\Zed\Kernel\Locator::getInstance();
                $obj = $locator->frontendExporter()->entitySpyFrontendExporterTouch();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            SpyFrontendExporterTouchTableMap::addInstanceToPool($obj, $key);
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
            $key = SpyFrontendExporterTouchTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = SpyFrontendExporterTouchTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var SpyFrontendExporterTouch $obj */

                /** @var \Generated\Zed\Ide\AutoCompletion $locator */
                $locator = \SprykerEngine\Zed\Kernel\Locator::getInstance();
                $obj = $locator->frontendExporter()->entitySpyFrontendExporterTouch();
                $obj->hydrate($row);
                $results[] = $obj;
                SpyFrontendExporterTouchTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(SpyFrontendExporterTouchTableMap::COL_ID_FRONTEND_EXPORTER_TOUCH);
            $criteria->addSelectColumn(SpyFrontendExporterTouchTableMap::COL_ITEM_TYPE);
            $criteria->addSelectColumn(SpyFrontendExporterTouchTableMap::COL_ITEM_EVENT);
            $criteria->addSelectColumn(SpyFrontendExporterTouchTableMap::COL_EXPORT_TYPE);
            $criteria->addSelectColumn(SpyFrontendExporterTouchTableMap::COL_ITEM_ID);
            $criteria->addSelectColumn(SpyFrontendExporterTouchTableMap::COL_TOUCHED);
        } else {
            $criteria->addSelectColumn($alias . '.id_frontend_exporter_touch');
            $criteria->addSelectColumn($alias . '.item_type');
            $criteria->addSelectColumn($alias . '.item_event');
            $criteria->addSelectColumn($alias . '.export_type');
            $criteria->addSelectColumn($alias . '.item_id');
            $criteria->addSelectColumn($alias . '.touched');
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
        return Propel::getServiceContainer()->getDatabaseMap(SpyFrontendExporterTouchTableMap::DATABASE_NAME)->getTable(SpyFrontendExporterTouchTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(SpyFrontendExporterTouchTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(SpyFrontendExporterTouchTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new SpyFrontendExporterTouchTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a SpyFrontendExporterTouch or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or SpyFrontendExporterTouch object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(SpyFrontendExporterTouchTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \SprykerFeature\Zed\FrontendExporter\Persistence\Propel\SpyFrontendExporterTouch) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(SpyFrontendExporterTouchTableMap::DATABASE_NAME);
            $criteria->add(SpyFrontendExporterTouchTableMap::COL_ID_FRONTEND_EXPORTER_TOUCH, (array) $values, Criteria::IN);
        }

        $query = SpyFrontendExporterTouchQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            SpyFrontendExporterTouchTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                SpyFrontendExporterTouchTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the spy_frontend_exporter_touch table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return SpyFrontendExporterTouchQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a SpyFrontendExporterTouch or Criteria object.
     *
     * @param mixed               $criteria Criteria or SpyFrontendExporterTouch object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SpyFrontendExporterTouchTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from SpyFrontendExporterTouch object
        }


        // Set the correct dbName
        $query = SpyFrontendExporterTouchQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // SpyFrontendExporterTouchTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
SpyFrontendExporterTouchTableMap::buildTableMap();
