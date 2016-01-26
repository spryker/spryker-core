<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Kernel;

use Spryker\Shared\Kernel\BundleProxy;
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
     * @var Locator
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
