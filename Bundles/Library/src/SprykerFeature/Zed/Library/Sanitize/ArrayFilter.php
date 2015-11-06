<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Library\Sanitize;

class ArrayFilter implements \Iterator, \Countable, \ArrayAccess
{

    /**
     * @var array
     */
    protected $filters = [];

    /**
     * @var array
     */
    protected $array;

    /**
     * @param array $array
     * @param string|FilterSetInterface $filterSet
     */
    public function __construct(array $array, $filterSet)
    {
        $this->initFilters($filterSet);
        $this->array = $this->filter($array);
    }

    /**
     * @param string|FilterSetInterface $filterSet
     */
    protected function initFilters($filterSet)
    {
        if (is_string($filterSet) && class_exists($filterSet)) {
            /** @var FilterSetInterface $filterSetClass */
            $filterSetClass = new $filterSet();
            $this->filters = $filterSetClass->getFilters();
        } elseif ($filterSet instanceof FilterSetInterface) {
            $this->filters = $filterSet->getFilters();
        }
    }

    /**
     * @param array $array
     *
     * @return array
     */
    protected function filter(array $array)
    {
        if (empty($this->filters)) {
            return $array;
        }

        $result = $array;
        /** @var FilterInterface $filter */
        foreach ($this->filters as $filter) {
            $result = $filter->filter($result);
        }

        return $result;
    }

    /**
     * @param array $array
     * @param $filterSet
     *
     * @return self
     */
    public static function fromArray(array $array, $filterSet)
    {
        return new static($array, $filterSet);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->array;
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
        return current($this->array);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     *
     * @link http://php.net/manual/en/iterator.next.php
     */
    public function next()
    {
        next($this->array);
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
        return key($this->array);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     *
     * @link http://php.net/manual/en/iterator.valid.php
     *
     * @return bool The return value will be casted to boolean and then evaluated.
     *   Returns true on success or false on failure.
     */
    public function valid()
    {
        return $this->offsetExists($this->key());
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     *
     * @link http://php.net/manual/en/iterator.rewind.php
     */
    public function rewind()
    {
        reset($this->array);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     *
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *   An offset to check for.
     *   </p>
     *
     * @return bool true on success or false on failure.
     *   </p>
     *   <p>
     *   The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return isset($this->array[$offset]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     *
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset <p>
     *   The offset to retrieve.
     *   </p>
     *
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->array[$offset];
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     *
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     *   The offset to assign the value to.
     *   </p>
     * @param mixed $value <p>
     *   The value to set.
     *   </p>
     */
    public function offsetSet($offset, $value)
    {
        $array = $this->array;
        $array[$offset] = $value;
        $this->array = $this->filter($array);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     *
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset <p>
     *   The offset to unset.
     *   </p>
     */
    public function offsetUnset($offset)
    {
        unset($this->array[$offset]);
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     *
     * @link http://php.net/manual/en/countable.count.php
     *
     * @return int The custom count as an integer.
     *   </p>
     *   <p>
     *   The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->array);
    }

}
