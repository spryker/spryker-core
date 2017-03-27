<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\Kernel;

use PHPUnit_Framework_TestCase;
use Spryker\Yves\Kernel\BundleControllerAction;

/**
 * @group Unit
 * @group Spryker
 * @group Yves
 * @group Kernel
 * @group BundleControllerActionTest
 */
class BundleControllerActionTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testGetBundleShouldReturnBundleName()
    {
        $bundleControllerLocator = new BundleControllerAction('foo', 'bar', 'baz');

        $this->assertSame('foo', $bundleControllerLocator->getBundle());
    }

    /**
     * @return void
     */
    public function testGetBundleShouldStripStoreName()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|\Spryker\Yves\Kernel\BundleControllerAction $bundleControllerLocator */
        $bundleControllerLocator = $this
            ->getMockBuilder(BundleControllerAction::class)
            ->setMethods(['getStoreName'])
            ->setConstructorArgs(['fooDE', 'bar', 'baz'])
            ->getMock();
        $bundleControllerLocator
            ->method('getStoreName')
            ->will($this->returnValue('DE'));

        $this->assertSame('foo', $bundleControllerLocator->getBundle());
    }

    /**
     * @return void
     */
    public function testGetControllerShouldReturnControllerName()
    {
        $bundleControllerLocator = new BundleControllerAction('foo', 'bar', 'baz');

        $this->assertSame('bar', $bundleControllerLocator->getController());
    }

    /**
     * @return void
     */
    public function testGetActionShouldReturnActionName()
    {
        $bundleControllerLocator = new BundleControllerAction('foo', 'bar', 'baz');

        $this->assertSame('baz', $bundleControllerLocator->getAction());
    }

}
