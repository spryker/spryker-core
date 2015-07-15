<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Shared\Kernel;

use SprykerEngine\Shared\Kernel\CamelHumpClassResolver;
use SprykerEngine\Shared\Kernel\ClassResolver;

/**
 * @group Kernel
 * @group CamelHumpClassResolver
 */
class CamelHumpClassResolverTest extends \PHPUnit_Framework_TestCase
{

    public function testCanResolveShouldReturnFalseIfClassCanNotBeResolved()
    {
        $classResolver = new ClassResolver();
        $resolver = new CamelHumpClassResolver($classResolver);

        $this->assertFalse($resolver->canResolve('Foo', 'Kernel'));
    }

    public function testCanResolveShouldThrowExceptionIfClassNameIsAmbiguous()
    {
        $this->setExpectedException('SprykerEngine\Shared\Kernel\ClassResolver\ClassNameAmbiguousException');

        $classResolver = new ClassResolver();
        $resolver = new CamelHumpClassResolver($classResolver);
        $classNamePattern = '\\Unit\\SprykerEngine\\Shared\\{{bundle}}{{store}}\\Fixtures\\CamelHump';

        $this->assertFalse($resolver->canResolve($classNamePattern, 'Kernel'));
    }

    public function testResolveShouldThrowExceptionIfClassNameIsAmbiguous()
    {
        $this->setExpectedException('SprykerEngine\Shared\Kernel\ClassResolver\ClassNameAmbiguousException');

        $classResolver = new ClassResolver();
        $resolver = new CamelHumpClassResolver($classResolver);
        $classNamePattern = '\\Unit\\SprykerEngine\\Shared\\{{bundle}}{{store}}\\Fixtures\\CamelHump';

        $resolver->resolve($classNamePattern, 'Kernel');
    }

    /**
     * @return array
     */
    public function camelHumpClassNameProvider()
    {
        return [
            ['Foo', 'Unit\SprykerEngine\Shared\Kernel\Fixtures\CamelHumpClassResolver\Foo'],
            ['FooBar', 'Unit\SprykerEngine\Shared\Kernel\Fixtures\CamelHumpClassResolver\Foo\Bar'],
            ['FooBarBaz', 'Unit\SprykerEngine\Shared\Kernel\Fixtures\CamelHumpClassResolver\FooBar\Baz'],
            ['FooBarBazBat', 'Unit\SprykerEngine\Shared\Kernel\Fixtures\CamelHumpClassResolver\FooBar\Baz\Bat'],
        ];
    }

    /**
     * @dataProvider camelHumpClassNameProvider
     *
     * @param string $className
     * @param string $fullyQualifiedClassName
     */
    public function testCanResolveShouldReturnTrueIfClassCanBeResolved($className, $fullyQualifiedClassName)
    {
        $classResolver = new ClassResolver();
        $resolver = new CamelHumpClassResolver($classResolver);
        $classNamePattern = '\\Unit\\{{namespace}}\\Shared\\{{bundle}}{{store}}\\Fixtures\\CamelHumpClassResolver\\' . $className;

        $this->assertTrue($resolver->canResolve($classNamePattern, 'Kernel'));
    }

    /**
     * @dataProvider camelHumpClassNameProvider
     *
     * @param string $className
     * @param string $fullyQualifiedClassName
     */
    public function testCanResolveShouldReturnClassInstanceIfClassCanBeResolved($className, $fullyQualifiedClassName)
    {
        $classResolver = new ClassResolver();
        $resolver = new CamelHumpClassResolver($classResolver);
        $classNamePattern =
            '\\Unit\\{{namespace}}\\Shared\\{{bundle}}{{store}}\\Fixtures\\CamelHumpClassResolver\\'
            . $className
        ;

        $this->assertInstanceOf($fullyQualifiedClassName, $resolver->resolve($classNamePattern, 'Kernel'));
    }

    public function testResolveShouldThrowExceptionIfClassCanNotBeResolved()
    {
        $this->setExpectedException('SprykerEngine\Shared\Kernel\ClassResolver\ClassNotFoundException');

        $classResolver = new ClassResolver();
        $resolver = new CamelHumpClassResolver($classResolver);
        $resolver->resolve('Foo', 'Kernel');
    }

}
