<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Shared\Kernel;

use SprykerEngine\Shared\Kernel\ClassResolver;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @group Kernel
 * @group ClassResolver
 */
class ClassResolverTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $source = __DIR__ . '/ClassResolver/Fixtures/Foo.mock';
        $destination = __DIR__ . '/../../../../Unit/SprykerFeature/Shared/Kernel/ClassResolver/Fixtures/Foo.php';
        $filesystem = new Filesystem();
        $filesystem->copy($source, $destination, true);
    }

    public function tearDown()
    {
        $file = __DIR__ . '/../../../../Unit/SprykerFeature/Shared/Kernel/ClassResolver/Fixtures/Foo.php';
        if (file_exists($file)) {
            (new Filesystem())->remove($file);
        }
    }

    public function testCreateInstanceWithBundleName()
    {
        $resolver = new ClassResolver();

        $this->assertInstanceOf('SprykerEngine\Shared\Kernel\ClassResolver', $resolver);
    }

    public function testCanResolveShouldReturnTrueIfItCanBeResolved()
    {
        $resolver = new ClassResolver();

        $this->assertTrue(
            $resolver->canResolve('\\Unit\\{{namespace}}\\Shared\\{{bundle}}{{store}}\\Fixtures\\TestClassResolver', 'Kernel')
        );
    }

    public function testCanResolveShouldReturnFalseIfItCanNotBeResolved()
    {
        $resolver = new ClassResolver();

        $this->assertFalse($resolver->canResolve('Foo', 'Kernel'));
    }

    public function testResolveShouldReturnClassInstanceIfItCanBeResolved()
    {
        $resolver = new ClassResolver();
        $resolvedClass = $resolver->resolve(
            '\\Unit\\{{namespace}}\\Shared\\{{bundle}}{{store}}\\Fixtures\\TestClassResolver',
            'Kernel'
        );

        $this->assertInstanceOf('Unit\SprykerEngine\Shared\Kernel\Fixtures\TestClassResolver', $resolvedClass);
    }

    public function testResolveWithArgumentsShouldReturnClassInstanceIfItCanBeResolved()
    {
        $resolver = new ClassResolver();
        $testData = 'foo';
        $resolvedClass = $resolver->resolve(
            '\\Unit\\{{namespace}}\\Shared\\{{bundle}}{{store}}\\Fixtures\\TestClassResolver',
            'Kernel',
            [$testData]
        );

        $this->assertInstanceOf('Unit\SprykerEngine\Shared\Kernel\Fixtures\TestClassResolver', $resolvedClass);
        $this->assertSame($testData, $resolvedClass->getData());
    }

    public function testResolveShouldThrowExceptionIfClassCanNotBeResolved()
    {
        $this->setExpectedException('\Exception');

        $resolver = new ClassResolver();
        $resolver->resolve('Bar', 'Foo');
    }

    public function testResolveShouldThrowExceptionIfClassCanBeFoundInMoreThenOneNamespacePerLayer()
    {
        $this->setExpectedException('SprykerEngine\Shared\Kernel\ClassResolver\ClassNameAmbiguousException');

        $resolver = new ClassResolver();
        $resolver->resolve(
            '\\Unit\\{{namespace}}\\Shared\\{{bundle}}{{store}}\\ClassResolver\\Fixtures\\Foo',
            'Kernel'
        );
    }

    public function testCanResolveShouldThrowExceptionIfClassCanBeFoundInMoreThenOneNamespacePerLayer()
    {
        $this->setExpectedException('SprykerEngine\Shared\Kernel\ClassResolver\ClassNameAmbiguousException');

        $resolver = new ClassResolver();
        $resolver->canResolve(
            '\\Unit\\{{namespace}}\\Shared\\{{bundle}}{{store}}\\ClassResolver\\Fixtures\\Foo',
            'Kernel'
        );
    }

    public function testCanResolveShouldReturnTrueIfClassCanBeFoundInAGivenStore()
    {
        $resolver = new ClassResolver();
        $classNamePattern =
            '\\Unit\\{{namespace}}\\Shared\\{{bundle}}{{store}}\\ClassResolver\\Fixtures\\ExistingStoreTest'
        ;

        $this->assertTrue($resolver->canResolve($classNamePattern, 'Kernel'));
    }

    public function testResolveShouldReturnStoreInstanceIfClassCanBeFoundInAGivenStore()
    {
        $resolver = new ClassResolver();
        $classNamePattern =
            '\\Unit\\{{namespace}}\\Shared\\{{bundle}}{{store}}\\ClassResolver\\Fixtures\\ExistingStoreTest'
        ;

        $this->assertInstanceOf(
            '\Unit\SprykerEngine\Shared\KernelDE\ClassResolver\Fixtures\ExistingStoreTest',
            $resolver->resolve($classNamePattern, 'Kernel')
        );
    }

}
