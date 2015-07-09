<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace YvesUnit\SprykerEngine\Yves\Kernel;

use SprykerEngine\Yves\Kernel\Locator;

/**
 * @group SprykerEngine
 * @group Yves
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
