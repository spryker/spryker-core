<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Kernel;

use Codeception\Test\Unit;
use Spryker\Shared\Kernel\BundleProxy;
use SprykerTest\Shared\Kernel\Fixtures\LocatorLocator;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Kernel
 * @group AbstractLocatorLocatorTest
 * Add your own group annotations below this line
 */
class AbstractLocatorLocatorTest extends Unit
{
    /**
     * @return void
     */
    public function testMagicMethodCallShouldReturnBundleProxy(): void
    {
        $locator = LocatorLocator::getInstance();
        $bundleProxy = $locator->foo();

        $this->assertInstanceOf(BundleProxy::class, $bundleProxy);
    }
}
