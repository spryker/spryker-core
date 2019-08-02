<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Glue\Testify\OpenApi3\Stub;

use Spryker\Glue\Testify\OpenApi3\Primitive\AbstractPrimitive;

class Bar extends AbstractPrimitive
{
    /**
     * @inheritdoc
     */
    protected function cast($value)
    {
        return (string)$value;
    }
}
