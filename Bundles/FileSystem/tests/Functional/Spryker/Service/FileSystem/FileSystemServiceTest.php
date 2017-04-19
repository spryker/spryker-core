<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Service\FileSystem;

use Codeception\Configuration;
use FileSystem\Stub\FileSystemConfigStub;
use League\Flysystem\Filesystem;
use PHPUnit_Framework_TestCase;
use Spryker\Service\FileSystem\FileSystemService;
use Spryker\Service\FileSystem\FileSystemServiceFactory;
use Spryker\Service\FileSystem\Model\Storage\FileSystemStorageInterface;

/**
 * @group Functional
 * @group Spryker
 * @group Service
 * @group FileSystem
 * @group FileSystemServiceTest
 */
class FileSystemServiceTest extends PHPUnit_Framework_TestCase
{

    const RESOURCE_FILE_NAME = 'fileName.jpg';

    const STORAGE_DOCUMENT = 'customerStorage';
    const STORAGE_PRODUCT_IMAGE = 'productStorage';

    const ROOT_DIRECTORY = 'fileSystemRoot/uploads/';
    const PATH_STORAGE_DOCUMENT = 'documents/';
    const PATH_STORAGE_PRODUCT_IMAGE = 'images/product/';

    const FILE_STORAGE_DOCUMENT = 'customer.txt';
    const FILE_STORAGE_PRODUCT_IMAGE = 'image.png';

    const FILE_CONTENT = 'Hello World';

    /**
     * @var \Spryker\Service\FileSystem\FileSystemServiceInterface
     */
    protected $fileSystemService;

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

        $config = new FileSystemConfigStub();

        $this->testDataFileSystemRootDirectory = Configuration::dataDir() . static::ROOT_DIRECTORY;

        $factory = new FileSystemServiceFactory();
        $factory->setConfig($config);

        $this->fileSystemService = new FileSystemService();
        $this->fileSystemService->setFactory($factory);
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
    public function testGetStorageByNameWithProduct()
    {
        $storage = $this->fileSystemService->getStorageByName(static::STORAGE_PRODUCT_IMAGE);

        $this->assertInstanceOf(FileSystemStorageInterface::class, $storage);
        $this->assertInstanceOf(Filesystem::class, $storage->getFileSystem());
        $this->assertSame(static::STORAGE_PRODUCT_IMAGE, $storage->getName());
    }

    /**
     * @return void
     */
    public function testGetStorageByNameWithCustomer()
    {
        $storage = $this->fileSystemService->getStorageByName(static::STORAGE_DOCUMENT);

        $this->assertInstanceOf(FileSystemStorageInterface::class, $storage);
        $this->assertInstanceOf(Filesystem::class, $storage->getFileSystem());
        $this->assertSame(static::STORAGE_DOCUMENT, $storage->getName());
    }

    /**
     * @return void
     */
    public function testFileSystemImplementationCreateDir()
    {
        $storage = $this->fileSystemService->getStorageByName(static::STORAGE_DOCUMENT);

        $fileSystem = $storage->getFileSystem();

        $fileSystem->createDir('/foo');

        $hasFoo = $fileSystem->has('/foo');
        $hasBar = $fileSystem->has('/bar');

        $this->assertTrue($hasFoo);
        $this->assertFalse($hasBar);

        $storageDirectory = $this->testDataFileSystemRootDirectory . static::PATH_STORAGE_DOCUMENT . 'foo/';
        $rootDirectoryExists = is_dir($storageDirectory);
        $this->assertTrue($rootDirectoryExists);
    }

    /**
     * @return void
     */
    public function testFileSystemImplementationRename()
    {
        $storage = $this->fileSystemService->getStorageByName(static::STORAGE_DOCUMENT);
        $fileSystem = $storage->getFileSystem();

        $fileSystem->createDir('/foo');
        $fileSystem->rename('/foo', '/bar');

        $hasBar = $fileSystem->has('/bar');
        $hasFoo = $fileSystem->has('/foo');

        $this->assertTrue($hasBar);
        $this->assertFalse($hasFoo);

        $storageDirectory = $this->testDataFileSystemRootDirectory . static::PATH_STORAGE_DOCUMENT . 'bar/';
        $rootDirectoryExists = is_dir($storageDirectory);
        $this->assertTrue($rootDirectoryExists);
    }

    /**
     * @return void
     */
    public function testFileSystemImplementationUpload()
    {
        $storage = $this->fileSystemService->getStorageByName(static::STORAGE_DOCUMENT);
        $fileSystem = $storage->getFileSystem();

        $uploadedFilename = $this->testDataFileSystemRootDirectory . static::FILE_STORAGE_DOCUMENT;
        $storageFilename = '/foo/' . static::FILE_STORAGE_DOCUMENT;

        $h = fopen($uploadedFilename, 'w');
        fwrite($h, static::FILE_CONTENT);
        fclose($h);

        $stream = fopen($uploadedFilename, 'r+');
        try {
            if ($fileSystem->has($storageFilename)) {
                $fileSystem->updateStream($storageFilename, $stream);
            } else {
                $fileSystem->writeStream($storageFilename, $stream);
            }
            fclose($stream);
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }

            unlink($uploadedFilename);
        }

        $content = $fileSystem->read($storageFilename);

        $this->assertSame(static::FILE_CONTENT, $content);
    }

    /**
     * @return void
     */
    protected function directoryCleanup()
    {
        try {
            $file = $this->testDataFileSystemRootDirectory . static::PATH_STORAGE_DOCUMENT . 'foo/' . static::FILE_STORAGE_DOCUMENT;
            if (is_file($file)) {
                unlink($file);
            }

            $dir = $this->testDataFileSystemRootDirectory . static::PATH_STORAGE_DOCUMENT . 'bar';
            if (is_dir($dir)) {
                rmdir($dir);
            }

            $dir = $this->testDataFileSystemRootDirectory . static::PATH_STORAGE_DOCUMENT . 'foo';
            if (is_dir($dir)) {
                rmdir($dir);
            }

            $dir = $this->testDataFileSystemRootDirectory . static::PATH_STORAGE_DOCUMENT;
            if (is_dir($dir)) {
                rmdir($dir);
            }

            $file = $this->testDataFileSystemRootDirectory . static::PATH_STORAGE_PRODUCT_IMAGE . static::FILE_STORAGE_PRODUCT_IMAGE;
            if (is_file($file)) {
                unlink($file);
            }

            $dir = $this->testDataFileSystemRootDirectory . static::PATH_STORAGE_PRODUCT_IMAGE;
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
