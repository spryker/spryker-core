<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\FileSystem\Business;

use Codeception\Configuration;
use FileSystem\Stub\FileSystemConfigStub;
use FileSystem\Stub\FlysystemConfigStub;
use League\Flysystem\FileNotFoundException;
use PHPUnit_Framework_TestCase;
use Spryker\Service\Flysystem\FlysystemService;
use Spryker\Service\Flysystem\FlysystemServiceFactory;
use Spryker\Zed\FileSystem\Business\FileSystemBusinessFactory;
use Spryker\Zed\FileSystem\Business\FileSystemFacade;
use Spryker\Zed\FileSystem\Dependency\Facade\FileSystemToFlysystemBridge;
use Spryker\Zed\FileSystem\FileSystemDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group FileSystem
 * @group Business
 * @group FileSystemFacadeTest
 */
class FileSystemFacadeTest extends PHPUnit_Framework_TestCase
{

    const RESOURCE_FILE_NAME = 'fileName.jpg';

    const FILE_SYSTEM_DOCUMENT = 'customerFileSystem';
    const FILE_SYSTEM_PRODUCT_IMAGE = 'productFileSystem';

    const ROOT_DIRECTORY = 'fileSystemRoot/uploads/';
    const PATH_DOCUMENT = 'documents/';
    const PATH_PRODUCT_IMAGE = 'images/product/';

    const FILE_DOCUMENT = 'customer.txt';
    const FILE_PRODUCT_IMAGE = 'image.png';

    const FILE_CONTENT = 'Hello World';

    /**
     * @var \Spryker\Zed\FileSystem\Business\FileSystemFacadeInterface
     */
    protected $fileSystemFacade;

    /**
     * @var string
     */
    protected $testDataFileSystemRootDirectory;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->testDataFileSystemRootDirectory = Configuration::dataDir() . static::ROOT_DIRECTORY;

        $flysystemConfig = new FlysystemConfigStub();
        $flysystemFactory = new FlysystemServiceFactory();
        $flysystemFactory->setConfig($flysystemConfig);

        $flysystemService = new FlysystemService();
        $flysystemService->setFactory($flysystemFactory);

        $container = new Container();
        $container[FileSystemDependencyProvider::SERVICE_FLYSYSTEM] = function (Container $container) use ($flysystemService) {
            return new FileSystemToFlysystemBridge($flysystemService);
        };

        $config = new FileSystemConfigStub();
        $factory = new FileSystemBusinessFactory();
        $factory->setConfig($config);
        $factory->setContainer($container);

        $this->fileSystemFacade = new FileSystemFacade();
        $this->fileSystemFacade->setFactory($factory);
    }

    /**
     * @return void
     */
    protected function tearDown()
    {
        $this->directoryCleanup();
    }

    /**
     * @return void
     */
    public function testHasShouldReturnFalseWithNonExistingFile()
    {
        $result = $this->fileSystemFacade->has(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT
        );

        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testHasShouldReturnTrueWithExistingFile()
    {
        $this->createDocumentFile();

        $result = $this->fileSystemFacade->has(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT
        );

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testReadWithNonExistingFileShouldThrowException()
    {
        $this->expectException(FileNotFoundException::class);

        $contents = $this->fileSystemFacade->read(
            static::FILE_SYSTEM_PRODUCT_IMAGE,
            'nonExistingFile.nil'
        );

        $this->assertNull($contents);
    }

    /**
     * @return void
     */
    public function testReadWithExistingFileShouldReturnContent()
    {
        $this->createDocumentFile();

        $contents = $this->fileSystemFacade->read(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT
        );

        $this->assertSame(static::FILE_CONTENT, $contents);
    }

    /**
     * @return void
     */
    public function testPut()
    {
        $this->createDocumentFile('Lorem Ipsum');

        $result = $this->fileSystemFacade->put(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
            static::FILE_CONTENT
        );

        $content = $this->getDocumentFileContent();

        $this->assertTrue($result);
        $this->assertSame(static::FILE_CONTENT, $content);
    }

    /**
     * @return void
     */
    public function testWrite()
    {
        $result = $this->fileSystemFacade->write(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
            static::FILE_CONTENT
        );

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testDelete()
    {
        $this->createDocumentFile();

        $result = $this->fileSystemFacade->delete(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT
        );

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testRename()
    {
        $this->createDocumentFile();

        $result = $this->fileSystemFacade->rename(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
            'foo/' . 'NEW_' . static::FILE_DOCUMENT
        );

        $isOriginalFile = is_file($this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/' . static::FILE_DOCUMENT);
        $isRenamedFile = is_file($this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/NEW_' . static::FILE_DOCUMENT);

        $this->assertTrue($result);
        $this->assertFalse($isOriginalFile);
        $this->assertTrue($isRenamedFile);
    }

    /**
     * @return void
     */
    public function testCopy()
    {
        $this->createDocumentFile();

        $result = $this->fileSystemFacade->copy(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
            'foo/' . 'NEW_' . static::FILE_DOCUMENT
        );

        $isOriginalFile = is_file($this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/' . static::FILE_DOCUMENT);
        $isCopiedFile = is_file($this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/NEW_' . static::FILE_DOCUMENT);

        $this->assertTrue($result);
        $this->assertTrue($isOriginalFile);
        $this->assertTrue($isCopiedFile);
    }

    /**
     * @return void
     */
    public function testGetMimeType()
    {
        $this->createDocumentFile();

        $mimeType = $this->fileSystemFacade->getMimetype(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT
        );

        $this->assertSame('text/plain', $mimeType);
    }

    /**
     * @param string|null $content
     *
     * @return void
     */
    protected function createDocumentFile($content = null)
    {
        $dir = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $file = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/' . static::FILE_DOCUMENT;

        $h = fopen($file, 'w');
        fwrite($h, $content ?: static::FILE_CONTENT);
        fclose($h);
    }

    /**
     * @return bool|string
     */
    protected function getDocumentFileContent()
    {
        $file = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/' . static::FILE_DOCUMENT;
        if (!is_file($file)) {
            return false;
        }

        return file_get_contents($file);
    }

    /**
     * @return void
     */
    protected function directoryCleanup()
    {
        try {
            $file = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/' . static::FILE_DOCUMENT;
            if (is_file($file)) {
                unlink($file);
            }

            $file = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/NEW_' . static::FILE_DOCUMENT;
            if (is_file($file)) {
                unlink($file);
            }

            $dir = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'bar';
            if (is_dir($dir)) {
                rmdir($dir);
            }

            $dir = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo';
            if (is_dir($dir)) {
                rmdir($dir);
            }

            $dir = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT;
            if (is_dir($dir)) {
                rmdir($dir);
            }

            $file = $this->testDataFileSystemRootDirectory . static::PATH_PRODUCT_IMAGE . static::FILE_PRODUCT_IMAGE;
            if (is_file($file)) {
                unlink($file);
            }

            $dir = $this->testDataFileSystemRootDirectory . static::PATH_PRODUCT_IMAGE;
            if (is_dir($dir)) {
                rmdir($dir);
            }

            $dir = $this->testDataFileSystemRootDirectory . 'images/';
            if (is_dir($dir)) {
                rmdir($dir);
            }

        } catch (\Exception $e) {

        } catch (\Throwable $e) {

        }
    }

}
