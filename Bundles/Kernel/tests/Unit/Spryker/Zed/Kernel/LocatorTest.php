<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Kernel;

use PHPUnit_Framework_TestCase;
use Spryker\Shared\Kernel\BundleProxy;
use Spryker\Zed\Kernel\Locator;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Kernel
 * @group LocatorTest
 */
class LocatorTest extends PHPUnit_Framework_TestCase
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
