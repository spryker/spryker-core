<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Testify\OpenApi3\Stub;

use Spryker\Glue\Testify\OpenApi3\Primitive\AbstractPrimitive;

class Bar extends AbstractPrimitive
{
    /**
     * @inheritDoc
     */
    protected function cast($value)
    {
        return (string)$value;
    }
}
