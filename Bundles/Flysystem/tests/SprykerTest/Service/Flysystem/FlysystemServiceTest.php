<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Flysystem;

use Codeception\Configuration;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\FlysystemResourceMetadataTransfer;
use Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException;
use Spryker\Service\Flysystem\FlysystemDependencyProvider;
use Spryker\Service\Flysystem\FlysystemService;
use Spryker\Service\Flysystem\FlysystemServiceFactory;
use Spryker\Service\FlysystemLocalFileSystem\Plugin\Flysystem\LocalFilesystemBuilderPlugin;
use Spryker\Service\Kernel\Container;
use SprykerTest\Service\Flysystem\Stub\FlysystemConfigStub;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Service
 * @group Flysystem
 * @group FlysystemServiceTest
 * Add your own group annotations below this line
 */
class FlysystemServiceTest extends Unit
{
    public const RESOURCE_FILE_NAME = 'fileName.jpg';

    public const FILE_SYSTEM_DOCUMENT = 'customerFileSystem';
    public const FILE_SYSTEM_PRODUCT_IMAGE = 'productFileSystem';

    public const ROOT_DIRECTORY = 'fileSystemRoot/uploads/';
    public const PATH_DOCUMENT = 'documents/';
    public const PATH_PRODUCT_IMAGE = 'images/product/';

    public const FILE_DOCUMENT = 'customer.txt';
    public const FILE_PRODUCT_IMAGE = 'image.png';

    public const FILE_CONTENT = 'Hello World';

    /**
     * @var \Spryker\Service\Flysystem\FlysystemServiceInterface
     */
    protected $flysystemService;

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

        $container = new Container();
        $container[FlysystemDependencyProvider::PLUGIN_COLLECTION_FILESYSTEM_BUILDER] = function (Container $container) {
            return [
                new LocalFilesystemBuilderPlugin(),
            ];
        };

        $container[FlysystemDependencyProvider::PLUGIN_COLLECTION_FLYSYSTEM] = function (Container $container) {
            return [];
        };

        $flysystemConfig = new FlysystemConfigStub();

        $flysystemFactory = new FlysystemServiceFactory();
        $flysystemFactory->setContainer($container);
        $flysystemFactory->setConfig($flysystemConfig);

        $flysystemService = new FlysystemService();
        $flysystemService->setFactory($flysystemFactory);

