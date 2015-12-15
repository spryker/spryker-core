<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace YvesUnit\Spryker\Yves\Kernel;

use Spryker\Yves\Kernel\Locator;

/**
 * @group Spryker
 * @group Yves
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

        $this->assertInstanceOf('Spryker\Shared\Kernel\BundleProxy', $locator->locateFoo());
    }

}
