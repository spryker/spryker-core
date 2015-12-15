<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Shared\Kernel;

use Unit\Spryker\Shared\Kernel\Fixtures\LocatorLocator;

/**
 * @group Kernel
 * @group Locator
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
