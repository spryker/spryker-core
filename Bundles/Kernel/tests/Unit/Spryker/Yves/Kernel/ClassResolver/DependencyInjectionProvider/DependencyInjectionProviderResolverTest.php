<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\Kernel\ClassResolver\DependencyInjectionProvider;

use Spryker\Shared\Kernel\ContainerInterface;
use Spryker\Shared\Kernel\Dependency\Injection\DependencyInjectionInterface;
use Spryker\Shared\Kernel\Dependency\Injection\DependencyInjectionProviderCollectionInterface;
use Spryker\Yves\Kernel\ClassResolver\DependencyInjectionProvider\DependencyInjectionProviderResolver;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @group Spryker
 * @group Yves
 * @group Kernel
 * @group DependencyInjectionProviderResolver
 */
class DependencyInjectionProviderResolverTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The bundle which calls the `getProvidedDependency()`
     *
     * @var string
     */
    protected $injectFromBundle = 'Kernel';

    /**
     * The bundle which want to inject dependencies into a certain bundle
     *
     * @var string
     */
    protected $injectToBundle = 'Foo';

    /**
     * @var string
     */
    protected $coreClass = 'Unit\\Spryker\\Yves\\Kernel\\ClassResolver\\Fixtures\\FooDependencyInjector';

    /**
     * @var string
     */
    protected $projectClass = 'Unit\\Pyz\\Yves\\Kernel\\ClassResolver\\Fixtures\\FooDependencyInjector';

    /**
     * @var string
     */
    protected $storeClass = 'Unit\\Pyz\\Yves\\KernelDE\\ClassResolver\\Fixtures\\FooDependencyInjector';

    /**
     * @var string
     */
    protected $classPattern = 'Unit\\%namespace%\\Yves\\%fromBundle%%store%\\ClassResolver\\Fixtures\\%bundle%DependencyInjector';

    /**
     * @var array
     */
    protected $createdFiles = [];

    /**
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        $this->deleteCreatedFiles();
    }

    /**
     * @param array $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Yves\Kernel\ClassResolver\DependencyInjectionProvider\DependencyInjectionProviderResolver
     */
    protected function getResolverMock(array $methods)
    {
        $dependencyInjectionProviderResolverMock = $this->getMock(DependencyInjectionProviderResolver::class, $methods);

        return $dependencyInjectionProviderResolverMock;
    }

    /**
     * @return void
     */
    public function testResolveShouldReturnEmptyCollection()
    {
        $resolverMock = $this->getResolverMock(['canResolve']);
        $resolverMock->method('canResolve')
            ->willReturn(false);

        $dependencyInjectionProviderCollection = $resolverMock->resolve('CatFace');

        $this->assertInstanceOf(
            DependencyInjectionProviderCollectionInterface::class,
            $dependencyInjectionProviderCollection
        );

        $this->assertCount(0, $dependencyInjectionProviderCollection);
    }

    /**
     * @return void
     */
    public function testResolveMustReturnCoreClass()
    {
        $this->createClass($this->coreClass);

        $resolverMock = $this->getResolverMock(['getClassPattern', 'getDependencyInjectionConfiguration']);
        $resolverMock->method('getClassPattern')
            ->willReturn($this->classPattern);

        $resolverMock->method('getDependencyInjectionConfiguration')
            ->willReturn([$this->injectToBundle => [$this->injectFromBundle]]);

        $dependencyInjectionProviderCollection = $resolverMock->resolve($this->injectToBundle);

        $this->assertInstanceOf(
            DependencyInjectionProviderCollectionInterface::class,
            $dependencyInjectionProviderCollection
        );

        $resolvedDependencyInjectionProvider = current($dependencyInjectionProviderCollection->getDependencyInjectionProvider());
        $this->assertInstanceOf($this->coreClass, $resolvedDependencyInjectionProvider);
    }

    /**
     * @return void
     */
    public function testResolveMustReturnProjectClass()
    {
        $this->createClass($this->coreClass);
        $this->createClass($this->projectClass);

        $resolverMock = $this->getResolverMock(['getClassPattern', 'getDependencyInjectionConfiguration']);
        $resolverMock->method('getClassPattern')
            ->willReturn($this->classPattern);

        $resolverMock->method('getDependencyInjectionConfiguration')
            ->willReturn([$this->injectToBundle => [$this->injectFromBundle]]);

        $dependencyInjectionProviderCollection = $resolverMock->resolve($this->injectToBundle);

        $this->assertInstanceOf(
            DependencyInjectionProviderCollectionInterface::class,
            $dependencyInjectionProviderCollection
        );

        $resolvedDependencyInjectionProvider = current($dependencyInjectionProviderCollection->getDependencyInjectionProvider());
        $this->assertInstanceOf($this->projectClass, $resolvedDependencyInjectionProvider);
    }

    /**
     * @return void
     */
    public function testResolveMustReturnStoreClass()
    {
        $this->createClass($this->projectClass);
        $this->createClass($this->storeClass);

        $resolverMock = $this->getResolverMock(['getClassPattern', 'getDependencyInjectionConfiguration']);
        $resolverMock->method('getClassPattern')
            ->willReturn($this->classPattern);

        $resolverMock->method('getDependencyInjectionConfiguration')
            ->willReturn([$this->injectToBundle => [$this->injectFromBundle]]);

        $dependencyInjectionProviderCollection = $resolverMock->resolve($this->injectToBundle);

        $this->assertInstanceOf(
            DependencyInjectionProviderCollectionInterface::class,
            $dependencyInjectionProviderCollection
        );

        $resolvedDependencyInjectionProvider = current($dependencyInjectionProviderCollection->getDependencyInjectionProvider());
        $this->assertInstanceOf($this->storeClass, $resolvedDependencyInjectionProvider);
    }

    /**
     * @return void
     */
    public function testGetClassPattern()
    {
        $dependencyInjectionProviderResolver = new DependencyInjectionProviderResolver();
        $this->assertSame('\%namespace%\Yves\%fromBundle%%store%\Dependency\Injection\%bundle%DependencyInjector', $dependencyInjectionProviderResolver->getClassPattern());
    }

    /**
     * @return void
     */
    private function deleteCreatedFiles()
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->createdFiles);
    }

    /**
     * @param string $className
     *
     * @return void
     */
    protected function createClass($className)
    {
        $classNameParts = explode('\\', $className);
        $class = array_pop($classNameParts);
        $fileContent = '<?php'
            . PHP_EOL . 'namespace ' . implode('\\', $classNameParts) . ';'
            . PHP_EOL . 'use ' . DependencyInjectionInterface::class . ';'
            . PHP_EOL . 'use ' . ContainerInterface::class . ';'
            . PHP_EOL . 'class ' . $class . ' implements DependencyInjectionInterface'
            . PHP_EOL . '{'
            . PHP_EOL . 'public function inject(ContainerInterface $container){}'
            . PHP_EOL . '}';

        $directoryParts = [
            $this->getBasePath(),
            implode(DIRECTORY_SEPARATOR, $classNameParts),
        ];
        $directory = implode(DIRECTORY_SEPARATOR,  $directoryParts);

        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }
        $fileName = $directory . DIRECTORY_SEPARATOR . $class . '.php';

        $this->createdFiles[] = $fileName;
        file_put_contents($fileName, $fileContent);

        require_once $fileName;
    }

    /**
     * @return string
     */
    private function getBasePath()
    {
        $directoryParts = explode(DIRECTORY_SEPARATOR, __DIR__);
        $testsDirectoryPosition = array_search('tests', $directoryParts);

        $basePath = implode(DIRECTORY_SEPARATOR, array_slice($directoryParts, 0, $testsDirectoryPosition + 1));

        return $basePath;
    }

}
