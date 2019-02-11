<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3\Primitive;

class FloatPrimitive extends AbstractPrimitive
{
    /**
     * @inheritdoc
     */
    protected function cast($value)
    {
        return (float)$value;
    }
}
