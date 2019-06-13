<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Util;

use ArrayObject;
use LogicException;

class ReadOnlyArrayObject extends ArrayObject
{
    /**
     * @param mixed $value
     *
     * @throws \LogicException
     *
     * @return void
     */
    public function append($value)
    {
        throw new LogicException('Attempting to write to an immutable array');
    }

    /**
     * @param mixed $input
     *
     * @throws \LogicException
     *
     * @return array
     */
    public function exchangeArray($input)
    {
        throw new LogicException('Attempting to write to an immutable array');
    }

    /**
     * @param mixed $index
     * @param mixed $newval
     *
     * @throws \LogicException
     *
     * @return void
     */
    public function offsetSet($index, $newval)
    {
        throw new LogicException('Attempting to write to an immutable array');
    }

    /**
     * @param mixed $index
     *
     * @throws \LogicException
     *
     * @return void
     */
    public function offsetUnset($index)
    {
        throw new LogicException('Attempting to write to an immutable array');
    }
}
