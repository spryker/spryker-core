<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Shared\Kernel;

use SprykerEngine\Shared\Kernel\ClassResolver;
use SprykerEngine\Shared\Kernel\IdentityMapClassResolver;

/**
 * @group Kernel
 * @group ClassResolver
 * @group IdentityMapClassResolver
 */
class IdentityMapClassResolverTest extends \PHPUnit_Framework_TestCase
{

    public function testGetInstanceWithGivenClassResolverShouldReturnInstance()
    {
        $resolver = new ClassResolver();
        $identityMapClassResolver = IdentityMapClassResolver::getInstance($resolver);

        $this->assertInstanceOf('SprykerEngine\Shared\Kernel\IdentityMapClassResolver', $identityMapClassResolver);
    }

    public function testCanResolveShouldReturnTrueFromInnerResolver()
    {
        $classResolverMock = $this->getMock('SprykerEngine\Shared\Kernel\ClassResolver', ['canResolve']);
        $classResolverMock->expects($this->once())
            ->method('canResolve')
            ->will($this->returnValue(true));

        $identityMapClassResolver = IdentityMapClassResolver::getInstance($classResolverMock);
        $result = $identityMapClassResolver->canResolve(
            '\\Unit\\{{namespace}}\\Shared\\{{bundle}}{{store}}\\Fixtures\\CamelHumpClassResolver\\Foo',
            'Kernel'
        );

        $this->assertTrue($result);
    }

    public function testCanResolveShouldReturnTrueIfResultAlreadyInMap()
    {
        $classResolverMock = $this->getMock('SprykerEngine\Shared\Kernel\ClassResolver', ['canResolve']);
        $classResolverMock->expects($this->once())
            ->method('canResolve')
            ->will($this->returnValue(true));

        $identityMapClassResolver = IdentityMapClassResolver::getInstance($classResolverMock);
        $firstRun = $identityMapClassResolver->canResolve(
            '\\Unit\\{{namespace}}\\Shared\\{{bundle}}{{store}}\\Fixtures\\TestClassResolver',
            'Kernel'
        );

        $secondRun = $identityMapClassResolver->canResolve(
            '\\Unit\\{{namespace}}\\Shared\\{{bundle}}{{store}}\\Fixtures\\TestClassResolver',
            'Kernel'
        );

        $this->assertTrue($secondRun);
    }

    public function testResolveShouldThrowExceptionIfClassWhetherInMapNorCanBeResolvedByInnerResolver()
    {
        $classResolverMock = $this->getMock('SprykerEngine\Shared\Kernel\ClassResolver', ['canResolve']);
        $classResolverMock->expects($this->once())
            ->method('canResolve')
            ->will($this->returnValue(false));

        $identityMapClassResolver = IdentityMapClassResolver::getInstance($classResolverMock);

        $this->setExpectedException('SprykerEngine\Shared\Kernel\ClassResolver\ClassNotFoundException');

        $identityMapClassResolver->resolve(
            '\\Foo\\Bar',
            'Kernel'
        );
    }

    public function testResolveShouldReturnClassFromInnerResolverAndStoreClassNameInMap()
    {
        $classResolverMock = $this->getMock('SprykerEngine\Shared\Kernel\ClassResolver', ['canResolve']);
        $classResolverMock->expects($this->once())
            ->method('canResolve')
            ->will($this->returnValue(true));

        $identityMapClassResolver = IdentityMapClassResolver::getInstance($classResolverMock);

        $resolvedClass = $identityMapClassResolver->resolve(
            '\\Unit\\{{namespace}}\\Shared\\{{bundle}}{{store}}\\Fixtures\\CamelHumpClassResolver\\Foo\\Bar',
            'Kernel'
        );

        $this->assertInstanceOf(
            '\Unit\SprykerEngine\Shared\Kernel\Fixtures\CamelHumpClassResolver\Foo\Bar',
            $resolvedClass
        );
    }

    public function testResolveShouldReturnClassFromMap()
    {
        $classResolverMock = $this->getMock('SprykerEngine\Shared\Kernel\ClassResolver', ['canResolve']);
        $classResolverMock->expects($this->once())
            ->method('canResolve')
            ->will($this->returnValue(true));

        $identityMapClassResolver = IdentityMapClassResolver::getInstance($classResolverMock);

        $firstResolvedClass = $identityMapClassResolver->resolve(
            '\\Unit\\{{namespace}}\\Shared\\{{bundle}}{{store}}\\Fixtures\\CamelHumpClassResolver\\FooBar\\Baz',
            'Kernel'
        );

        $this->assertInstanceOf(
            '\Unit\SprykerEngine\Shared\Kernel\Fixtures\CamelHumpClassResolver\FooBar\Baz',
            $firstResolvedClass
        );

        $secondResolvedClass = $identityMapClassResolver->resolve(
            '\\Unit\\{{namespace}}\\Shared\\{{bundle}}{{store}}\\Fixtures\\CamelHumpClassResolver\\FooBar\\Baz',
            'Kernel'
        );

        $this->assertNotSame($firstResolvedClass, $secondResolvedClass);
    }

    public function testResolveWithArgumentsShouldReturnClassFromMap()
    {
        $classResolverMock = $this->getMock('SprykerEngine\Shared\Kernel\ClassResolver', ['canResolve']);
        $classResolverMock->expects($this->once())
            ->method('canResolve')
            ->will($this->returnValue(true))
        ;

        $data = 'foo';

        $identityMapClassResolver = IdentityMapClassResolver::getInstance($classResolverMock);

        $firstResolvedClass = $identityMapClassResolver->resolve(
            '\\Unit\\{{namespace}}\\Shared\\{{bundle}}{{store}}\\Fixtures\\CamelHumpClassResolver\\FooBar\\Baz\\Bat',
            'Kernel',
            [$data]
        );
        $this->assertInstanceOf(
            '\Unit\SprykerEngine\Shared\Kernel\Fixtures\CamelHumpClassResolver\FooBar\Baz\Bat',
            $firstResolvedClass
        );

        $secondResolvedClass = $identityMapClassResolver->resolve(
            '\\Unit\\{{namespace}}\\Shared\\{{bundle}}{{store}}\\Fixtures\\CamelHumpClassResolver\\FooBar\\Baz\\Bat',
            'Kernel',
            [$data]
        );

        $this->assertNotSame($firstResolvedClass, $secondResolvedClass);
    }

}
