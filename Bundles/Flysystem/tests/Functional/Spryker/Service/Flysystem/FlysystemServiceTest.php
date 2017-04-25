<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Service\Flysystem;

use Codeception\Configuration;
use Flysystem\Stub\FlysystemConfigStub;
use Generated\Shared\Transfer\FlysystemResourceMetadataTransfer;
use League\Flysystem\FileNotFoundException;
use PHPUnit_Framework_TestCase;
use Spryker\Service\Flysystem\FlysystemService;
use Spryker\Service\Flysystem\FlysystemServiceFactory;

/**
 * @group Functional
 * @group Spryker
 * @group Service
 * @group Flysystem
 * @group FlysystemServiceTest
 */
class FlysystemServiceTest extends PHPUnit_Framework_TestCase
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

        $flysystemConfig = new FlysystemConfigStub();
        $flysystemFactory = new FlysystemServiceFactory();
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
    public function testReadWithNonExistingFileShouldThrowException()
    {
        $this->expectException(FileNotFoundException::class);

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

        $result = $this->flysystemService->put(
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
        $result = $this->flysystemService->write(
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

        $result = $this->flysystemService->delete(
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

        $result = $this->flysystemService->rename(
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

        $result = $this->flysystemService->copy(
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
        $file = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/' . static::FILE_DOCUMENT;
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

        $result = $this->flysystemService->markAsPrivate(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT
        );

        $isPrivate = $this->flysystemService->isPrivate(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT
        );

        $this->assertTrue($result);
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

        $result = $this->flysystemService->markAsPublic(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT
        );

        $isPrivate = $this->flysystemService->isPrivate(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT
        );

        $this->assertTrue($result);
        $this->assertFalse($isPrivate);
    }

    /**
     * @return void
     */
    public function testCreateDir()
    {
        $dirCreated = $this->flysystemService->createDir(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/bar'
        );

        $dir = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/bar/';
        $isDir = is_dir($dir);

        $this->assertTrue($dirCreated);
        $this->assertTrue($isDir);
    }

    /**
     * @return void
     */
    public function testDeleteDir()
    {
        $dir = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/bar';
        mkdir($dir, 0777, true);

        $result = $this->flysystemService->deleteDir(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/bar'
        );

        $isDir = is_dir($dir);

        $this->assertTrue($result);
        $this->assertFalse($isDir);
    }

    /**
     * @return void
     */
    public function testPutStream()
    {
        $stream = tmpfile();
        fwrite($stream, static::FILE_CONTENT);
        rewind($stream);

        $result = $this->flysystemService->putStream(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
            $stream
        );

        if (is_resource($stream)) {
            fclose($stream);
        }

        $file = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/' . static::FILE_DOCUMENT;
        $isFile = is_file($file);
        $content = file_get_contents($file);

        $this->assertTrue($result);
        $this->assertTrue($isFile);
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

        $result = $this->flysystemService->updateStream(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
            $stream
        );

        if (is_resource($stream)) {
            fclose($stream);
        }

        $file = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/' . static::FILE_DOCUMENT;
        $isFile = is_file($file);
        $content = file_get_contents($file);

        $this->assertTrue($result);
        $this->assertTrue($isFile);
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

        $result = $this->flysystemService->writeStream(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
            $stream
        );

        if (is_resource($stream)) {
            fclose($stream);
        }

        $file = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/' . static::FILE_DOCUMENT;
        $isFile = is_file($file);
        $content = file_get_contents($file);

        $this->assertTrue($result);
        $this->assertTrue($isFile);
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
     * @param null|string $content
     * @param null|string $modifiedTimestamp
     *
     * @return void
     */
    protected function createDocumentFile($content = null, $modifiedTimestamp = null)
    {
        $dir = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $file = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/' . static::FILE_DOCUMENT;

        $h = fopen($file, 'w');
        fwrite($h, $content ?: static::FILE_CONTENT);
        fclose($h);

        if ($modifiedTimestamp) {
            touch($file, $modifiedTimestamp);
        }
    }

    /**
     * @param null|string $content
     * @param null|string $modifiedTimestamp
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

        } catch (\Exception $e) {

        } catch (\Throwable $e) {

        }
    }

}
