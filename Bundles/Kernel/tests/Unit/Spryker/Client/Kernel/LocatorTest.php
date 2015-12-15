<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Client\Kernel;

use Spryker\Client\Kernel\Locator;

/**
 * @group Spryker
 * @group Client
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
        $injectedLocator = Locator::getInstance();
        $locator = Locator::getInstance([$injectedLocator]);

        $this->assertInstanceOf('Spryker\Client\Kernel\Locator', $locator);
    }

}
