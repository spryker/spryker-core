<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Kernel\ClassResolver\DependencyInjector;

use Codeception\Test\Unit;
use Spryker\Yves\Kernel\ClassResolver\DependencyInjector\DependencyInjectorResolver;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Dependency\Injector\DependencyInjectorCollectionInterface;
use Spryker\Yves\Kernel\Dependency\Injector\DependencyInjectorInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Kernel
 * @group ClassResolver
 * @group DependencyInjector
 * @group DependencyInjectorResolverTest
 * Add your own group annotations below this line
 */
class DependencyInjectorResolverTest extends Unit
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
    protected $coreClass = 'Spryker\\Yves\\Kernel\\ClassResolver\\FooDependencyInjector';

    /**
     * @var string
     */
    protected $projectClass = 'ProjectNamespace\\Yves\\Kernel\\ClassResolver\\FooDependencyInjector';

    /**
     * @var string
     */
    protected $storeClass = 'ProjectNamespace\\Yves\\KernelDE\\ClassResolver\\FooDependencyInjector';

    /**
     * @var string
     */
    protected $codeBucketClass = 'CodeBucketNamespace\\Yves\\KernelDE\\ClassResolver\\FooDependencyInjector';

    /**
     * @var string
     */
    protected $classPattern = '%namespace%\\Yves\\%fromBundle%%codeBucket%\\ClassResolver\\%bundle%DependencyInjector';

    /**
     * @var array
     */
    protected $createdFiles = [];

    /**
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();

        $this->deleteCreatedFiles();
    }

    /**
     * @param array $methods
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Yves\Kernel\ClassResolver\DependencyInjector\DependencyInjectorResolver
     */
    protected function getResolverMock(array $methods): DependencyInjectorResolver
    {
        $dependencyInjectorResolverMock = $this->getMockBuilder(DependencyInjectorResolver::class)->setMethods($methods)->getMock();

        return $dependencyInjectorResolverMock;
    }

    /**
     * @return void
     */
    public function testResolveShouldReturnEmptyCollection(): void
    {
        $resolverMock = $this->getResolverMock(['canResolve']);
        $resolverMock->method('canResolve')
            ->willReturn(false);

        $dependencyInjectorCollection = $resolverMock->resolve('CatFace');

        $this->assertInstanceOf(
            DependencyInjectorCollectionInterface::class,
            $dependencyInjectorCollection
        );

        $this->assertCount(0, $dependencyInjectorCollection);
    }

    /**
     * @return void
     */
    public function testResolveMustReturnCoreClass(): void
    {
        $this->createClass($this->coreClass);

        $resolverMock = $this->getResolverMock(['getClassPattern', 'getDependencyInjectorConfiguration', 'getProjectNamespaces']);
        $resolverMock->method('getClassPattern')->willReturn($this->classPattern);
        $resolverMock->method('getProjectNamespaces')->willReturn(['ProjectNamespace']);

        $resolverMock->method('getDependencyInjectorConfiguration')
            ->willReturn([$this->injectToBundle => [$this->injectFromBundle]]);

        $dependencyInjectorCollection = $resolverMock->resolve($this->injectToBundle);

        $this->assertInstanceOf(
            DependencyInjectorCollectionInterface::class,
            $dependencyInjectorCollection
        );

        $resolvedDependencyInjector = current($dependencyInjectorCollection->getDependencyInjector());
        $this->assertInstanceOf($this->coreClass, $resolvedDependencyInjector);
    }

    /**
     * @return void
     */
    public function testResolveMustReturnProjectClass(): void
    {
        $this->createClass($this->coreClass);
        $this->createClass($this->projectClass);

        $resolverMock = $this->getResolverMock(['getClassPattern', 'getDependencyInjectorConfiguration', 'getProjectNamespaces']);
        $resolverMock->method('getClassPattern')->willReturn($this->classPattern);
        $resolverMock->method('getProjectNamespaces')->willReturn(['ProjectNamespace']);

        $resolverMock->method('getDependencyInjectorConfiguration')
            ->willReturn([$this->injectToBundle => [$this->injectFromBundle]]);

        $dependencyInjectorCollection = $resolverMock->resolve($this->injectToBundle);

        $this->assertInstanceOf(
            DependencyInjectorCollectionInterface::class,
            $dependencyInjectorCollection
        );

        $resolvedDependencyInjector = current($dependencyInjectorCollection->getDependencyInjector());
        $this->assertInstanceOf($this->projectClass, $resolvedDependencyInjector);
    }

    /**
     * @return void
     */
    public function testResolveMustReturnStoreClass(): void
    {
        $this->createClass($this->projectClass);
        $this->createClass($this->storeClass);

        $resolverMock = $this->getResolverMock(['getClassPattern', 'getDependencyInjectorConfiguration', 'getProjectNamespaces']);
        $resolverMock->method('getClassPattern')->willReturn($this->classPattern);
        $resolverMock->method('getProjectNamespaces')->willReturn(['ProjectNamespace']);

        $resolverMock->method('getDependencyInjectorConfiguration')
            ->willReturn([$this->injectToBundle => [$this->injectFromBundle]]);

        $dependencyInjectorCollection = $resolverMock->resolve($this->injectToBundle);

        $this->assertInstanceOf(
            DependencyInjectorCollectionInterface::class,
            $dependencyInjectorCollection
        );

        $resolvedDependencyInjector = current($dependencyInjectorCollection->getDependencyInjector());
        $this->assertInstanceOf($this->storeClass, $resolvedDependencyInjector);
    }

    /**
     * @return void
     */
    public function testResolveMustReturnCodeBucketClass(): void
    {
        $this->createClass($this->projectClass);
        $this->createClass($this->codeBucketClass);
        $this->createClass($this->storeClass);

        $resolverMock = $this->getResolverMock(['getClassPattern', 'getDependencyInjectorConfiguration', 'getProjectNamespaces']);
        $resolverMock->method('getClassPattern')->willReturn($this->classPattern);
        $resolverMock->method('getProjectNamespaces')->willReturn(['CodeBucketNamespace']);

        $resolverMock->method('getDependencyInjectorConfiguration')
            ->willReturn([$this->injectToBundle => [$this->injectFromBundle]]);

        $dependencyInjectorCollection = $resolverMock->resolve($this->injectToBundle);

        $this->assertInstanceOf(
            DependencyInjectorCollectionInterface::class,
            $dependencyInjectorCollection
        );

        $resolvedDependencyInjector = current($dependencyInjectorCollection->getDependencyInjector());
        $this->assertInstanceOf($this->codeBucketClass, $resolvedDependencyInjector);
    }

    /**
     * @return void
     */
    public function testGetClassPattern(): void
    {
        $dependencyInjectorResolver = new DependencyInjectorResolver();
        $this->assertSame('\%namespace%\Yves\%fromBundle%%codeBucket%\Dependency\Injector\%bundle%DependencyInjector', $dependencyInjectorResolver->getClassPattern());
    }

    /**
     * @return void
     */
    private function deleteCreatedFiles(): void
    {
        if (is_dir($this->getBasePath())) {
            $filesystem = new Filesystem();
            $filesystem->remove($this->getBasePath());
        }
    }

    /**
     * @param string $className
     *
     * @return void
     */
    protected function createClass(string $className): void
    {
        $classNameParts = explode('\\', $className);
        $class = array_pop($classNameParts);
        $fileContent = '<?php'
            . PHP_EOL . 'namespace ' . implode('\\', $classNameParts) . ';'
            . PHP_EOL . 'use ' . DependencyInjectorInterface::class . ';'
            . PHP_EOL . 'use ' . Container::class . ';'
            . PHP_EOL . 'class ' . $class . ' implements DependencyInjectorInterface'
            . PHP_EOL . '{'
            . PHP_EOL . 'public function inject(Container $container): Container {}'
            . PHP_EOL . '}';

        $directoryParts = [
            $this->getBasePath(),
            implode(DIRECTORY_SEPARATOR, $classNameParts),
        ];
        $directory = implode(DIRECTORY_SEPARATOR, $directoryParts);

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
    private function getBasePath(): string
    {
        return __DIR__ . '/../_data/Generated';
    }
}
