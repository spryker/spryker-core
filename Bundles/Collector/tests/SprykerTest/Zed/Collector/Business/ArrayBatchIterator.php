<?php

namespace SprykerTest\Zed\Collector\Business;

use Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface;

class ArrayBatchIterator implements CountableIteratorInterface
{
    /**
     * @var mixed[]
     */
    protected $batch;

    /**
     * @var int
     */
    protected $currentElement = 0;

    /**
     * @param mixed[] $batch
     */
    public function __construct(array $batch)
    {
        $this->batch = $batch;
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return current($this->batch);
    }

    /**
     * @return void
     */
    public function next()
    {
        next($this->batch);
        $this->currentElement++;
    }

    /**
     * @return int|string|null Scalar on success, or null on failure.
     */
    public function key()
    {
        return key($this->batch);
    }

    /**
     * @return boolean
     */
    public function valid()
    {
        return ($this->currentElement + 1 <= $this->count());
    }

    /**
     * @return void
     */
    public function rewind()
    {
        reset($this->batch);
        $this->currentElement = 0;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->batch);
    }
}
