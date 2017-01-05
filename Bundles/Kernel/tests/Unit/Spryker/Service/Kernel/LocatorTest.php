<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Service\Kernel;

use PHPUnit_Framework_TestCase;
use Spryker\Service\Kernel\Locator;
use Spryker\Shared\Kernel\BundleProxy;

/**
 * @group Unit
 * @group Spryker
 * @group Service
 * @group Kernel
 * @group LocatorTest
 */
class LocatorTest extends PHPUnit_Framework_TestCase
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
