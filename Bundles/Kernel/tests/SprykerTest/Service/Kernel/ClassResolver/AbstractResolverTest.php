<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Kernel\ClassResolver;

use Codeception\Test\Unit;
use ReflectionClass;
use Spryker\Shared\Kernel\ClassResolver\AbstractClassResolver;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Service
 * @group Kernel
 * @group ClassResolver
 * @group AbstractResolverTest
 * Add your own group annotations below this line
 */
abstract class AbstractResolverTest extends Unit
{
    /**
     * @var string
     */
    protected $projectClass;

    /**
     * @var string
     */
    protected $storeClass;

    /**
     * @var string
     */
    protected $classPattern;

    /**
     * @var string
     */
    protected $expectedExceptionClass;

    /**
     * @var string
     */
    protected $className = 'Kernel';

    /**
     * @var string
     */
    protected $unResolvableClassName = 'unresolvable';

    /**
     * @var array
     */
    private $createdFiles = [];

    /**
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        $reflectionResolver = new ReflectionClass(AbstractClassResolver::class);
        $reflectionProperty = $reflectionResolver->getProperty('cache');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue([]);

        $this->deleteCreatedFiles();
    }

    /**
     * @param array $methods
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Kernel\ClassResolver\AbstractClassResolver
     */
    abstract protected function getResolverMock(array $methods);

    /**
     * @return void
     */
    public function testResolveMustThrowExceptionIfClassCanNotBeResolved()
    {
        $this->expectException($this->expectedExceptionClass);

        $resolverMock = $this->getResolverMock(['canResolve']);
        $resolverMock->method('canResolve')
            ->willReturn(false);

        $resolverMock->resolve($this->unResolvableClassName);
    }

    /**
     * @return void
     */
    public function testResolveMustReturnProjectClass()
    {
        $this->createClass($this->projectClass);

        $resolverMock = $this->getResolverMock(['getClassPattern', 'getProjectNamespaces']);
        $resolverMock->method('getClassPattern')->willReturn($this->classPattern);
        $resolverMock->method('getProjectNamespaces')->willReturn(['ProjectNamespace']);

        $resolved = $resolverMock->resolve($this->className);
        $this->assertInstanceOf($this->projectClass, $resolved);
    }

    /**
     * @return void
     */
    public function testResolveMustReturnStoreClass()
    {
        $this->createClass($this->projectClass);
        $this->createClass($this->storeClass);

        $resolverMock = $this->getResolverMock(['getClassPattern', 'getProjectNamespaces']);
        $resolverMock->method('getClassPattern')->willReturn($this->classPattern);
        $resolverMock->method('getProjectNamespaces')->willReturn(['ProjectNamespace']);

        $resolved = $resolverMock->resolve($this->className);
        $this->assertInstanceOf($this->storeClass, $resolved);
    }

    /**
     * @return void
     */
    private function deleteCreatedFiles()
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
    protected function createClass($className)
    {
        $classNameParts = explode('\\', $className);
        $class = array_pop($classNameParts);
        $fileContent = '<?php' . PHP_EOL . 'namespace ' . implode('\\', $classNameParts) . ';' . PHP_EOL . 'class ' . $class . '{}';

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
    private function getBasePath()
    {
        return __DIR__ . '/../_data/Generated';
    }
}
