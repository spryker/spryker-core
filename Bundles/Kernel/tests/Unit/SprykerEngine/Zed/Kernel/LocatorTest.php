<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel;

use SprykerEngine\Zed\Kernel\Locator;

/**
 * @group SprykerEngine
 * @group Zed
 * @group Kernel
 * @group Locator
 */
class LocatorTest extends \PHPUnit_Framework_TestCase
{

    public function testCallShouldReturnBundleProxy()
    {
        $locator = Locator::getInstance();

        $this->assertInstanceOf('SprykerEngine\Shared\Kernel\BundleProxy', $locator->foo());
    }

    public function testGetInstanceWithLocatorAsArgumentShouldReturnLocator()
    {
        $locator = Locator::getInstance([
            new \Unit\SprykerEngine\Shared\Kernel\Fixtures\Locator('Foo'),
        ]);

        $this->assertInstanceOf('SprykerEngine\Zed\Kernel\Locator', $locator);
    }

}
