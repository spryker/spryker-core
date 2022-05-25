<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Util;

use ArrayObject;
use LogicException;

/**
 * @extends \ArrayObject<int|string, mixed>
 */
class ReadOnlyArrayObject extends ArrayObject
{
    /**
     * @param mixed $value
     *
     * @throws \LogicException
     *
     * @return void
     */
    public function append($value): void
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
    public function exchangeArray($input): array
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
    public function offsetSet($index, $newval): void
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
    public function offsetUnset($index): void
    {
        throw new LogicException('Attempting to write to an immutable array');
    }
}
