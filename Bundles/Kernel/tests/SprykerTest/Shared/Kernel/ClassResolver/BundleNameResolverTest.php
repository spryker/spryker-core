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
 *
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
    public function testResolveShouldRemoveCodeBucketName(): void
    {
        $bundleNameResolver = $this->getBundleNameResolver('TEST');
        $resolvedBundleName = $bundleNameResolver->resolve('CartTEST');

        $this->assertSame('Cart', $resolvedBundleName);
    }

    /**
     * @param string $codeBucket
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Kernel\ClassResolver\BundleNameResolver
     */
    protected function getBundleNameResolver(string $codeBucket): BundleNameResolver
    {
        $mock = $this
            ->getMockBuilder(BundleNameResolver::class)
            ->setMethods(['getCodeBucket'])
            ->getMock();

        $mock
            ->method('getCodeBucket')
            ->will($this->returnValue($codeBucket));

        return $mock;
    }
}
