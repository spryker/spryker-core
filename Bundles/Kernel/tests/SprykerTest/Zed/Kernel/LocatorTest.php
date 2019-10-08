<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Kernel;

use Codeception\Test\Unit;
use Spryker\Shared\Kernel\BundleProxy;
use Spryker\Zed\Kernel\Locator;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Kernel
 * @group LocatorTest
 * Add your own group annotations below this line
 */
class LocatorTest extends Unit
{
    /**
     * @var \Spryker\Zed\Kernel\Locator
     */
    private $locator;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->locator = Locator::getInstance();
    }

    /**
     * @return void
     */
    public function testCallShouldReturnBundleProxy()
    {
        $this->assertInstanceOf(BundleProxy::class, $this->locator->foo());
    }
}
