<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Kernel\ClassResolver;

use Codeception\Test\Unit;
use Spryker\Shared\Kernel\ClassResolver\Config\SharedConfigNotFoundException;
use Spryker\Shared\Kernel\ClassResolver\Config\SharedConfigResolver;
use Spryker\Shared\Kernel\ClassResolver\Factory\SharedFactoryNotFoundException;
use Spryker\Shared\Kernel\ClassResolver\Factory\SharedFactoryResolver;
use SprykerTest\Shared\Kernel\Helper\ClassResolverHelper;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Kernel
 * @group ClassResolver
 * @group SharedResolverTest
 * Add your own group annotations below this line
 */
class SharedResolverTest extends Unit
{
    /**
     * @var \SprykerTest\Shared\Kernel\KernelSharedTester
     */
    protected $tester;

    /**
     * @return string[][]
     */
    public function resolverDataProvider(): array
    {
        return [
            [SharedConfigResolver::class],
            [SharedFactoryResolver::class],
        ];
    }

    /**
     * @return string[][]
     */
    public function resolverExceptionDataProvider(): array
    {
        return [
            [SharedConfigResolver::class, SharedConfigNotFoundException::class],
            [SharedFactoryResolver::class, SharedFactoryNotFoundException::class],
        ];
    }

    /**
     * @dataProvider resolverExceptionDataProvider
     *
     * @param string $resolverClassName
     * @param string $resolverExceptionClassName
     *
     * @return void
     */
    public function testResolveThrowsExceptionWhenClassNotFound(string $resolverClassName, string $resolverExceptionClassName): void
    {
        $this->expectException($resolverExceptionClassName);
        /** @var \Spryker\Shared\Kernel\ClassResolver\AbstractClassResolver $resolver */
        $resolver = new $resolverClassName();

        $resolver->resolve('NotExistingModule');
    }

    /**
     * @dataProvider resolverDataProvider
     *
     * @param string $resolverClassName
     *
     * @return void
     */
    public function testResolveResolvesCoreClass(string $resolverClassName): void
    {
        $this->tester->createCoreClass($resolverClassName);

        $resolvedInstance = $this->tester->getResolver($resolverClassName)->resolve(ClassResolverHelper::MODULE_NAME);

        $this->assertSame($this->tester->getCoreClassName($resolverClassName), get_class($resolvedInstance));
    }

    /**
     * @dataProvider resolverDataProvider
     *
     * @param string $resolverClassName
     *
     * @return void
     */
    public function testResolveResolvesProjectClass(string $resolverClassName): void
    {
        $this->tester->createProjectClass($resolverClassName);

        $resolvedInstance = $this->tester->getResolver($resolverClassName)->resolve(ClassResolverHelper::MODULE_NAME);

        $this->assertSame($this->tester->getProjectClassName($resolverClassName), get_class($resolvedInstance));
    }

    /**
     * @dataProvider resolverDataProvider
     *
     * @param string $resolverClassName
     *
     * @return void
     */
    public function testResolveResolvesStoreClass(string $resolverClassName): void
    {
        $this->tester->createProjectStoreClass($resolverClassName);

        $resolvedInstance = $this->tester->getResolver($resolverClassName)->resolve(ClassResolverHelper::MODULE_NAME);

        $this->assertSame($this->tester->getProjectStoreClassName($resolverClassName), get_class($resolvedInstance));
    }
}
