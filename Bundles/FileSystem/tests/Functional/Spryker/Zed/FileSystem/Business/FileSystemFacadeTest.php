<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\FileSystem\Business;

use Codeception\Configuration;
use FileSystem\Stub\FileSystemConfigStub;
use PHPUnit_Framework_TestCase;
use Spryker\Zed\FileSystem\Business\FileSystemBusinessFactory;
use Spryker\Zed\FileSystem\Business\FileSystemFacade;

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

    const STORAGE_DOCUMENT = 'customerStorage';
    const STORAGE_PRODUCT_IMAGE = 'productStorage';

    const ROOT_DIRECTORY = 'fileSystemRoot/uploads/';
    const PATH_STORAGE_DOCUMENT = 'documents/';
    const PATH_STORAGE_PRODUCT_IMAGE = 'images/product/';

    const FILE_STORAGE_DOCUMENT = 'customer.txt';
    const FILE_STORAGE_PRODUCT_IMAGE = 'image.png';

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

        $config = new FileSystemConfigStub();

        $this->testDataFileSystemRootDirectory = Configuration::dataDir() . static::ROOT_DIRECTORY;

        $factory = new FileSystemBusinessFactory();
        $factory->setConfig($config);

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
    public function testReadShouldReturnNullWithNonExistentFile()
    {
        $contents = $this->fileSystemFacade->read(
            static::STORAGE_PRODUCT_IMAGE,
            static::RESOURCE_FILE_NAME
        );
        
        $this->assertNull($contents);
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
