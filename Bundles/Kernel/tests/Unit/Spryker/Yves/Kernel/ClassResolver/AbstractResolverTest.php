<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\Kernel\ClassResolver;

use Symfony\Component\Filesystem\Filesystem;

/**
 * @group Spryker
 * @group Yves
 * @group Kernel
 * @group ClassResolver
 */
abstract class AbstractResolverTest extends \PHPUnit_Framework_TestCase
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

        $this->deleteCreatedFiles();
    }

    /**
     * @param array $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Shared\Kernel\ClassResolver\AbstractClassResolver
     */
    abstract protected function getResolverMock(array $methods);

    /**
     * @return void
     */
    public function testResolveMustThrowExceptionIfClassCanNotBeResolved()
    {
        $this->setExpectedException($this->expectedExceptionClass);

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

        $resolverMock = $this->getResolverMock(['getClassPattern']);
        $resolverMock->method('getClassPattern')
            ->willReturn($this->classPattern);

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

        $resolverMock = $this->getResolverMock(['getClassPattern']);
        $resolverMock->method('getClassPattern')
            ->willReturn($this->classPattern);

        $resolved = $resolverMock->resolve($this->className);
        $this->assertInstanceOf($this->storeClass, $resolved);
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
        $fileContent = '<?php' . PHP_EOL . 'namespace ' . implode('\\', $classNameParts) . ';' . PHP_EOL . 'class ' . $class . '{}';

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
