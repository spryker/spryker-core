<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Kernel\ClassResolver\QueryContainer;

use Spryker\Zed\Kernel\ClassResolver\Config\BundleConfigNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\Config\BundleConfigResolver;
use Unit\Spryker\Zed\Kernel\ClassResolver\AbstractResolverTest;

/**
 * @group Spryker
 * @group Zed
 * @group Kernel
 * @group ConfigResolver
 */
class ConfigResolverTest extends AbstractResolverTest
{

    const CORE_CLASS_NAME = 'Unit\\Spryker\\Zed\\Kernel\\ClassResolver\\Fixtures\\KernelConfig';
    const PROJECT_CLASS_NAME = 'Unit\\Pyz\\Zed\\Kernel\\ClassResolver\\Fixtures\\KernelConfig';
    const STORE_CLASS_NAME = 'Unit\\Pyz\\Zed\\KernelDE\\ClassResolver\\Fixtures\\KernelConfig';
    const CLASS_PATTERN = 'Unit\\%namespace%\\Zed\\%bundle%%store%\\ClassResolver\\Fixtures\\%bundle%Config';

    public function testResolveMustThrowExceptionIfClassCanNotBeResolved()
    {
        $this->setExpectedException(BundleConfigNotFoundException::class);

        $queryContainerMock = $this->getResolverMock(['canResolve']);
        $queryContainerMock->method('canResolve')
            ->willReturn(false);

        $queryContainerMock->resolve(self::UNRESOLVABLE_CLASS);
    }

    public function testResolveMustReturnCoreClass()
    {
        $this->createClass(self::CORE_CLASS_NAME);

        $resolverMock = $this->getResolverMock(['getClassPattern']);
        $resolverMock->method('getClassPattern')
            ->willReturn(self::CLASS_PATTERN);

        $resolved = $resolverMock->resolve('Kernel');
        $this->assertInstanceOf(self::CORE_CLASS_NAME, $resolved);
    }

    public function testResolveMustReturnProjectClass()
    {
        $this->createClass(self::CORE_CLASS_NAME);
        $this->createClass(self::PROJECT_CLASS_NAME);

        $resolverMock = $this->getResolverMock(['getClassPattern']);
        $resolverMock->method('getClassPattern')
            ->willReturn(self::CLASS_PATTERN);

        $resolved = $resolverMock->resolve('Kernel');
        $this->assertInstanceOf(self::PROJECT_CLASS_NAME, $resolved);
    }

    public function testResolveMustReturnStoreClass()
    {
        $this->createClass(self::PROJECT_CLASS_NAME);
        $this->createClass(self::STORE_CLASS_NAME);

        $resolverMock = $this->getResolverMock(['getClassPattern']);
        $resolverMock->method('getClassPattern')
            ->willReturn(self::CLASS_PATTERN);

        $resolved = $resolverMock->resolve('Kernel');
        $this->assertInstanceOf(self::STORE_CLASS_NAME, $resolved);
    }

    /**
     * @param array $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|BundleConfigResolver
     */
    protected function getResolverMock(array $methods)
    {
        $resolverMock = $this->getMock(BundleConfigResolver::class, $methods);

        return $resolverMock;
    }

}
