<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Kernel\ClassResolver;

use Codeception\Test\Unit;
use Spryker\Shared\Kernel\ClassResolver\ClassInfo;
use Spryker\Shared\Kernel\ClassResolver\ModuleNameResolver;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Kernel
 * @group ClassResolver
 * @group ClassInfoTest
 * Add your own group annotations below this line
 */
class ClassInfoTest extends Unit
{
    /**
     * @return void
     */
    public function testGetBundleStripsStoreNameFromBundleName(): void
    {
        $classInfo = $this->getClassInfo('DE');

        $classInfo->setClass('\\ProjectNamespace\\Zed\CartDE\\Business\\CartFacade');

        $this->assertSame('Cart', $classInfo->getBundle());
    }

    /**
     * @param string $storeName
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Kernel\ClassResolver\ClassInfo
     */
    protected function getClassInfo(string $storeName): ClassInfo
    {
        $mock = $this
            ->getMockBuilder(ClassInfo::class)
            ->setMethods(['getBundleNameResolver'])
            ->getMock();

        $mock
            ->method('getBundleNameResolver')
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
}
