<?php

namespace SprykerFeature\Zed\QueueDistributor\Persistence\Propel\Base;

use \DateTime;
use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItem as ChildSpyQueueItem;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItemQuery as ChildSpyQueueItemQuery;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItemType as ChildSpyQueueItemType;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItemTypeQuery as ChildSpyQueueItemTypeQuery;
use SprykerFeature\Zed\QueueDistributor\Persistence\Propel\Map\SpyQueueItemTypeTableMap;

/**
 * Base class that represents a row from the 'spy_queue_item_type' table.
 *
 *
 *
* @package    propel.generator.vendor.spryker.spryker.Bundles.QueueDistributor.src.SprykerFeature.Zed.QueueDistributor.Persistence.Propel.Base
*/
abstract class SpyQueueItemType implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\SprykerFeature\\Zed\\QueueDistributor\\Persistence\\Propel\\Map\\SpyQueueItemTypeTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id_queue_item_type field.
     * @var        int
     */
    protected $id_queue_item_type;

    /**
     * The value for the key field.
     * @var        string
     */
    protected $key;

    /**
     * The value for the last_distribution field.
     * @var        \DateTime
     */
    protected $last_distribution;

    /**
     * @var        ObjectCollection|ChildSpyQueueItem[] Collection to store aggregation of ChildSpyQueueItem objects.
     */
    protected $collSpyQueueItems;
    protected $collSpyQueueItemsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildSpyQueueItem[]
     */
    protected $spyQueueItemsScheduledForDeletion = null;

    /**
     * Initializes internal state of SprykerFeature\Zed\QueueDistributor\Persistence\Propel\Base\SpyQueueItemType object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>SpyQueueItemType</code> instance.  If
     * <code>obj</code> is an instance of <code>SpyQueueItemType</code>, delegates to
     * <code>equals(SpyQueueItemType)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|SpyQueueItemType The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        return array_keys(get_object_vars($this));
    }

    /**
     * Get the [id_queue_item_type] column value.
     *
     * @return int
     */
    public function getIdQueueItemType()
    {
        return $this->id_queue_item_type;
    }

    /**
     * Get the [key] column value.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Get the [optionally formatted] temporal [last_distribution] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getLastDistribution($format = NULL)
    {
        if ($format === null) {
            return $this->last_distribution;
        } else {
            return $this->last_distribution instanceof \DateTime ? $this->last_distribution->format($format) : null;
        }
    }

    /**
     * Set the value of [id_queue_item_type] column.
     *
     * @param  int $v new value
     * @return $this|\SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItemType The current object (for fluent API support)
     */
    public function setIdQueueItemType($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id_queue_item_type !== $v) {
            $this->id_queue_item_type = $v;
            $this->modifiedColumns[SpyQueueItemTypeTableMap::COL_ID_QUEUE_ITEM_TYPE] = true;
        }

        return $this;
    } // setIdQueueItemType()

    /**
     * Set the value of [key] column.
     *
     * @param  string $v new value
     * @return $this|\SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItemType The current object (for fluent API support)
     */
    public function setKey($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->key !== $v) {
            $this->key = $v;
            $this->modifiedColumns[SpyQueueItemTypeTableMap::COL_KEY] = true;
        }

        return $this;
    } // setKey()

    /**
     * Sets the value of [last_distribution] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItemType The current object (for fluent API support)
     */
    public function setLastDistribution($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->last_distribution !== null || $dt !== null) {
            if ($dt !== $this->last_distribution) {
                $this->last_distribution = $dt;
                $this->modifiedColumns[SpyQueueItemTypeTableMap::COL_LAST_DISTRIBUTION] = true;
            }
        } // if either are not null

        return $this;
    } // setLastDistribution()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : SpyQueueItemTypeTableMap::translateFieldName('IdQueueItemType', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id_queue_item_type = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : SpyQueueItemTypeTableMap::translateFieldName('Key', TableMap::TYPE_PHPNAME, $indexType)];
            $this->key = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : SpyQueueItemTypeTableMap::translateFieldName('LastDistribution', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->last_distribution = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 3; // 3 = SpyQueueItemTypeTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\SprykerFeature\\Zed\\QueueDistributor\\Persistence\\Propel\\SpyQueueItemType'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SpyQueueItemTypeTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildSpyQueueItemTypeQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collSpyQueueItems = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see SpyQueueItemType::setDeleted()
     * @see SpyQueueItemType::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(SpyQueueItemTypeTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildSpyQueueItemTypeQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(SpyQueueItemTypeTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $isInsert = $this->isNew();
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                SpyQueueItemTypeTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->spyQueueItemsScheduledForDeletion !== null) {
                if (!$this->spyQueueItemsScheduledForDeletion->isEmpty()) {
                    \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItemQuery::create()
                        ->filterByPrimaryKeys($this->spyQueueItemsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->spyQueueItemsScheduledForDeletion = null;
                }
            }

            if ($this->collSpyQueueItems !== null) {
                foreach ($this->collSpyQueueItems as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[SpyQueueItemTypeTableMap::COL_ID_QUEUE_ITEM_TYPE] = true;
        if (null !== $this->id_queue_item_type) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . SpyQueueItemTypeTableMap::COL_ID_QUEUE_ITEM_TYPE . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(SpyQueueItemTypeTableMap::COL_ID_QUEUE_ITEM_TYPE)) {
            $modifiedColumns[':p' . $index++]  = 'id_queue_item_type';
        }
        if ($this->isColumnModified(SpyQueueItemTypeTableMap::COL_KEY)) {
            $modifiedColumns[':p' . $index++]  = 'key';
        }
        if ($this->isColumnModified(SpyQueueItemTypeTableMap::COL_LAST_DISTRIBUTION)) {
            $modifiedColumns[':p' . $index++]  = 'last_distribution';
        }

        $sql = sprintf(
            'INSERT INTO spy_queue_item_type (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'id_queue_item_type':
                        $stmt->bindValue($identifier, $this->id_queue_item_type, PDO::PARAM_INT);
                        break;
                    case 'key':
                        $stmt->bindValue($identifier, $this->key, PDO::PARAM_STR);
                        break;
                    case 'last_distribution':
                        $stmt->bindValue($identifier, $this->last_distribution ? $this->last_distribution->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setIdQueueItemType($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_FIELDNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_FIELDNAME)
    {
        $pos = SpyQueueItemTypeTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getIdQueueItemType();
                break;
            case 1:
                return $this->getKey();
                break;
            case 2:
                return $this->getLastDistribution();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_FIELDNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_FIELDNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['SpyQueueItemType'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['SpyQueueItemType'][$this->hashCode()] = true;
        $keys = SpyQueueItemTypeTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getIdQueueItemType(),
            $keys[1] => $this->getKey(),
            $keys[2] => $this->getLastDistribution(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collSpyQueueItems) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'spyQueueItems';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'spy_queue_items';
                        break;
                    default:
                        $key = 'SpyQueueItems';
                }

                $result[$key] = $this->collSpyQueueItems->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_FIELDNAME.
     * @return $this|\SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItemType
     */
    public function setByName($name, $value, $type = TableMap::TYPE_FIELDNAME)
    {
        $pos = SpyQueueItemTypeTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItemType
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setIdQueueItemType($value);
                break;
            case 1:
                $this->setKey($value);
                break;
            case 2:
                $this->setLastDistribution($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_FIELDNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_FIELDNAME)
    {
        $keys = SpyQueueItemTypeTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setIdQueueItemType($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setKey($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setLastDistribution($arr[$keys[2]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_FIELDNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItemType The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_FIELDNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(SpyQueueItemTypeTableMap::DATABASE_NAME);

        if ($this->isColumnModified(SpyQueueItemTypeTableMap::COL_ID_QUEUE_ITEM_TYPE)) {
            $criteria->add(SpyQueueItemTypeTableMap::COL_ID_QUEUE_ITEM_TYPE, $this->id_queue_item_type);
        }
        if ($this->isColumnModified(SpyQueueItemTypeTableMap::COL_KEY)) {
            $criteria->add(SpyQueueItemTypeTableMap::COL_KEY, $this->key);
        }
        if ($this->isColumnModified(SpyQueueItemTypeTableMap::COL_LAST_DISTRIBUTION)) {
            $criteria->add(SpyQueueItemTypeTableMap::COL_LAST_DISTRIBUTION, $this->last_distribution);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildSpyQueueItemTypeQuery::create();
        $criteria->add(SpyQueueItemTypeTableMap::COL_ID_QUEUE_ITEM_TYPE, $this->id_queue_item_type);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getIdQueueItemType();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getIdQueueItemType();
    }

    /**
     * Generic method to set the primary key (id_queue_item_type column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setIdQueueItemType($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getIdQueueItemType();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItemType (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setKey($this->getKey());
        $copyObj->setLastDistribution($this->getLastDistribution());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getSpyQueueItems() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSpyQueueItem($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setIdQueueItemType(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItemType Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('SpyQueueItem' == $relationName) {
            return $this->initSpyQueueItems();
        }
    }

    /**
     * Clears out the collSpyQueueItems collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addSpyQueueItems()
     */
    public function clearSpyQueueItems()
    {
        $this->collSpyQueueItems = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collSpyQueueItems collection loaded partially.
     */
    public function resetPartialSpyQueueItems($v = true)
    {
        $this->collSpyQueueItemsPartial = $v;
    }

    /**
     * Initializes the collSpyQueueItems collection.
     *
     * By default this just sets the collSpyQueueItems collection to an empty array (like clearcollSpyQueueItems());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSpyQueueItems($overrideExisting = true)
    {
        if (null !== $this->collSpyQueueItems && !$overrideExisting) {
            return;
        }
        $this->collSpyQueueItems = new ObjectCollection();
        $this->collSpyQueueItems->setModel('\SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItem');
    }

    /**
     * Gets an array of ChildSpyQueueItem objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildSpyQueueItemType is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildSpyQueueItem[] List of ChildSpyQueueItem objects
     * @throws PropelException
     */
    public function getSpyQueueItems(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collSpyQueueItemsPartial && !$this->isNew();
        if (null === $this->collSpyQueueItems || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSpyQueueItems) {
                // return empty collection
                $this->initSpyQueueItems();
            } else {
                $collSpyQueueItems = ChildSpyQueueItemQuery::create(null, $criteria)
                    ->filterBySpyQueueItemType($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collSpyQueueItemsPartial && count($collSpyQueueItems)) {
                        $this->initSpyQueueItems(false);

                        foreach ($collSpyQueueItems as $obj) {
                            if (false == $this->collSpyQueueItems->contains($obj)) {
                                $this->collSpyQueueItems->append($obj);
                            }
                        }

                        $this->collSpyQueueItemsPartial = true;
                    }

                    return $collSpyQueueItems;
                }

                if ($partial && $this->collSpyQueueItems) {
                    foreach ($this->collSpyQueueItems as $obj) {
                        if ($obj->isNew()) {
                            $collSpyQueueItems[] = $obj;
                        }
                    }
                }

                $this->collSpyQueueItems = $collSpyQueueItems;
                $this->collSpyQueueItemsPartial = false;
            }
        }

        return $this->collSpyQueueItems;
    }

    /**
     * Sets a collection of ChildSpyQueueItem objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $spyQueueItems A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildSpyQueueItemType The current object (for fluent API support)
     */
    public function setSpyQueueItems(Collection $spyQueueItems, ConnectionInterface $con = null)
    {
        /** @var ChildSpyQueueItem[] $spyQueueItemsToDelete */
        $spyQueueItemsToDelete = $this->getSpyQueueItems(new Criteria(), $con)->diff($spyQueueItems);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->spyQueueItemsScheduledForDeletion = clone $spyQueueItemsToDelete;

        foreach ($spyQueueItemsToDelete as $spyQueueItemRemoved) {
            $spyQueueItemRemoved->setSpyQueueItemType(null);
        }

        $this->collSpyQueueItems = null;
        foreach ($spyQueueItems as $spyQueueItem) {
            $this->addSpyQueueItem($spyQueueItem);
        }

        $this->collSpyQueueItems = $spyQueueItems;
        $this->collSpyQueueItemsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related SpyQueueItem objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related SpyQueueItem objects.
     * @throws PropelException
     */
    public function countSpyQueueItems(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collSpyQueueItemsPartial && !$this->isNew();
        if (null === $this->collSpyQueueItems || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSpyQueueItems) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSpyQueueItems());
            }

            $query = ChildSpyQueueItemQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySpyQueueItemType($this)
                ->count($con);
        }

        return count($this->collSpyQueueItems);
    }

    /**
     * Method called to associate a ChildSpyQueueItem object to this object
     * through the ChildSpyQueueItem foreign key attribute.
     *
     * @param  ChildSpyQueueItem $l ChildSpyQueueItem
     * @return $this|\SprykerFeature\Zed\QueueDistributor\Persistence\Propel\SpyQueueItemType The current object (for fluent API support)
     */
    public function addSpyQueueItem(ChildSpyQueueItem $l)
    {
        if ($this->collSpyQueueItems === null) {
            $this->initSpyQueueItems();
            $this->collSpyQueueItemsPartial = true;
        }

        if (!$this->collSpyQueueItems->contains($l)) {
            $this->doAddSpyQueueItem($l);
        }

        return $this;
    }

    /**
     * @param ChildSpyQueueItem $spyQueueItem The ChildSpyQueueItem object to add.
     */
    protected function doAddSpyQueueItem(ChildSpyQueueItem $spyQueueItem)
    {
        $this->collSpyQueueItems[]= $spyQueueItem;
        $spyQueueItem->setSpyQueueItemType($this);
    }

    /**
     * @param  ChildSpyQueueItem $spyQueueItem The ChildSpyQueueItem object to remove.
     * @return $this|ChildSpyQueueItemType The current object (for fluent API support)
     */
    public function removeSpyQueueItem(ChildSpyQueueItem $spyQueueItem)
    {
        if ($this->getSpyQueueItems()->contains($spyQueueItem)) {
            $pos = $this->collSpyQueueItems->search($spyQueueItem);
            $this->collSpyQueueItems->remove($pos);
            if (null === $this->spyQueueItemsScheduledForDeletion) {
                $this->spyQueueItemsScheduledForDeletion = clone $this->collSpyQueueItems;
                $this->spyQueueItemsScheduledForDeletion->clear();
            }
            $this->spyQueueItemsScheduledForDeletion[]= clone $spyQueueItem;
            $spyQueueItem->setSpyQueueItemType(null);
        }

        return $this;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id_queue_item_type = null;
        $this->key = null;
        $this->last_distribution = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collSpyQueueItems) {
                foreach ($this->collSpyQueueItems as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collSpyQueueItems = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(SpyQueueItemTypeTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {

    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
