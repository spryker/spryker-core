<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Shared\Kernel;

use Unit\SprykerEngine\Shared\Kernel\Fixtures\LocatorLocator;

/**
 * @group Kernel
 * @group Locator
 */
class AbstractLocatorLocatorTest extends \PHPUnit_Framework_TestCase
{

    public function testMagicMethodCallShouldReturnBundleProxy()
    {
        $locator = LocatorLocator::getInstance();
        $bundleProxy = $locator->foo();

        $this->assertInstanceOf('SprykerEngine\Shared\Kernel\BundleProxy', $bundleProxy);
    }

}
