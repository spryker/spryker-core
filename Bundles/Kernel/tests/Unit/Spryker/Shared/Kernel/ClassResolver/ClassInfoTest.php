<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Kernel\ClassResolver;

use Codeception\TestCase\Test;
use Spryker\Shared\Kernel\ClassResolver\ClassInfo;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Kernel
 * @group ClassResolver
 * @group ClassInfoTest
 */
class ClassInfoTest extends Test
{

    /**
     * @return void
     */
    public function testGetBundleStripsStoreNameFromBundleName()
    {
        $classInfo = $this->getClassInfo('DE');

        $classInfo->setClass('\\Pyz\\Zed\CartDE\\Business\\CartFacade');

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
            ->setMethods(['getStoreName'])
            ->getMock();

        $mock
            ->method('getStoreName')
            ->will($this->returnValue($storeName));

        return $mock;
    }

}
