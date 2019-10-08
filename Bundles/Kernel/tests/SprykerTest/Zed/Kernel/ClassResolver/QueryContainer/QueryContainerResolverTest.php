<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Kernel\ClassResolver\QueryContainer;

use Spryker\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerResolver;
use SprykerTest\Zed\Kernel\ClassResolver\AbstractResolverTest;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Kernel
 * @group ClassResolver
 * @group QueryContainer
 * @group QueryContainerResolverTest
 * Add your own group annotations below this line
 */
class QueryContainerResolverTest extends AbstractResolverTest
{
    /**
     * @var string
     */
    protected $coreClass = 'Spryker\\Zed\\Kernel\\ClassResolver\\KernelQueryContainer';

    /**
     * @var string
     */
    protected $projectClass = 'ProjectNamespace\\Zed\\Kernel\\ClassResolver\\KernelQueryContainer';

    /**
     * @var string
     */
    protected $storeClass = 'ProjectNamespace\\Zed\\KernelDE\\ClassResolver\\KernelQueryContainer';

    /**
     * @var string
     */
    protected $classPattern = '%namespace%\\Zed\\%bundle%%store%\\ClassResolver\\%bundle%QueryContainer';

    /**
     * @var string
     */
    protected $expectedExceptionClass = QueryContainerNotFoundException::class;

    /**
     * @param array $methods
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerResolver
     */
    protected function getResolverMock(array $methods)
    {
        $resolverMock = $this->getMockBuilder(QueryContainerResolver::class)->setMethods($methods)->getMock();

        return $resolverMock;
    }
}
