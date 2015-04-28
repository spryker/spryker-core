<?php

namespace SprykerFeature\Zed\Payone\Persistence\Propel\Base;

use \DateTime;
use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLog as ChildSpyPaymentPayoneTransactionStatusLog;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLogQuery as ChildSpyPaymentPayoneTransactionStatusLogQuery;
use SprykerFeature\Zed\Payone\Persistence\Propel\Map\SpyPaymentPayoneTransactionStatusLogTableMap;

/**
 * Base class that represents a row from the 'spy_payment_payone_transaction_status_log' table.
 *
 *
 *
* @package    propel.generator.vendor.spryker.payone.src.SprykerFeature.Zed.Payone.Persistence.Propel.Base
*/
abstract class SpyPaymentPayoneTransactionStatusLog implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\SprykerFeature\\Zed\\Payone\\Persistence\\Propel\\Map\\SpyPaymentPayoneTransactionStatusLogTableMap';


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
     * The value for the id_payment_payone_transaction_status_log field.
     * @var        int
     */
    protected $id_payment_payone_transaction_status_log;

    /**
     * The value for the transaction_id field.
     * @var        int
     */
    protected $transaction_id;

    /**
     * The value for the reference_id field.
     * @var        int
     */
    protected $reference_id;

    /**
     * The value for the mode field.
     * @var        string
     */
    protected $mode;

    /**
     * The value for the status field.
     * @var        string
     */
    protected $status;

    /**
     * The value for the transaction_time field.
     * @var        \DateTime
     */
    protected $transaction_time;

    /**
     * The value for the sequence_number field.
     * @var        int
     */
    protected $sequence_number;

    /**
     * The value for the clearing_type field.
     * @var        string
     */
    protected $clearing_type;

    /**
     * The value for the portal_id field.
     * @var        string
     */
    protected $portal_id;

    /**
     * The value for the balance field.
     * @var        int
     */
    protected $balance;

    /**
     * The value for the receivable field.
     * @var        int
     */
    protected $receivable;

    /**
     * The value for the created_at field.
     * @var        \DateTime
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     * @var        \DateTime
     */
    protected $updated_at;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * Initializes internal state of SprykerFeature\Zed\Payone\Persistence\Propel\Base\SpyPaymentPayoneTransactionStatusLog object.
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
     * Compares this with another <code>SpyPaymentPayoneTransactionStatusLog</code> instance.  If
     * <code>obj</code> is an instance of <code>SpyPaymentPayoneTransactionStatusLog</code>, delegates to
     * <code>equals(SpyPaymentPayoneTransactionStatusLog)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|SpyPaymentPayoneTransactionStatusLog The current object, for fluid interface
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
     * Get the [id_payment_payone_transaction_status_log] column value.
     *
     * @return int
     */
    public function getIdPaymentPayoneTransactionStatusLog()
    {
        return $this->id_payment_payone_transaction_status_log;
    }

    /**
     * Get the [transaction_id] column value.
     *
     * @return int
     */
    public function getTransactionId()
    {
        return $this->transaction_id;
    }

    /**
     * Get the [reference_id] column value.
     *
     * @return int
     */
    public function getReferenceId()
    {
        return $this->reference_id;
    }

    /**
     * Get the [mode] column value.
     *
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Get the [status] column value.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get the [optionally formatted] temporal [transaction_time] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getTransactionTime($format = NULL)
    {
        if ($format === null) {
            return $this->transaction_time;
        } else {
            return $this->transaction_time instanceof \DateTime ? $this->transaction_time->format($format) : null;
        }
    }

    /**
     * Get the [sequence_number] column value.
     *
     * @return int
     */
    public function getSequenceNumber()
    {
        return $this->sequence_number;
    }

    /**
     * Get the [clearing_type] column value.
     *
     * @return string
     */
    public function getClearingType()
    {
        return $this->clearing_type;
    }

    /**
     * Get the [portal_id] column value.
     *
     * @return string
     */
    public function getPortalId()
    {
        return $this->portal_id;
    }

    /**
     * Get the [balance] column value.
     *
     * @return int
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Get the [receivable] column value.
     *
     * @return int
     */
    public function getReceivable()
    {
        return $this->receivable;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->created_at;
        } else {
            return $this->created_at instanceof \DateTime ? $this->created_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->updated_at;
        } else {
            return $this->updated_at instanceof \DateTime ? $this->updated_at->format($format) : null;
        }
    }

    /**
     * Set the value of [id_payment_payone_transaction_status_log] column.
     *
     * @param  int $v new value
     * @return $this|\SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLog The current object (for fluent API support)
     */
    public function setIdPaymentPayoneTransactionStatusLog($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id_payment_payone_transaction_status_log !== $v) {
            $this->id_payment_payone_transaction_status_log = $v;
            $this->modifiedColumns[SpyPaymentPayoneTransactionStatusLogTableMap::COL_ID_PAYMENT_PAYONE_TRANSACTION_STATUS_LOG] = true;
        }

        return $this;
    } // setIdPaymentPayoneTransactionStatusLog()

    /**
     * Set the value of [transaction_id] column.
     *
     * @param  int $v new value
     * @return $this|\SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLog The current object (for fluent API support)
     */
    public function setTransactionId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->transaction_id !== $v) {
            $this->transaction_id = $v;
            $this->modifiedColumns[SpyPaymentPayoneTransactionStatusLogTableMap::COL_TRANSACTION_ID] = true;
        }

        return $this;
    } // setTransactionId()

    /**
     * Set the value of [reference_id] column.
     *
     * @param  int $v new value
     * @return $this|\SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLog The current object (for fluent API support)
     */
    public function setReferenceId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->reference_id !== $v) {
            $this->reference_id = $v;
            $this->modifiedColumns[SpyPaymentPayoneTransactionStatusLogTableMap::COL_REFERENCE_ID] = true;
        }

        return $this;
    } // setReferenceId()

    /**
     * Set the value of [mode] column.
     *
     * @param  string $v new value
     * @return $this|\SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLog The current object (for fluent API support)
     */
    public function setMode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->mode !== $v) {
            $this->mode = $v;
            $this->modifiedColumns[SpyPaymentPayoneTransactionStatusLogTableMap::COL_MODE] = true;
        }

        return $this;
    } // setMode()

    /**
     * Set the value of [status] column.
     *
     * @param  string $v new value
     * @return $this|\SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLog The current object (for fluent API support)
     */
    public function setStatus($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->status !== $v) {
            $this->status = $v;
            $this->modifiedColumns[SpyPaymentPayoneTransactionStatusLogTableMap::COL_STATUS] = true;
        }

        return $this;
    } // setStatus()

    /**
     * Sets the value of [transaction_time] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLog The current object (for fluent API support)
     */
    public function setTransactionTime($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->transaction_time !== null || $dt !== null) {
            if ($dt !== $this->transaction_time) {
                $this->transaction_time = $dt;
                $this->modifiedColumns[SpyPaymentPayoneTransactionStatusLogTableMap::COL_TRANSACTION_TIME] = true;
            }
        } // if either are not null

        return $this;
    } // setTransactionTime()

    /**
     * Set the value of [sequence_number] column.
     *
     * @param  int $v new value
     * @return $this|\SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLog The current object (for fluent API support)
     */
    public function setSequenceNumber($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->sequence_number !== $v) {
            $this->sequence_number = $v;
            $this->modifiedColumns[SpyPaymentPayoneTransactionStatusLogTableMap::COL_SEQUENCE_NUMBER] = true;
        }

        return $this;
    } // setSequenceNumber()

    /**
     * Set the value of [clearing_type] column.
     *
     * @param  string $v new value
     * @return $this|\SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLog The current object (for fluent API support)
     */
    public function setClearingType($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->clearing_type !== $v) {
            $this->clearing_type = $v;
            $this->modifiedColumns[SpyPaymentPayoneTransactionStatusLogTableMap::COL_CLEARING_TYPE] = true;
        }

        return $this;
    } // setClearingType()

    /**
     * Set the value of [portal_id] column.
     *
     * @param  string $v new value
     * @return $this|\SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLog The current object (for fluent API support)
     */
    public function setPortalId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->portal_id !== $v) {
            $this->portal_id = $v;
            $this->modifiedColumns[SpyPaymentPayoneTransactionStatusLogTableMap::COL_PORTAL_ID] = true;
        }

        return $this;
    } // setPortalId()

    /**
     * Set the value of [balance] column.
     *
     * @param  int $v new value
     * @return $this|\SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLog The current object (for fluent API support)
     */
    public function setBalance($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->balance !== $v) {
            $this->balance = $v;
            $this->modifiedColumns[SpyPaymentPayoneTransactionStatusLogTableMap::COL_BALANCE] = true;
        }

        return $this;
    } // setBalance()

    /**
     * Set the value of [receivable] column.
     *
     * @param  int $v new value
     * @return $this|\SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLog The current object (for fluent API support)
     */
    public function setReceivable($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->receivable !== $v) {
            $this->receivable = $v;
            $this->modifiedColumns[SpyPaymentPayoneTransactionStatusLogTableMap::COL_RECEIVABLE] = true;
        }

        return $this;
    } // setReceivable()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLog The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($dt !== $this->created_at) {
                $this->created_at = $dt;
                $this->modifiedColumns[SpyPaymentPayoneTransactionStatusLogTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLog The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($dt !== $this->updated_at) {
                $this->updated_at = $dt;
                $this->modifiedColumns[SpyPaymentPayoneTransactionStatusLogTableMap::COL_UPDATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setUpdatedAt()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : SpyPaymentPayoneTransactionStatusLogTableMap::translateFieldName('IdPaymentPayoneTransactionStatusLog', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id_payment_payone_transaction_status_log = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : SpyPaymentPayoneTransactionStatusLogTableMap::translateFieldName('TransactionId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->transaction_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : SpyPaymentPayoneTransactionStatusLogTableMap::translateFieldName('ReferenceId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->reference_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : SpyPaymentPayoneTransactionStatusLogTableMap::translateFieldName('Mode', TableMap::TYPE_PHPNAME, $indexType)];
            $this->mode = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : SpyPaymentPayoneTransactionStatusLogTableMap::translateFieldName('Status', TableMap::TYPE_PHPNAME, $indexType)];
            $this->status = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : SpyPaymentPayoneTransactionStatusLogTableMap::translateFieldName('TransactionTime', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->transaction_time = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : SpyPaymentPayoneTransactionStatusLogTableMap::translateFieldName('SequenceNumber', TableMap::TYPE_PHPNAME, $indexType)];
            $this->sequence_number = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : SpyPaymentPayoneTransactionStatusLogTableMap::translateFieldName('ClearingType', TableMap::TYPE_PHPNAME, $indexType)];
            $this->clearing_type = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : SpyPaymentPayoneTransactionStatusLogTableMap::translateFieldName('PortalId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->portal_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : SpyPaymentPayoneTransactionStatusLogTableMap::translateFieldName('Balance', TableMap::TYPE_PHPNAME, $indexType)];
            $this->balance = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : SpyPaymentPayoneTransactionStatusLogTableMap::translateFieldName('Receivable', TableMap::TYPE_PHPNAME, $indexType)];
            $this->receivable = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : SpyPaymentPayoneTransactionStatusLogTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : SpyPaymentPayoneTransactionStatusLogTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 13; // 13 = SpyPaymentPayoneTransactionStatusLogTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\SprykerFeature\\Zed\\Payone\\Persistence\\Propel\\SpyPaymentPayoneTransactionStatusLog'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(SpyPaymentPayoneTransactionStatusLogTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildSpyPaymentPayoneTransactionStatusLogQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see SpyPaymentPayoneTransactionStatusLog::setDeleted()
     * @see SpyPaymentPayoneTransactionStatusLog::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(SpyPaymentPayoneTransactionStatusLogTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildSpyPaymentPayoneTransactionStatusLogQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(SpyPaymentPayoneTransactionStatusLogTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $isInsert = $this->isNew();
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior

                if (!$this->isColumnModified(SpyPaymentPayoneTransactionStatusLogTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(SpyPaymentPayoneTransactionStatusLogTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(SpyPaymentPayoneTransactionStatusLogTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                SpyPaymentPayoneTransactionStatusLogTableMap::addInstanceToPool($this);
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

        $this->modifiedColumns[SpyPaymentPayoneTransactionStatusLogTableMap::COL_ID_PAYMENT_PAYONE_TRANSACTION_STATUS_LOG] = true;
        if (null !== $this->id_payment_payone_transaction_status_log) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . SpyPaymentPayoneTransactionStatusLogTableMap::COL_ID_PAYMENT_PAYONE_TRANSACTION_STATUS_LOG . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(SpyPaymentPayoneTransactionStatusLogTableMap::COL_ID_PAYMENT_PAYONE_TRANSACTION_STATUS_LOG)) {
            $modifiedColumns[':p' . $index++]  = '`id_payment_payone_transaction_status_log`';
        }
        if ($this->isColumnModified(SpyPaymentPayoneTransactionStatusLogTableMap::COL_TRANSACTION_ID)) {
            $modifiedColumns[':p' . $index++]  = '`transaction_id`';
        }
        if ($this->isColumnModified(SpyPaymentPayoneTransactionStatusLogTableMap::COL_REFERENCE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`reference_id`';
        }
        if ($this->isColumnModified(SpyPaymentPayoneTransactionStatusLogTableMap::COL_MODE)) {
            $modifiedColumns[':p' . $index++]  = '`mode`';
        }
        if ($this->isColumnModified(SpyPaymentPayoneTransactionStatusLogTableMap::COL_STATUS)) {
            $modifiedColumns[':p' . $index++]  = '`status`';
        }
        if ($this->isColumnModified(SpyPaymentPayoneTransactionStatusLogTableMap::COL_TRANSACTION_TIME)) {
            $modifiedColumns[':p' . $index++]  = '`transaction_time`';
        }
        if ($this->isColumnModified(SpyPaymentPayoneTransactionStatusLogTableMap::COL_SEQUENCE_NUMBER)) {
            $modifiedColumns[':p' . $index++]  = '`sequence_number`';
        }
        if ($this->isColumnModified(SpyPaymentPayoneTransactionStatusLogTableMap::COL_CLEARING_TYPE)) {
            $modifiedColumns[':p' . $index++]  = '`clearing_type`';
        }
        if ($this->isColumnModified(SpyPaymentPayoneTransactionStatusLogTableMap::COL_PORTAL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`portal_id`';
        }
        if ($this->isColumnModified(SpyPaymentPayoneTransactionStatusLogTableMap::COL_BALANCE)) {
            $modifiedColumns[':p' . $index++]  = '`balance`';
        }
        if ($this->isColumnModified(SpyPaymentPayoneTransactionStatusLogTableMap::COL_RECEIVABLE)) {
            $modifiedColumns[':p' . $index++]  = '`receivable`';
        }
        if ($this->isColumnModified(SpyPaymentPayoneTransactionStatusLogTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(SpyPaymentPayoneTransactionStatusLogTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `spy_payment_payone_transaction_status_log` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id_payment_payone_transaction_status_log`':
                        $stmt->bindValue($identifier, $this->id_payment_payone_transaction_status_log, PDO::PARAM_INT);
                        break;
                    case '`transaction_id`':
                        $stmt->bindValue($identifier, $this->transaction_id, PDO::PARAM_INT);
                        break;
                    case '`reference_id`':
                        $stmt->bindValue($identifier, $this->reference_id, PDO::PARAM_INT);
                        break;
                    case '`mode`':
                        $stmt->bindValue($identifier, $this->mode, PDO::PARAM_STR);
                        break;
                    case '`status`':
                        $stmt->bindValue($identifier, $this->status, PDO::PARAM_STR);
                        break;
                    case '`transaction_time`':
                        $stmt->bindValue($identifier, $this->transaction_time ? $this->transaction_time->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case '`sequence_number`':
                        $stmt->bindValue($identifier, $this->sequence_number, PDO::PARAM_INT);
                        break;
                    case '`clearing_type`':
                        $stmt->bindValue($identifier, $this->clearing_type, PDO::PARAM_STR);
                        break;
                    case '`portal_id`':
                        $stmt->bindValue($identifier, $this->portal_id, PDO::PARAM_STR);
                        break;
                    case '`balance`':
                        $stmt->bindValue($identifier, $this->balance, PDO::PARAM_INT);
                        break;
                    case '`receivable`':
                        $stmt->bindValue($identifier, $this->receivable, PDO::PARAM_INT);
                        break;
                    case '`created_at`':
                        $stmt->bindValue($identifier, $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case '`updated_at`':
                        $stmt->bindValue($identifier, $this->updated_at ? $this->updated_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
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
        $this->setIdPaymentPayoneTransactionStatusLog($pk);

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
        $pos = SpyPaymentPayoneTransactionStatusLogTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getIdPaymentPayoneTransactionStatusLog();
                break;
            case 1:
                return $this->getTransactionId();
                break;
            case 2:
                return $this->getReferenceId();
                break;
            case 3:
                return $this->getMode();
                break;
            case 4:
                return $this->getStatus();
                break;
            case 5:
                return $this->getTransactionTime();
                break;
            case 6:
                return $this->getSequenceNumber();
                break;
            case 7:
                return $this->getClearingType();
                break;
            case 8:
                return $this->getPortalId();
                break;
            case 9:
                return $this->getBalance();
                break;
            case 10:
                return $this->getReceivable();
                break;
            case 11:
                return $this->getCreatedAt();
                break;
            case 12:
                return $this->getUpdatedAt();
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
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_FIELDNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array())
    {

        if (isset($alreadyDumpedObjects['SpyPaymentPayoneTransactionStatusLog'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['SpyPaymentPayoneTransactionStatusLog'][$this->hashCode()] = true;
        $keys = SpyPaymentPayoneTransactionStatusLogTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getIdPaymentPayoneTransactionStatusLog(),
            $keys[1] => $this->getTransactionId(),
            $keys[2] => $this->getReferenceId(),
            $keys[3] => $this->getMode(),
            $keys[4] => $this->getStatus(),
            $keys[5] => $this->getTransactionTime(),
            $keys[6] => $this->getSequenceNumber(),
            $keys[7] => $this->getClearingType(),
            $keys[8] => $this->getPortalId(),
            $keys[9] => $this->getBalance(),
            $keys[10] => $this->getReceivable(),
            $keys[11] => $this->getCreatedAt(),
            $keys[12] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
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
     * @return $this|\SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLog
     */
    public function setByName($name, $value, $type = TableMap::TYPE_FIELDNAME)
    {
        $pos = SpyPaymentPayoneTransactionStatusLogTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLog
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setIdPaymentPayoneTransactionStatusLog($value);
                break;
            case 1:
                $this->setTransactionId($value);
                break;
            case 2:
                $this->setReferenceId($value);
                break;
            case 3:
                $this->setMode($value);
                break;
            case 4:
                $this->setStatus($value);
                break;
            case 5:
                $this->setTransactionTime($value);
                break;
            case 6:
                $this->setSequenceNumber($value);
                break;
            case 7:
                $this->setClearingType($value);
                break;
            case 8:
                $this->setPortalId($value);
                break;
            case 9:
                $this->setBalance($value);
                break;
            case 10:
                $this->setReceivable($value);
                break;
            case 11:
                $this->setCreatedAt($value);
                break;
            case 12:
                $this->setUpdatedAt($value);
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
        $keys = SpyPaymentPayoneTransactionStatusLogTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setIdPaymentPayoneTransactionStatusLog($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setTransactionId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setReferenceId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setMode($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setStatus($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setTransactionTime($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setSequenceNumber($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setClearingType($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setPortalId($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setBalance($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setReceivable($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setCreatedAt($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setUpdatedAt($arr[$keys[12]]);
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
     * @return $this|\SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLog The current object, for fluid interface
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
        $criteria = new Criteria(SpyPaymentPayoneTransactionStatusLogTableMap::DATABASE_NAME);

        if ($this->isColumnModified(SpyPaymentPayoneTransactionStatusLogTableMap::COL_ID_PAYMENT_PAYONE_TRANSACTION_STATUS_LOG)) {
            $criteria->add(SpyPaymentPayoneTransactionStatusLogTableMap::COL_ID_PAYMENT_PAYONE_TRANSACTION_STATUS_LOG, $this->id_payment_payone_transaction_status_log);
        }
        if ($this->isColumnModified(SpyPaymentPayoneTransactionStatusLogTableMap::COL_TRANSACTION_ID)) {
            $criteria->add(SpyPaymentPayoneTransactionStatusLogTableMap::COL_TRANSACTION_ID, $this->transaction_id);
        }
        if ($this->isColumnModified(SpyPaymentPayoneTransactionStatusLogTableMap::COL_REFERENCE_ID)) {
            $criteria->add(SpyPaymentPayoneTransactionStatusLogTableMap::COL_REFERENCE_ID, $this->reference_id);
        }
        if ($this->isColumnModified(SpyPaymentPayoneTransactionStatusLogTableMap::COL_MODE)) {
            $criteria->add(SpyPaymentPayoneTransactionStatusLogTableMap::COL_MODE, $this->mode);
        }
        if ($this->isColumnModified(SpyPaymentPayoneTransactionStatusLogTableMap::COL_STATUS)) {
            $criteria->add(SpyPaymentPayoneTransactionStatusLogTableMap::COL_STATUS, $this->status);
        }
        if ($this->isColumnModified(SpyPaymentPayoneTransactionStatusLogTableMap::COL_TRANSACTION_TIME)) {
            $criteria->add(SpyPaymentPayoneTransactionStatusLogTableMap::COL_TRANSACTION_TIME, $this->transaction_time);
        }
        if ($this->isColumnModified(SpyPaymentPayoneTransactionStatusLogTableMap::COL_SEQUENCE_NUMBER)) {
            $criteria->add(SpyPaymentPayoneTransactionStatusLogTableMap::COL_SEQUENCE_NUMBER, $this->sequence_number);
        }
        if ($this->isColumnModified(SpyPaymentPayoneTransactionStatusLogTableMap::COL_CLEARING_TYPE)) {
            $criteria->add(SpyPaymentPayoneTransactionStatusLogTableMap::COL_CLEARING_TYPE, $this->clearing_type);
        }
        if ($this->isColumnModified(SpyPaymentPayoneTransactionStatusLogTableMap::COL_PORTAL_ID)) {
            $criteria->add(SpyPaymentPayoneTransactionStatusLogTableMap::COL_PORTAL_ID, $this->portal_id);
        }
        if ($this->isColumnModified(SpyPaymentPayoneTransactionStatusLogTableMap::COL_BALANCE)) {
            $criteria->add(SpyPaymentPayoneTransactionStatusLogTableMap::COL_BALANCE, $this->balance);
        }
        if ($this->isColumnModified(SpyPaymentPayoneTransactionStatusLogTableMap::COL_RECEIVABLE)) {
            $criteria->add(SpyPaymentPayoneTransactionStatusLogTableMap::COL_RECEIVABLE, $this->receivable);
        }
        if ($this->isColumnModified(SpyPaymentPayoneTransactionStatusLogTableMap::COL_CREATED_AT)) {
            $criteria->add(SpyPaymentPayoneTransactionStatusLogTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(SpyPaymentPayoneTransactionStatusLogTableMap::COL_UPDATED_AT)) {
            $criteria->add(SpyPaymentPayoneTransactionStatusLogTableMap::COL_UPDATED_AT, $this->updated_at);
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
        $criteria = ChildSpyPaymentPayoneTransactionStatusLogQuery::create();
        $criteria->add(SpyPaymentPayoneTransactionStatusLogTableMap::COL_ID_PAYMENT_PAYONE_TRANSACTION_STATUS_LOG, $this->id_payment_payone_transaction_status_log);

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
        $validPk = null !== $this->getIdPaymentPayoneTransactionStatusLog();

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
        return $this->getIdPaymentPayoneTransactionStatusLog();
    }

    /**
     * Generic method to set the primary key (id_payment_payone_transaction_status_log column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setIdPaymentPayoneTransactionStatusLog($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getIdPaymentPayoneTransactionStatusLog();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLog (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setTransactionId($this->getTransactionId());
        $copyObj->setReferenceId($this->getReferenceId());
        $copyObj->setMode($this->getMode());
        $copyObj->setStatus($this->getStatus());
        $copyObj->setTransactionTime($this->getTransactionTime());
        $copyObj->setSequenceNumber($this->getSequenceNumber());
        $copyObj->setClearingType($this->getClearingType());
        $copyObj->setPortalId($this->getPortalId());
        $copyObj->setBalance($this->getBalance());
        $copyObj->setReceivable($this->getReceivable());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setIdPaymentPayoneTransactionStatusLog(NULL); // this is a auto-increment column, so set to default value
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
     * @return \SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLog Clone of current object.
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
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id_payment_payone_transaction_status_log = null;
        $this->transaction_id = null;
        $this->reference_id = null;
        $this->mode = null;
        $this->status = null;
        $this->transaction_time = null;
        $this->sequence_number = null;
        $this->clearing_type = null;
        $this->portal_id = null;
        $this->balance = null;
        $this->receivable = null;
        $this->created_at = null;
        $this->updated_at = null;
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
        } // if ($deep)

    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(SpyPaymentPayoneTransactionStatusLogTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildSpyPaymentPayoneTransactionStatusLog The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[SpyPaymentPayoneTransactionStatusLogTableMap::COL_UPDATED_AT] = true;

        return $this;
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
