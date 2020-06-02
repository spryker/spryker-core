<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Kernel\ClassResolver;

use Codeception\Test\Unit;
use Spryker\Shared\Kernel\ClassResolver\ModuleNameResolver;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Kernel
 * @group ClassResolver
 * @group ModuleNameResolverTest
 * Add your own group annotations below this line
 */
class ModuleNameResolverTest extends Unit
{
    /**
     * @return void
     */
    public function testResolveShouldRemoveStoreName(): void
    {
        $moduleNameResolver = $this->getModuleNameResolver('TEST');
        $resolvedModuleName = $moduleNameResolver->resolve('CartTEST');

        $this->assertSame('Cart', $resolvedModuleName);
    }

    /**
     * @param string $codeBucket
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Kernel\ClassResolver\ModuleNameResolver
     */
    protected function getModuleNameResolver(string $codeBucket): ModuleNameResolver
    {
        $mock = $this
            ->getMockBuilder(ModuleNameResolver::class)
            ->onlyMethods(['getCodeBucket'])
            ->getMock();

        $mock
            ->method('getCodeBucket')
            ->will($this->returnValue($codeBucket));

        return $mock;
    }
}
