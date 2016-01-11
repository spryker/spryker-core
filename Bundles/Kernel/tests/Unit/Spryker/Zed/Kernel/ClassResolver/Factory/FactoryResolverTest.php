<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Kernel\ClassResolver\QueryContainer;

use Spryker\Zed\Kernel\ClassResolver\Factory\FactoryNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver;
use Unit\Spryker\Zed\Kernel\ClassResolver\AbstractResolverTest;

/**
 * @group Spryker
 * @group Zed
 * @group Kernel
 * @group FactoryResolver
 */
class FactoryResolverTest extends AbstractResolverTest
{

    protected $coreClass = 'Unit\\Spryker\\Zed\\Kernel\\ClassResolver\\Fixtures\\KernelFactory';
    protected $projectClass = 'Unit\\Pyz\\Zed\\Kernel\\ClassResolver\\Fixtures\\KernelFactory';
    protected $storeClass = 'Unit\\Pyz\\Zed\\KernelDE\\ClassResolver\\Fixtures\\KernelFactory';
    protected $classPattern = 'Unit\\%namespace%\\Zed\\%bundle%%store%\\ClassResolver\\Fixtures\\%bundle%Factory';
    protected $className = 'Spryker\Zed\Kernel\Business';
    protected $unResolvableClassName = 'Spryker\Zed\UnResolvable\Business';
    protected $expectedExceptionClass = FactoryNotFoundException::class;

    /**
     * @param array $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|FactoryResolver
     */
    protected function getResolverMock(array $methods)
    {
        $resolverMock = $this->getMock(FactoryResolver::class, $methods);

        return $resolverMock;
    }

}
