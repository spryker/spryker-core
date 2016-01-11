<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Kernel\ClassResolver\QueryContainer;

use Spryker\Zed\Kernel\ClassResolver\DependencyProvider\DependencyProviderNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver;
use Unit\Spryker\Zed\Kernel\ClassResolver\AbstractResolverTest;

/**
 * @group Spryker
 * @group Zed
 * @group Kernel
 * @group DependencyProviderResolver
 */
class DependencyProviderResolverTest extends AbstractResolverTest
{

    protected $coreClass = 'Unit\\Spryker\\Zed\\Kernel\\ClassResolver\\Fixtures\\KernelDependencyProvider';
    protected $projectClass = 'Unit\\Pyz\\Zed\\Kernel\\ClassResolver\\Fixtures\\KernelDependencyProvider';
    protected $storeClass = 'Unit\\Pyz\\Zed\\KernelDE\\ClassResolver\\Fixtures\\KernelDependencyProvider';
    protected $classPattern = 'Unit\\%namespace%\\Zed\\%bundle%%store%\\ClassResolver\\Fixtures\\%bundle%DependencyProvider';
    protected $expectedExceptionClass = DependencyProviderNotFoundException::class;

    /**
     * @param array $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|DependencyProviderResolver
     */
    protected function getResolverMock(array $methods)
    {
        $resolverMock = $this->getMock(DependencyProviderResolver::class, $methods);

        return $resolverMock;
    }

}
