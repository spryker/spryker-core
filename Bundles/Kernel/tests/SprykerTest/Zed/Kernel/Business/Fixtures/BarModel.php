<?php

/**
 * (c) Spryker Systems GmbH copyright protected
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
