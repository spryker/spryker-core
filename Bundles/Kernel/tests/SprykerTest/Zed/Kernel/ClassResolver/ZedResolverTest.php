<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Kernel\ClassResolver;

use Codeception\Test\Unit;
use Spryker\Zed\Kernel\ClassResolver\Business\BusinessFactoryNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\Business\BusinessFactoryResolver;
use Spryker\Zed\Kernel\ClassResolver\Communication\CommunicationFactoryNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\Communication\CommunicationFactoryResolver;
use Spryker\Zed\Kernel\ClassResolver\Config\BundleConfigNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\Config\BundleConfigResolver;
use Spryker\Zed\Kernel\ClassResolver\DependencyProvider\DependencyProviderNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver;
use Spryker\Zed\Kernel\ClassResolver\EntityManager\EntityManagerNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\EntityManager\EntityManagerResolver;
use Spryker\Zed\Kernel\ClassResolver\Facade\FacadeNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\Facade\FacadeResolver;
use Spryker\Zed\Kernel\ClassResolver\Persistence\PersistenceFactoryNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\Persistence\PersistenceFactoryResolver;
use Spryker\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerResolver;
use Spryker\Zed\Kernel\ClassResolver\Repository\RepositoryNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\Repository\RepositoryResolver;
use SprykerTest\Shared\Kernel\Helper\ClassResolverHelper;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Kernel
 * @group ClassResolver
 * @group ZedResolverTest
 * Add your own group annotations below this line
 */
class ZedResolverTest extends Unit
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
            [BusinessFactoryResolver::class],
            [CommunicationFactoryResolver::class],
            [BundleConfigResolver::class],
            [DependencyProviderResolver::class],
            [EntityManagerResolver::class],
            [FacadeResolver::class],
            [PersistenceFactoryResolver::class],
            [QueryContainerResolver::class],
            [RepositoryResolver::class],
        ];
    }

    /**
     * @return string[][]
     */
    public function resolverExceptionDataProvider(): array
    {
        return [
            [BusinessFactoryResolver::class, BusinessFactoryNotFoundException::class],
            [CommunicationFactoryResolver::class, CommunicationFactoryNotFoundException::class],
            [BundleConfigResolver::class, BundleConfigNotFoundException::class],
            [DependencyProviderResolver::class, DependencyProviderNotFoundException::class],
            [EntityManagerResolver::class, EntityManagerNotFoundException::class],
            [FacadeResolver::class, FacadeNotFoundException::class],
            [PersistenceFactoryResolver::class, PersistenceFactoryNotFoundException::class],
            [QueryContainerResolver::class, QueryContainerNotFoundException::class],
            [RepositoryResolver::class, RepositoryNotFoundException::class],
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
