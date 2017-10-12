<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\DataSet;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use Serializable;

interface DataSetInterface extends IteratorAggregate, ArrayAccess, Serializable, Countable
{
    /**
     * This exchanges the used array inside and will return the old one.
     * Do not use the returned value, this is the old one and not the new!
     *
     * Call this method and return the object instead.
     *
     * @param array $input
     *
     * @return array Returns the old array!
     */
    public function exchangeArray($input);

    /**
     * @return array
     */
    public function getArrayCopy();
}
