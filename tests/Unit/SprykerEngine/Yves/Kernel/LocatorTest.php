<?php

namespace Unit\SprykerEngine\Yves\Kernel;

use SprykerEngine\Yves\Kernel\Locator;

/**
 * @group Kernel
 * @group Locator
 */
class LocatorTest extends \PHPUnit_Framework_TestCase
{

    public function testCallShouldReturnBundleProxy()
    {
        $locator = Locator::getInstance();

        $this->assertInstanceOf('SprykerEngine\Shared\Kernel\BundleProxy', $locator->locateFoo());
    }
}
