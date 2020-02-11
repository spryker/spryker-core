<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

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
    protected $currentElementIndex = 0;

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
    public function next(): void
    {
        next($this->batch);
        $this->currentElementIndex++;
    }

    /**
     * @return int|string|null Scalar on success, or null on failure.
     */
    public function key()
    {
        return key($this->batch);
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return ($this->currentElementIndex + 1 <= $this->count());
    }

    /**
     * @return void
     */
    public function rewind(): void
    {
        reset($this->batch);
        $this->currentElementIndex = 0;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->batch);
    }
}
