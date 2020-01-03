<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Testify\OpenApi3\Primitive;

class IntPrimitive extends AbstractPrimitive
{
    /**
     * @inheritDoc
     */
    protected function cast($value)
    {
        return (int)$value;
    }
}
