<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Kernel;

use Spryker\Zed\Kernel\Locator;

/**
 * @group Spryker
 * @group Zed
 * @group Kernel
 * @group Locator
 */
class LocatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testCallShouldReturnBundleProxy()
    {
        $locator = Locator::getInstance();

        $this->assertInstanceOf('Spryker\Shared\Kernel\BundleProxy', $locator->foo());
    }

    /**
     * @return void
     */
    public function testGetInstanceWithLocatorAsArgumentShouldReturnLocator()
    {
        $locator = Locator::getInstance([
            new \Unit\Spryker\Shared\Kernel\Fixtures\Locator('Foo'),
        ]);

        $this->assertInstanceOf('Spryker\Zed\Kernel\Locator', $locator);
    }

}
