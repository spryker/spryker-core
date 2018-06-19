<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Kernel\Business\Fixtures;

class BarModel
{
    /**
     * @param \SprykerTest\Zed\Kernel\Business\Fixtures\FooModel $fooModel
     */
    public function __construct(FooModel $fooModel)
    {
        unset($fooModel);
    }
}
