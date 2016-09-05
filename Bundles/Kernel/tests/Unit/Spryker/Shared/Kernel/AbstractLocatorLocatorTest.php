<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Kernel;

use Unit\Spryker\Shared\Kernel\Fixtures\LocatorLocator;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Kernel
 * @group AbstractLocatorLocatorTest
 */
class AbstractLocatorLocatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testMagicMethodCallShouldReturnBundleProxy()
    {
        $locator = LocatorLocator::getInstance();
        $bundleProxy = $locator->foo();

        $this->assertInstanceOf('Spryker\Shared\Kernel\BundleProxy', $bundleProxy);
    }

}
