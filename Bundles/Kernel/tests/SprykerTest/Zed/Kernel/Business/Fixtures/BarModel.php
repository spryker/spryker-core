<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerTest\Zed\Kernel\Business\Fixtures;

class BarModel
{

    /**
     * @param FooModel $fooModel
     */
    public function __construct(FooModel $fooModel)
    {
        unset($fooModel);
    }

}
