<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Shared\Kernel;

use Unit\SprykerEngine\Shared\Kernel\Fixtures\Factory;
use Unit\SprykerEngine\Shared\Kernel\Fixtures\MissingPropertyKernelFactory;

/**
 * @group Kernel
 * @group AbstractFactory
 */
class AbstractFactoryTest extends \PHPUnit_Framework_TestCase
{

    const BUNDLE_NAME = 'Kernel';

    const DEPENDENCY_CONTAINER = 'DependencyContainer';

    public function testExistsShouldReturnTrueIfClassCanBeResolved()
    {
        $factory = new Factory(self::BUNDLE_NAME);

        $this->assertTrue($factory->exists('Foo'));
    }

    public function testExistsWithMagicCallShouldReturnTrueIfClassCanBeResolved()
    {
        $factory = new Factory(self::BUNDLE_NAME);

        $this->assertTrue($factory->existsFoo());
    }

    public function testExistsShouldReturnFalseIfClassCanNotBeResolved()
    {
        $factory = new Factory(self::BUNDLE_NAME);

        $this->assertFalse($factory->exists('False'));
    }

    public function testIfClassNamePropertyIsNullGetClassNamePatternShouldThrowException()
    {
        $this->setExpectedException('SprykerEngine\Shared\Kernel\Factory\FactoryException');
        $factory = new MissingPropertyKernelFactory(self::BUNDLE_NAME);

        $factory->exists('Exception');
    }

    public function testBuildClassNameShouldReturnClassWithoutAddingBundleNameIfItsNotABaseClassName()
    {
        $factory = new Factory(self::BUNDLE_NAME);
        $class = $factory->create('Foo');

        $this->assertInstanceOf('Unit\SprykerEngine\Shared\Kernel\Fixtures\Foo', $class);
    }

    public function testBuildClassNameShouldReturnClassWithAddingBundleNameIfItsABaseClassName()
    {
        $factory = new Factory(self::BUNDLE_NAME);
        $class = $factory->create(self::DEPENDENCY_CONTAINER);

        $this->assertInstanceOf('Unit\SprykerEngine\Shared\Kernel\Fixtures\KernelDependencyContainer', $class);
    }

    public function testCreateWithMagicCallShouldReturnClassIfClassCanBeResolved()
    {
        $factory = new Factory(self::BUNDLE_NAME);
        $class = $factory->createFoo();

        $this->assertInstanceOf('Unit\SprykerEngine\Shared\Kernel\Fixtures\Foo', $class);
    }

    public function testCreateWithMagicCallAndArgumentsShouldReturnClassIfClassCanBeResolved()
    {
        $factory = new Factory(self::BUNDLE_NAME);
        $foo = 'foo';
        $bar = 'bar';

        $class = $factory->createBar($foo, $bar);

        $this->assertInstanceOf('Unit\SprykerEngine\Shared\Kernel\Fixtures\Bar', $class);
        $this->assertSame($foo, $class->getFoo());
        $this->assertSame($bar, $class->getBar());
    }

}
