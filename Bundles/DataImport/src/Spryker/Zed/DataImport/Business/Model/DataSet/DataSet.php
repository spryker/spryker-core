<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\DataSet;

use ArrayObject;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;

class DataSet implements DataSetInterface
{
    /**
     * @var \ArrayObject
     */
    protected $dataSet;

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->dataSet = new ArrayObject($data);
    }

    /**
     * @param string|int $index
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException
     *
     * @return string|int|array|float|bool
     */
    public function offsetGet($index)
    {
        if (!$this->offsetExists($index)) {
            throw new DataKeyNotFoundInDataSetException(sprintf('The key "%s" was not found in data set. Available keys: "%s"', $index, implode(', ', array_keys($this->getArrayCopy()))));
        }

        return $this->dataSet->offsetGet($index);
    }

    /**
     * @param string|int $index
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException
     *
     * @return void
     */
    public function offsetUnset($index)
    {
        if (!$this->offsetExists($index)) {
            throw new DataKeyNotFoundInDataSetException(sprintf('The key "%s" was not found in data set. Available keys: "%s"', $index, implode(', ', array_keys($this->getArrayCopy()))));
        }

        $this->dataSet->offsetUnset($index);
    }

    /**
     * @return \ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        return $this->dataSet->getIterator();
    }

    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->dataSet->offsetExists($offset);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->dataSet->offsetSet($offset, $value);
    }

    /**
     * @return string|null
     */
    public function serialize()
    {
        return $this->dataSet->serialize();
    }

    /**
     * @param string $serialized
     *
     * @return void
     */
    public function unserialize($serialized)
    {
        $this->dataSet->unserialize($serialized);
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->dataSet->count();
    }

    /**
     * @param array $input
     *
     * @return array
     */
    public function exchangeArray($input)
    {
        return $this->dataSet->exchangeArray($input);
    }

    /**
     * @return array
     */
    public function getArrayCopy()
    {
        return $this->dataSet->getArrayCopy();
    }
}
