<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Kernel\ClassResolver;

use Codeception\Test\Unit;
use Spryker\Shared\Kernel\ClassResolver\BundleNameResolver;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Shared
 * @group Kernel
 * @group ClassResolver
 * @group BundleNameResolverTest
 * Add your own group annotations below this line
 */
class BundleNameResolverTest extends Unit
{
    /**
     * @return void
     */
    public function testResolveShouldRemoveStoreName()
    {
        $bundleNameResolver = $this->getBundleNameResolver('DE');
        $resolvedBundleName = $bundleNameResolver->resolve('CartDE');

        $this->assertSame('Cart', $resolvedBundleName);
    }

    /**
     * @param string $storeName
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Kernel\ClassResolver\BundleNameResolver
     */
    protected function getBundleNameResolver($storeName)
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
