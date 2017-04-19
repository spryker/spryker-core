<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Service\FileSystem;

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

    const STORAGE_CUSTOMER = 'customerImage';
    const STORAGE_PRODUCT = 'productImage';

    const ROOT_DIRECTORY = APPLICATION_ROOT_DIR . '/data/DE/uploads/';
    const PATH_STORAGE_CUSTOMER = 'documents/';
    const PATH_STORAGE_PRODUCT = 'images/product/';

    const FILE_STORAGE_CUSTOMER = 'customer.txt';
    const FILE_STORAGE_PRODUCT = 'image.png.txt';

    const FILE_CONTENT = 'Hello World';

    /**
     * @var \Spryker\Service\FileSystem\FileSystemServiceInterface
     */
    protected $fileSystemService;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->directoryCleanup();

        $config = new FileSystemConfigStub();

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
    public function testGetMimeTypeByFilename()
    {
        $mimeType = $this->fileSystemService->getMimeTypeByFilename(static::RESOURCE_FILE_NAME);

        $this->assertSame('image/jpeg', $mimeType);
    }

    /**
     * @return void
     */
    public function testGetExtensionByFilename()
    {
        $extension = $this->fileSystemService->getExtensionByFilename(static::RESOURCE_FILE_NAME);

        $this->assertSame('jpg', $extension);
    }

    /**
     * @return void
     */
    public function testGetStorageByNameWithProduct()
    {
        $storage = $this->fileSystemService->getStorageByName(static::STORAGE_PRODUCT);

        $this->assertInstanceOf(FileSystemStorageInterface::class, $storage);
        $this->assertInstanceOf(Filesystem::class, $storage->getFileSystem());
        $this->assertSame(static::STORAGE_PRODUCT, $storage->getName());

        $storageDirectory = static::ROOT_DIRECTORY . static::PATH_STORAGE_PRODUCT;
        $rootDirectoryExists = is_dir($storageDirectory);
        $this->assertTrue($rootDirectoryExists);
    }

    /**
     * @return void
     */
    public function testGetStorageByNameWithCustomer()
    {
        $storage = $this->fileSystemService->getStorageByName(static::STORAGE_CUSTOMER);

        $this->assertInstanceOf(FileSystemStorageInterface::class, $storage);
        $this->assertInstanceOf(Filesystem::class, $storage->getFileSystem());
        $this->assertSame(static::STORAGE_CUSTOMER, $storage->getName());

        $storageDirectory = static::ROOT_DIRECTORY . static::PATH_STORAGE_CUSTOMER;
        $rootDirectoryExists = is_dir($storageDirectory);
        $this->assertTrue($rootDirectoryExists);
    }

    /**
     * @return void
     */
    public function testFileSystemImplementationCreateDir()
    {
        $storage = $this->fileSystemService->getStorageByName(static::STORAGE_CUSTOMER);
        $fileSystem = $storage->getFileSystem();

        $fileSystem->createDir('/foo');

        $hasFoo = $fileSystem->has('/foo');
        $hasBar = $fileSystem->has('/bar');

        $this->assertTrue($hasFoo);
        $this->assertFalse($hasBar);
    }

    /**
     * @return void
     */
    public function testFileSystemImplementationRename()
    {
        $storage = $this->fileSystemService->getStorageByName(static::STORAGE_CUSTOMER);
        $fileSystem = $storage->getFileSystem();

        $fileSystem->createDir('/foo');
        $fileSystem->rename('/foo', '/bar');

        $hasBar = $fileSystem->has('/bar');
        $this->assertTrue($hasBar);
    }

    /**
     * @return void
     */
    public function testFileSystemImplementationUpload()
    {
        $storage = $this->fileSystemService->getStorageByName(static::STORAGE_CUSTOMER);
        $fileSystem = $storage->getFileSystem();

        $fileSystem->createDir('/foo');

        $uploadedFilename = static::ROOT_DIRECTORY . static::FILE_STORAGE_CUSTOMER;
        $storageFilename = '/foo/' . static::FILE_STORAGE_CUSTOMER;

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
            $file = static::ROOT_DIRECTORY . static::PATH_STORAGE_CUSTOMER . 'foo/' . static::FILE_STORAGE_CUSTOMER;
            if (is_file($file)) {
                unlink($file);
            }

            $dir = static::ROOT_DIRECTORY . static::PATH_STORAGE_CUSTOMER . 'bar';
            if (is_dir($dir)) {
                rmdir($dir);
            }

            $dir = static::ROOT_DIRECTORY . static::PATH_STORAGE_CUSTOMER . 'foo';
            if (is_dir($dir)) {
                rmdir($dir);
            }

            $dir = static::ROOT_DIRECTORY . static::PATH_STORAGE_CUSTOMER;
            if (is_dir($dir)) {
                rmdir($dir);
            }

            $file = static::ROOT_DIRECTORY . static::PATH_STORAGE_PRODUCT . static::FILE_STORAGE_PRODUCT;
            if (is_file($file)) {
                unlink($file);
            }

            $dir = static::ROOT_DIRECTORY . static::PATH_STORAGE_PRODUCT;
            if (is_dir($dir)) {
                rmdir($dir);
            }

            $dir = static::ROOT_DIRECTORY . 'images/';
            if (is_dir($dir)) {
                rmdir($dir);
            }

            $dir = static::ROOT_DIRECTORY;
            if (is_dir($dir)) {
                rmdir($dir);
            }

        } catch (\Exception $e) {

        } catch (\Throwable $e) {

        }
    }

}
