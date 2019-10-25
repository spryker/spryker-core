<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Kernel;

use Codeception\Test\Unit;
use Spryker\Shared\Kernel\BundleProxy;
use Spryker\Yves\Kernel\Locator;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Kernel
 * @group LocatorTest
 * Add your own group annotations below this line
 */
class LocatorTest extends Unit
{
    /**
     * @return void
     */
    public function testCallShouldReturnBundleProxy()
    {
        $locator = Locator::getInstance();

        $this->assertInstanceOf(BundleProxy::class, $locator->locateFoo());
    }
}
