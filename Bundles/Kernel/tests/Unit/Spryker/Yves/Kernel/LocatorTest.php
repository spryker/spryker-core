<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\Kernel;

use Spryker\Yves\Kernel\Locator;

/**
 * @group Spryker
 * @group Yves
 * @group Kernel
 * @group Locator
 */
class LocatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testCallShouldReturnBundleProxy()
    {
        $locator = Locator::getInstance();

        $this->assertInstanceOf('Spryker\Shared\Kernel\BundleProxy', $locator->locateFoo());
    }

}
