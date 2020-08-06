<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Kernel;

use Codeception\Test\Unit;
use Spryker\Shared\Kernel\ClassResolver\ModuleNameResolver;
use Spryker\Yves\Kernel\BundleControllerAction;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Kernel
 * @group BundleControllerActionTest
 * Add your own group annotations below this line
 */
class BundleControllerActionTest extends Unit
{
    /**
     * @return void
     */
    public function testGetBundleShouldReturnBundleName(): void
    {
        $bundleControllerAction = new BundleControllerAction('foo', 'bar', 'baz');

        $this->assertSame('foo', $bundleControllerAction->getBundle());
    }

    /**
     * @return void
     */
    public function testGetBundleShouldStripStoreName(): void
    {
        $bundleControllerAction = $this->getBundleControllerAction('fooDE', 'bar', 'baz', 'DE');

        $this->assertSame('foo', $bundleControllerAction->getBundle());
    }

    /**
     * @param string $bundle
     * @param string $controller
     * @param string $action
     * @param string $storeName
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Yves\Kernel\BundleControllerAction
     */
    protected function getBundleControllerAction(string $bundle, string $controller, string $action, string $storeName): BundleControllerAction
    {
        $mock = $this
            ->getMockBuilder(BundleControllerAction::class)
            ->setMethods(['getModuleNameResolver'])
            ->setConstructorArgs([$bundle, $controller, $action])
            ->getMock();

        $mock
            ->method('getModuleNameResolver')
            ->will($this->returnValue($this->getModuleNameResolverMock($storeName)));

        return $mock;
    }

    /**
     * @param string $storeName
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Kernel\ClassResolver\ModuleNameResolver
     */
    protected function getModuleNameResolverMock(string $storeName): ModuleNameResolver
    {
        $mock = $this
            ->getMockBuilder(ModuleNameResolver::class)
            ->setMethods(['getStoreName'])
            ->getMock();

        $mock
            ->method('getStoreName')
            ->will($this->returnValue($storeName));

        return $mock;
    }

    /**
     * @return void
     */
    public function testGetControllerShouldReturnControllerName(): void
    {
        $bundleControllerAction = new BundleControllerAction('foo', 'bar', 'baz');

        $this->assertSame('bar', $bundleControllerAction->getController());
    }

    /**
     * @return void
     */
    public function testGetActionShouldReturnActionName(): void
    {
        $bundleControllerAction = new BundleControllerAction('foo', 'bar', 'baz');

        $this->assertSame('baz', $bundleControllerAction->getAction());
    }
}
