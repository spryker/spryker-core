<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\DataSet;

use ArrayObject;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;

class DataSet extends ArrayObject implements DataSetInterface
{
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

        return parent::offsetGet($index);
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

        parent::offsetUnset($index);
    }
}
