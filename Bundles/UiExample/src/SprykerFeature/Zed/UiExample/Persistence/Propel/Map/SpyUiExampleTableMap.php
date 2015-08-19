<?php

namespace SprykerFeature\Zed\UiExample\Persistence\Propel\Map;

use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;
use SprykerFeature\Zed\UiExample\Persistence\Propel\SpyUiExample;
use SprykerFeature\Zed\UiExample\Persistence\Propel\SpyUiExampleQuery;


/**
 * This class defines the structure of the 'spy_ui_example' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class SpyUiExampleTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'vendor.spryker.spryker.Bundles.UiExample.src.SprykerFeature.Zed.UiExample.Persistence.Propel.Map.SpyUiExampleTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'zed';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'spy_ui_example';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\SprykerFeature\\Zed\\UiExample\\Persistence\\Propel\\SpyUiExample';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'vendor.spryker.spryker.Bundles.UiExample.src.SprykerFeature.Zed.UiExample.Persistence.Propel.SpyUiExample';

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
     * the column name for the id_ui_example field
     */
    const COL_ID_UI_EXAMPLE = 'spy_ui_example.id_ui_example';

    /**
     * the column name for the column_for_string field
     */
    const COL_COLUMN_FOR_STRING = 'spy_ui_example.column_for_string';

    /**
     * the column name for the column_for_boolean field
     */
    const COL_COLUMN_FOR_BOOLEAN = 'spy_ui_example.column_for_boolean';

    /**
     * the column name for the column_for_timestamp field
     */
    const COL_COLUMN_FOR_TIMESTAMP = 'spy_ui_example.column_for_timestamp';

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
        self::TYPE_PHPNAME       => array('IdUiExample', 'ColumnForString', 'ColumnForBoolean', 'ColumnForTimestamp', ),
        self::TYPE_CAMELNAME     => array('idUiExample', 'columnForString', 'columnForBoolean', 'columnForTimestamp', ),
        self::TYPE_COLNAME       => array(SpyUiExampleTableMap::COL_ID_UI_EXAMPLE, SpyUiExampleTableMap::COL_COLUMN_FOR_STRING, SpyUiExampleTableMap::COL_COLUMN_FOR_BOOLEAN, SpyUiExampleTableMap::COL_COLUMN_FOR_TIMESTAMP, ),
        self::TYPE_FIELDNAME     => array('id_ui_example', 'column_for_string', 'column_for_boolean', 'column_for_timestamp', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('IdUiExample' => 0, 'ColumnForString' => 1, 'ColumnForBoolean' => 2, 'ColumnForTimestamp' => 3, ),
        self::TYPE_CAMELNAME     => array('idUiExample' => 0, 'columnForString' => 1, 'columnForBoolean' => 2, 'columnForTimestamp' => 3, ),
        self::TYPE_COLNAME       => array(SpyUiExampleTableMap::COL_ID_UI_EXAMPLE => 0, SpyUiExampleTableMap::COL_COLUMN_FOR_STRING => 1, SpyUiExampleTableMap::COL_COLUMN_FOR_BOOLEAN => 2, SpyUiExampleTableMap::COL_COLUMN_FOR_TIMESTAMP => 3, ),
        self::TYPE_FIELDNAME     => array('id_ui_example' => 0, 'column_for_string' => 1, 'column_for_boolean' => 2, 'column_for_timestamp' => 3, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, )
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
        $this->setName('spy_ui_example');
        $this->setPhpName('SpyUiExample');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\SprykerFeature\\Zed\\UiExample\\Persistence\\Propel\\SpyUiExample');
        $this->setPackage('vendor.spryker.spryker.Bundles.UiExample.src.SprykerFeature.Zed.UiExample.Persistence.Propel');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id_ui_example', 'IdUiExample', 'INTEGER', true, null, null);
        $this->addColumn('column_for_string', 'ColumnForString', 'VARCHAR', true, 255, null);
        $this->addColumn('column_for_boolean', 'ColumnForBoolean', 'BOOLEAN', true, 1, null);
        $this->addColumn('column_for_timestamp', 'ColumnForTimestamp', 'TIMESTAMP', true, null, null);
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('IdUiExample', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('IdUiExample', TableMap::TYPE_PHPNAME, $indexType)];
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
                : self::translateFieldName('IdUiExample', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? SpyUiExampleTableMap::CLASS_DEFAULT : SpyUiExampleTableMap::OM_CLASS;
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
     * @return array           (SpyUiExample object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = SpyUiExampleTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = SpyUiExampleTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + SpyUiExampleTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = SpyUiExampleTableMap::OM_CLASS;
            /** @var SpyUiExample $obj */

                /** @var \Generated\Zed\Ide\AutoCompletion $locator */
                $locator = \SprykerEngine\Zed\Kernel\Locator::getInstance();
                $obj = $locator->uiExample()->entitySpyUiExample();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            SpyUiExampleTableMap::addInstanceToPool($obj, $key);
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
            $key = SpyUiExampleTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = SpyUiExampleTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var SpyUiExample $obj */

                /** @var \Generated\Zed\Ide\AutoCompletion $locator */
                $locator = \SprykerEngine\Zed\Kernel\Locator::getInstance();
                $obj = $locator->uiExample()->entitySpyUiExample();
                $obj->hydrate($row);
                $results[] = $obj;
                SpyUiExampleTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(SpyUiExampleTableMap::COL_ID_UI_EXAMPLE);
            $criteria->addSelectColumn(SpyUiExampleTableMap::COL_COLUMN_FOR_STRING);
            $criteria->addSelectColumn(SpyUiExampleTableMap::COL_COLUMN_FOR_BOOLEAN);
            $criteria->addSelectColumn(SpyUiExampleTableMap::COL_COLUMN_FOR_TIMESTAMP);
        } else {
            $criteria->addSelectColumn($alias . '.id_ui_example');
            $criteria->addSelectColumn($alias . '.column_for_string');
            $criteria->addSelectColumn($alias . '.column_for_boolean');
            $criteria->addSelectColumn($alias . '.column_for_timestamp');
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
        return Propel::getServiceContainer()->getDatabaseMap(SpyUiExampleTableMap::DATABASE_NAME)->getTable(SpyUiExampleTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(SpyUiExampleTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(SpyUiExampleTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new SpyUiExampleTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a SpyUiExample or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or SpyUiExample object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(SpyUiExampleTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \SprykerFeature\Zed\UiExample\Persistence\Propel\SpyUiExample) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(SpyUiExampleTableMap::DATABASE_NAME);
            $criteria->add(SpyUiExampleTableMap::COL_ID_UI_EXAMPLE, (array) $values, Criteria::IN);
        }

        $query = SpyUiExampleQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            SpyUiExampleTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                SpyUiExampleTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the spy_ui_example table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return SpyUiExampleQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a SpyUiExample or Criteria object.
     *
     * @param mixed               $criteria Criteria or SpyUiExample object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SpyUiExampleTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from SpyUiExample object
        }

        if ($criteria->containsKey(SpyUiExampleTableMap::COL_ID_UI_EXAMPLE) && $criteria->keyContainsValue(SpyUiExampleTableMap::COL_ID_UI_EXAMPLE) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.SpyUiExampleTableMap::COL_ID_UI_EXAMPLE.')');
        }


        // Set the correct dbName
        $query = SpyUiExampleQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // SpyUiExampleTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
SpyUiExampleTableMap::buildTableMap();
