<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Kernel\ClassResolver\DependencyProvider;

use Spryker\Service\Kernel\ClassResolver\DependencyProvider\DependencyProviderNotFoundException;
use Spryker\Service\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver;
use SprykerTest\Service\Kernel\ClassResolver\AbstractResolverTest;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group Kernel
 * @group ClassResolver
 * @group DependencyProvider
 * @group DependencyProviderResolverTest
 * Add your own group annotations below this line
 */
class DependencyProviderResolverTest extends AbstractResolverTest
{
    /**
     * @var string
     */
    protected $projectClass = 'ProjectNamespace\\Service\\Kernel\\ClassResolver\\KernelDependencyProvider';

    /**
     * @var string
     */
    protected $storeClass = 'ProjectNamespace\\Service\\KernelDE\\ClassResolver\\KernelDependencyProvider';

    /**
     * @var string
     */
    protected $classPattern = '%namespace%\\Service\\%bundle%%store%\\ClassResolver\\%bundle%DependencyProvider';

    /**
     * @var string
     */
    protected $expectedExceptionClass = DependencyProviderNotFoundException::class;

    /**
     * @param array $methods
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Service\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver
     */
    protected function getResolverMock(array $methods)
    {
        $resolverMock = $this->getMockBuilder(DependencyProviderResolver::class)->setMethods($methods)->getMock();

        return $resolverMock;
    }
}
