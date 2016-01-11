<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Kernel\ClassResolver\QueryContainer;

use Spryker\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerResolver;
use Unit\Spryker\Zed\Kernel\ClassResolver\AbstractResolverTest;

/**
 * @group Spryker
 * @group Zed
 * @group Kernel
 * @group QueryContainerResolver
 */
class QueryContainerResolverTest extends AbstractResolverTest
{

    const CORE_CLASS_NAME = 'Unit\\Spryker\\Zed\\Kernel\\ClassResolver\\Fixtures\\KernelQueryContainer';
    const PROJECT_CLASS_NAME = 'Unit\\Pyz\\Zed\\Kernel\\ClassResolver\\Fixtures\\KernelQueryContainer';
    const STORE_CLASS_NAME = 'Unit\\Pyz\\Zed\\KernelDE\\ClassResolver\\Fixtures\\KernelQueryContainer';
    const CLASS_PATTERN = 'Unit\\%namespace%\\Zed\\%bundle%%store%\\ClassResolver\\Fixtures\\%bundle%QueryContainer';

    public function testResolveMustThrowExceptionIfClassCanNotBeResolved()
    {
        $this->setExpectedException(QueryContainerNotFoundException::class);

        $providerMock = $this->getResolverMock(['canResolve']);
        $providerMock->method('canResolve')
            ->willReturn(false);

        $providerMock->resolve(self::UNRESOLVABLE_CLASS);
    }

    public function testResolveMustReturnCoreClass()
    {
        $this->createClass(self::CORE_CLASS_NAME);

        $providerMock = $this->getResolverMock(['getClassPattern']);
        $providerMock->method('getClassPattern')
            ->willReturn(self::CLASS_PATTERN);

        $resolved = $providerMock->resolve('Kernel');
        $this->assertInstanceOf(self::CORE_CLASS_NAME, $resolved);
    }

    public function testResolveMustReturnProjectClass()
    {
        $this->createClass(self::CORE_CLASS_NAME);
        $this->createClass(self::PROJECT_CLASS_NAME);

        $providerMock = $this->getResolverMock(['getClassPattern']);
        $providerMock->method('getClassPattern')
            ->willReturn(self::CLASS_PATTERN);

        $resolved = $providerMock->resolve('Kernel');
        $this->assertInstanceOf(self::PROJECT_CLASS_NAME, $resolved);
    }

    public function testResolveMustReturnStoreClass()
    {
        $this->createClass(self::PROJECT_CLASS_NAME);
        $this->createClass(self::STORE_CLASS_NAME);

        $providerMock = $this->getResolverMock(['getClassPattern']);
        $providerMock->method('getClassPattern')
            ->willReturn(self::CLASS_PATTERN);

        $resolved = $providerMock->resolve('Kernel');
        $this->assertInstanceOf(self::STORE_CLASS_NAME, $resolved);
    }

    /**
     * @param array $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|QueryContainerResolver
     */
    protected function getResolverMock(array $methods)
    {
        $resolverMock = $this->getMock(QueryContainerResolver::class, $methods);

        return $resolverMock;
    }

}
