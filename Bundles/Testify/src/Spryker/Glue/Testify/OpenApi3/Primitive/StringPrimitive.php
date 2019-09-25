<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3\Primitive;

class StringPrimitive extends AbstractPrimitive
{
    /**
     * @inheritDoc
     */
    protected function cast($value)
    {
        return (string)$value;
    }
}