        $this->flysystemService = $flysystemService;
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
        $result = $this->flysystemService->has(
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

        $result = $this->flysystemService->has(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT
        );

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testReadWithNonExistingFileShouldThrowFileSystemException()
    {
        $this->expectException(FileSystemReadException::class);

        $contents = $this->flysystemService->read(
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

        $contents = $this->flysystemService->read(
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

        $this->flysystemService->put(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
            static::FILE_CONTENT
        );

        $content = $this->getDocumentFileContent();

        $this->assertSame(static::FILE_CONTENT, $content);
    }

    /**
     * @return void
     */
    public function testWrite()
    {
        $this->flysystemService->write(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
            static::FILE_CONTENT
        );

        $file = $this->getLocalDocumentFile();
        $fileContent = file_get_contents($file);
        $this->assertSame(static::FILE_CONTENT, $fileContent);
    }

    /**
     * @return void
     */
    public function testDelete()
    {
        $this->createDocumentFile();

        $this->flysystemService->delete(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT
        );

        $file = $this->getLocalDocumentFile();
        $this->assertFileNotExists($file);
    }

    /**
     * @return void
     */
    public function testRename()
    {
        $this->createDocumentFile();

        $this->flysystemService->rename(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
            'foo/' . 'NEW_' . static::FILE_DOCUMENT
        );

        $originalFile = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/' . static::FILE_DOCUMENT;
        $renamedFile = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/NEW_' . static::FILE_DOCUMENT;

        $this->assertFileNotExists($originalFile);
        $this->assertFileExists($renamedFile);
    }

    /**
     * @return void
     */
    public function testCopy()
    {
        $this->createDocumentFile();

        $this->flysystemService->copy(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
            'foo/' . 'NEW_' . static::FILE_DOCUMENT
        );

        $originalFile = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/' . static::FILE_DOCUMENT;
        $copiedFile = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/NEW_' . static::FILE_DOCUMENT;

        $this->assertFileExists($originalFile);
        $this->assertFileExists($copiedFile);
    }

    /**
     * @return void
     */
    public function testGetMetadata()
    {
        $this->createDocumentFile();

        $metadataTransfer = $this->flysystemService->getMetadata(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT
        );

        $this->assertInstanceOf(FlysystemResourceMetadataTransfer::class, $metadataTransfer);
    }

    /**
     * @return void
     */
    public function testGetMimeType()
    {
        $this->createDocumentFile();

        $mimeType = $this->flysystemService->getMimetype(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT
        );

        $this->assertSame('text/plain', $mimeType);
    }

    /**
     * @return void
     */
    public function testGetTimestamp()
    {
        $timestampExpected = time();
        $this->createDocumentFile(null, $timestampExpected);

        $timestamp = $this->flysystemService->getTimestamp(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT
        );

        $this->assertSame($timestamp, $timestampExpected);
    }

    /**
     * @return void
     */
    public function testGetSize()
    {
        $this->createDocumentFile();
        $file = $this->getLocalDocumentFile();
        $sizeExpected = filesize($file);

        $size = $this->flysystemService->getSize(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT
        );

        $this->assertSame($sizeExpected, $size);
    }

    /**
     * @return void
     */
    public function testIsPrivate()
    {
        $this->createDocumentFile();

        $isPrivate = $this->flysystemService->isPrivate(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT
        );

        $this->assertFalse($isPrivate);
    }

    /**
     * @return void
     */
    public function testMarkAsPrivate()
    {
        $this->createDocumentFile();

        $isPrivate = $this->flysystemService->isPrivate(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT
        );

        $this->assertFalse($isPrivate);

        $this->flysystemService->markAsPrivate(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT
        );

        $isPrivate = $this->flysystemService->isPrivate(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT
        );

        $this->assertTrue($isPrivate);
    }

    /**
     * @return void
     */
    public function testIsPublic()
    {
        $this->createDocumentFile();

        $isPrivate = $this->flysystemService->isPrivate(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT
        );

        $this->assertFalse($isPrivate);
    }

    /**
     * @return void
     */
    public function testMarkAsPublic()
    {
        $this->createDocumentFile();

        $this->flysystemService->markAsPublic(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT
        );

        $isPrivate = $this->flysystemService->isPrivate(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT
        );

        $this->assertFalse($isPrivate);
    }

    /**
     * @return void
     */
    public function testCreateDir()
    {
        $this->flysystemService->createDir(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/bar'
        );

        $dir = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/bar/';
        $this->assertDirectoryExists($dir);
    }

    /**
     * @return void
     */
    public function testDeleteDir()
    {
        $dir = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/bar';
        mkdir($dir, 0777, true);

        $this->flysystemService->deleteDir(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/bar'
        );

        $this->assertDirectoryNotExists($dir);
    }

    /**
     * @return void
     */
    public function testPutStream()
    {
        $stream = tmpfile();
        fwrite($stream, static::FILE_CONTENT);
        rewind($stream);

        $this->flysystemService->putStream(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
            $stream
        );

        if (is_resource($stream)) {
            fclose($stream);
        }

        $file = $this->getLocalDocumentFile();
        $content = file_get_contents($file);

        $this->assertFileExists($file);
        $this->assertSame(static::FILE_CONTENT, $content);
    }

    /**
     * @return void
     */
    public function testReadStream()
    {
        $this->createDocumentFile();

        $stream = $this->flysystemService->readStream(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT
        );

        $content = stream_get_contents($stream);
        if (is_resource($stream)) {
            fclose($stream);
        }

        $this->assertSame(static::FILE_CONTENT, $content);
    }

    /**
     * @return void
     */
    public function testUpdateStream()
    {
        $this->createDocumentFile();
        $this->createDocumentFileInRoot('Lorem Ipsum');

        $file = $this->testDataFileSystemRootDirectory . static::FILE_DOCUMENT;
        $stream = fopen($file, 'r+');

        $this->flysystemService->updateStream(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
            $stream
        );

        if (is_resource($stream)) {
            fclose($stream);
        }

        $file = $this->getLocalDocumentFile();
        $content = file_get_contents($file);

        $this->assertFileExists($file);
        $this->assertSame('Lorem Ipsum', $content);
    }

    /**
     * @return void
     */
    public function testWriteStream()
    {
        $this->createDocumentFileInRoot();
        $file = $this->testDataFileSystemRootDirectory . static::FILE_DOCUMENT;
        $stream = fopen($file, 'r+');

        $this->flysystemService->writeStream(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
            $stream
        );

        if (is_resource($stream)) {
            fclose($stream);
        }

        $file = $this->getLocalDocumentFile();
        $content = file_get_contents($file);

        $this->assertFileExists($file);
        $this->assertSame(static::FILE_CONTENT, $content);
    }

    /**
     * @return void
     */
    public function testListContents()
    {
        $this->createDocumentFile();

        $content = $this->flysystemService->listContents(
            static::FILE_SYSTEM_DOCUMENT,
            '/',
            true
        );

        $this->assertGreaterThan(0, count($content));
    }

    /**
     * @param string|null $content
     * @param string|null $modifiedTimestamp
     *
     * @return void
     */
    protected function createDocumentFile($content = null, $modifiedTimestamp = null)
    {
        $dir = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $file = $this->getLocalDocumentFile();

        $h = fopen($file, 'w');
        fwrite($h, $content ?: static::FILE_CONTENT);
        fclose($h);

        if ($modifiedTimestamp) {
            touch($file, $modifiedTimestamp);
        }
    }

    /**
     * @param string|null $content
     * @param string|null $modifiedTimestamp
     *
     * @return void
     */
    protected function createDocumentFileInRoot($content = null, $modifiedTimestamp = null)
    {
        $file = $this->testDataFileSystemRootDirectory . static::FILE_DOCUMENT;

        $h = fopen($file, 'w');
        fwrite($h, $content ?: static::FILE_CONTENT);
        fclose($h);

        if ($modifiedTimestamp) {
            touch($file, $modifiedTimestamp);
        }
    }

    /**
     * @return bool|string
     */
    protected function getDocumentFileContent()
    {
        $file = $this->getLocalDocumentFile();
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
        $file = $this->getLocalDocumentFile();
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

        $dir = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/bar';
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

        $file = $this->testDataFileSystemRootDirectory . static::FILE_DOCUMENT;
        if (is_file($file)) {
            unlink($file);
        }
    }

    /**
     * @return string
     */
    protected function getLocalDocumentFile()
    {
        return $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/' . static::FILE_DOCUMENT;
    }
}
