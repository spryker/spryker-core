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

    const CORE_CLASS_NAME = 'Unit\\Spryker\\Zed\\Kernel\\ClassResolver\\Fixtures\\KernelFactory';
    const PROJECT_CLASS_NAME = 'Unit\\Pyz\\Zed\\Kernel\\ClassResolver\\Fixtures\\KernelFactory';
    const STORE_CLASS_NAME = 'Unit\\Pyz\\Zed\\KernelDE\\ClassResolver\\Fixtures\\KernelFactory';
    const CLASS_PATTERN = 'Unit\\%namespace%\\Zed\\%bundle%%store%\\ClassResolver\\Fixtures\\%bundle%Factory';

    public function testResolveMustThrowExceptionIfClassCanNotBeResolved()
    {
        $this->setExpectedException(FactoryNotFoundException::class);

        $queryContainerMock = $this->getResolverMock(['canResolve']);
        $queryContainerMock->method('canResolve')
            ->willReturn(false);

        $queryContainerMock->resolve('Spryker\Zed\Unresolvable\Business');
    }

    public function testResolveMustReturnCoreClass()
    {
        $this->createClass(self::CORE_CLASS_NAME);

        $resolverMock = $this->getResolverMock(['getClassPattern']);
        $resolverMock->method('getClassPattern')
            ->willReturn(self::CLASS_PATTERN);

        $resolved = $resolverMock->resolve('Spryker\Zed\Kernel\Business');
        $this->assertInstanceOf(self::CORE_CLASS_NAME, $resolved);
    }

    public function testResolveMustReturnProjectClass()
    {
        $this->createClass(self::CORE_CLASS_NAME);
        $this->createClass(self::PROJECT_CLASS_NAME);

        $resolverMock = $this->getResolverMock(['getClassPattern']);
        $resolverMock->method('getClassPattern')
            ->willReturn(self::CLASS_PATTERN);

        $resolved = $resolverMock->resolve('Spryker\Zed\Kernel\Business');
        $this->assertInstanceOf(self::PROJECT_CLASS_NAME, $resolved);
    }

    public function testResolveMustReturnStoreClass()
    {
        $this->createClass(self::PROJECT_CLASS_NAME);
        $this->createClass(self::STORE_CLASS_NAME);

        $resolverMock = $this->getResolverMock(['getClassPattern']);
        $resolverMock->method('getClassPattern')
            ->willReturn(self::CLASS_PATTERN);

        $resolved = $resolverMock->resolve('Spryker\Zed\Kernel\Business');
        $this->assertInstanceOf(self::STORE_CLASS_NAME, $resolved);
    }

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
