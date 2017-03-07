<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Kernel\ClassResolver\QueryContainer;

use Spryker\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerResolver;
use Unit\Spryker\Zed\Kernel\ClassResolver\AbstractResolverTest;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Kernel
 * @group ClassResolver
 * @group QueryContainer
 * @group QueryContainerResolverTest
 */
class QueryContainerResolverTest extends AbstractResolverTest
{

    /**
     * @var string
     */
    protected $coreClass = 'Unit\\Spryker\\Zed\\Kernel\\ClassResolver\\Fixtures\\KernelQueryContainer';

    /**
     * @var string
     */
    protected $projectClass = 'Unit\\ProjectNamespace\\Zed\\Kernel\\ClassResolver\\Fixtures\\KernelQueryContainer';

    /**
     * @var string
     */
    protected $storeClass = 'Unit\\ProjectNamespace\\Zed\\KernelDE\\ClassResolver\\Fixtures\\KernelQueryContainer';

    /**
     * @var string
     */
    protected $classPattern = 'Unit\\%namespace%\\Zed\\%bundle%%store%\\ClassResolver\\Fixtures\\%bundle%QueryContainer';

    /**
     * @var string
     */
    protected $expectedExceptionClass = QueryContainerNotFoundException::class;

    /**
     * @param array $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerResolver
     */
    protected function getResolverMock(array $methods)
    {
        $resolverMock = $this->getMockBuilder(QueryContainerResolver::class)->setMethods($methods)->getMock();

        return $resolverMock;
    }

}
