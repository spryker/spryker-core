<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\FrontendExporter\Business\Model;

class PdoStatementIterator implements \Iterator, \Countable
{

    /**
     * @var \PDOStatement
     */
    protected $statement;

    /**
     * @var int
     */
    protected $count;

    /**
     * @var mixed
     */
    protected $currentRow;

    /**
     * @var int
     */
    protected $currentKey;

    /**
     * @var bool
     */
    protected $isValid = true;

    /**
     * @param \PDOStatement $statement
     */
    public function __construct(\PDOStatement $statement)
    {
        $this->statement = $statement;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     *
     * @link http://php.net/manual/en/iterator.current.php
     *
     * @return mixed Can return any type.
     */
    public function current()
    {
        return $this->currentRow;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     *
     * @link http://php.net/manual/en/iterator.next.php
     */
    public function next()
    {
        $this->currentRow = $this->statement->fetch(\PDO::FETCH_ASSOC);
        $this->currentKey++;
        $this->isValid = (boolean) $this->currentRow;

        if (!$this->isValid) {
            $this->statement->closeCursor();
        }
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     *
     * @link http://php.net/manual/en/iterator.key.php
     *
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        $this->currentKey;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     *
     * @link http://php.net/manual/en/iterator.valid.php
     *
     * @return bool The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return $this->isValid;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     *
     * @link http://php.net/manual/en/iterator.rewind.php
     */
    public function rewind()
    {
        $this->next();
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     *
     * @link http://php.net/manual/en/countable.count.php
     *
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        if (is_null($this->count)) {
            $this->count = $this->statement->rowCount();
        }

        return $this->count;
    }

}
