<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\Kernel\ClassResolver\QueryContainer;

use Spryker\Yves\Kernel\ClassResolver\DependencyProvider\DependencyProviderNotFoundException;
use Spryker\Yves\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver;
use Unit\Spryker\Yves\Kernel\ClassResolver\AbstractResolverTest;

/**
 * @group Spryker
 * @group Yves
 * @group Kernel
 * @group DependencyProviderResolver
 */
class DependencyProviderResolverTest extends AbstractResolverTest
{

    /**
     * @var string
     */
    protected $projectClass = 'Unit\\Pyz\\Yves\\Kernel\\ClassResolver\\Fixtures\\KernelDependencyProvider';

    /**
     * @var string
     */
    protected $storeClass = 'Unit\\Pyz\\Yves\\KernelDE\\ClassResolver\\Fixtures\\KernelDependencyProvider';

    /**
     * @var string
     */
    protected $classPattern = 'Unit\\%namespace%\\Yves\\%bundle%%store%\\ClassResolver\\Fixtures\\%bundle%DependencyProvider';

    /**
     * @var string
     */
    protected $expectedExceptionClass = DependencyProviderNotFoundException::class;

    /**
     * @param array $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Yves\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver
     */
    protected function getResolverMock(array $methods)
    {
        $resolverMock = $this->getMock(DependencyProviderResolver::class, $methods);

        return $resolverMock;
    }

}
