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
    public function testGetBundleStripsStoreNameFromModuleName(): void
    {
        $classInfo = $this->getClassInfo('TEST');

        $classInfo->setClass('\\ProjectNamespace\\Zed\CartTEST\\Business\\CartFacade');

        $this->assertSame('Cart', $classInfo->getModule());
    }

    /**
     * @param string $codeBucket
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Kernel\ClassResolver\ClassInfo
     */
    protected function getClassInfo(string $codeBucket): ClassInfo
    {
        $mock = $this
            ->getMockBuilder(ClassInfo::class)
            ->setMethods(['getModuleNameResolver'])
            ->getMock();

        $mock
            ->method('getModuleNameResolver')
            ->will($this->returnValue($this->getModuleNameResolverMock($codeBucket)));

        return $mock;
    }

    /**
     * @param string $codeBucket
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Kernel\ClassResolver\ModuleNameResolver
     */
    protected function getModuleNameResolverMock(string $codeBucket): ModuleNameResolver
    {
        $mock = $this
            ->getMockBuilder(ModuleNameResolver::class)
            ->setMethods(['getCodeBucket'])
            ->getMock();

        $mock
            ->method('getCodeBucket')
            ->will($this->returnValue($codeBucket));

        return $mock;
    }
}
