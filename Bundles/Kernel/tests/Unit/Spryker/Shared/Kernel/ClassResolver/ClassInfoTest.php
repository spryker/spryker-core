<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Kernel\ClassResolver;

use Codeception\Test\Unit;
use Spryker\Shared\Kernel\ClassResolver\BundleNameResolver;
use Spryker\Shared\Kernel\ClassResolver\ClassInfo;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Kernel
 * @group ClassResolver
 * @group ClassInfoTest
 */
class ClassInfoTest extends Unit
{

    /**
     * @return void
     */
    public function testGetBundleStripsStoreNameFromBundleName()
    {
        $classInfo = $this->getClassInfo('DE');

        $classInfo->setClass('\\ProjectNamespace\\Zed\CartDE\\Business\\CartFacade');

        $this->assertSame('Cart', $classInfo->getBundle());
    }

    /**
     * @param string $storeName
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Shared\Kernel\ClassResolver\ClassInfo
     */
    protected function getClassInfo($storeName)
    {
        $mock = $this
            ->getMockBuilder(ClassInfo::class)
            ->setMethods(['getBundleNameResolver'])
            ->getMock();

        $mock
            ->method('getBundleNameResolver')
            ->will($this->returnValue($this->getBundleNameResolverMock($storeName)));

        return $mock;
    }

    /**
     * @param string $storeName
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Shared\Kernel\ClassResolver\BundleNameResolver
     */
    protected function getBundleNameResolverMock($storeName)
    {
        $mock = $this
            ->getMockBuilder(BundleNameResolver::class)
            ->setMethods(['getStoreName'])
            ->getMock();

        $mock
            ->method('getStoreName')
            ->will($this->returnValue($storeName));

        return $mock;
    }

}
