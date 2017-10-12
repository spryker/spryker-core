<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Kernel\ClassResolver\Factory;

use Spryker\Zed\Kernel\ClassResolver\Factory\FactoryNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver;
use SprykerTest\Zed\Kernel\ClassResolver\AbstractResolverTest;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Kernel
 * @group ClassResolver
 * @group Factory
 * @group FactoryResolverTest
 * Add your own group annotations below this line
 */
class FactoryResolverTest extends AbstractResolverTest
{
    /**
     * @var string
     */
    protected $coreClass = 'Unit\\Spryker\\Zed\\Kernel\\ClassResolver\\Fixtures\\KernelFactory';

    /**
     * @var string
     */
    protected $projectClass = 'Unit\\ProjectNamespace\\Zed\\Kernel\\ClassResolver\\Fixtures\\KernelFactory';

    /**
     * @var string
     */
    protected $storeClass = 'Unit\\ProjectNamespace\\Zed\\KernelDE\\ClassResolver\\Fixtures\\KernelFactory';

    /**
     * @var string
     */
    protected $classPattern = 'Unit\\%namespace%\\Zed\\%bundle%%store%\\ClassResolver\\Fixtures\\%bundle%Factory';

    /**
     * @var string
     */
    protected $className = 'Spryker\Zed\Kernel\Business';

    /**
     * @var string
     */
    protected $unResolvableClassName = 'Spryker\Zed\UnResolvable\Business';

    /**
     * @var string
     */
    protected $expectedExceptionClass = FactoryNotFoundException::class;

    /**
     * @param array $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver
     */
    protected function getResolverMock(array $methods)
    {
        $resolverMock = $this->getMockBuilder(FactoryResolver::class)->setMethods($methods)->getMock();

        return $resolverMock;
    }
}
