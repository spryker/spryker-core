<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Kernel\ClassResolver\DependencyProvider;

use Spryker\Yves\Kernel\ClassResolver\DependencyProvider\DependencyProviderNotFoundException;
use Spryker\Yves\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver;
use SprykerTest\Yves\Kernel\ClassResolver\AbstractResolverTest;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Yves
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
    protected $projectClass = 'ProjectNamespace\\Yves\\Kernel\\ClassResolver\\KernelDependencyProvider';

    /**
     * @var string
     */
    protected $storeClass = 'ProjectNamespace\\Yves\\KernelDE\\ClassResolver\\KernelDependencyProvider';

    /**
     * @var string
     */
    protected $classPattern = '%namespace%\\Yves\\%bundle%%store%\\ClassResolver\\%bundle%DependencyProvider';

    /**
     * @var string
     */
    protected $expectedExceptionClass = DependencyProviderNotFoundException::class;

    /**
     * @param array $methods
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Yves\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver
     */
    protected function getResolverMock(array $methods)
    {
        $resolverMock = $this->getMockBuilder(DependencyProviderResolver::class)->setMethods($methods)->getMock();

        return $resolverMock;
    }
}
