<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\FileSystem\Business;

use Codeception\Configuration;
use FileSystem\Stub\FileSystemConfigStub;
use FileSystem\Stub\FlysystemConfigStub;
use Generated\Shared\Transfer\FileSystemContentTransfer;
use Generated\Shared\Transfer\FileSystemCopyTransfer;
use Generated\Shared\Transfer\FileSystemCreateDirectoryTransfer;
use Generated\Shared\Transfer\FileSystemDeleteDirectoryTransfer;
use Generated\Shared\Transfer\FileSystemDeleteTransfer;
use Generated\Shared\Transfer\FileSystemListTransfer;
use Generated\Shared\Transfer\FileSystemQueryTransfer;
use Generated\Shared\Transfer\FileSystemRenameTransfer;
use Generated\Shared\Transfer\FileSystemResourceMetadataTransfer;
use Generated\Shared\Transfer\FileSystemStreamTransfer;
use Generated\Shared\Transfer\FileSystemVisibilityTransfer;
use League\Flysystem\FileNotFoundException;
use PHPUnit_Framework_TestCase;
use Spryker\Service\Flysystem\FlysystemService;
use Spryker\Service\Flysystem\FlysystemServiceFactory;
use Spryker\Zed\FileSystem\Business\FileSystemBusinessFactory;
use Spryker\Zed\FileSystem\Business\FileSystemFacade;
use Spryker\Zed\FileSystem\Dependency\Service\FileSystemToFlysystemBridge;
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
        $fileSystemQueryTransfer = $this->createDocumentQueryTransfer();
        $fileSystemQueryTransfer->setPath('invalid filename');

        $result = $this->fileSystemFacade->has($fileSystemQueryTransfer);

        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testHasShouldReturnTrueWithExistingFile()
    {
        $fileSystemQueryTransfer = $this->createDocumentQueryTransfer();
        $this->createDocumentFile();

        $result = $this->fileSystemFacade->has($fileSystemQueryTransfer);

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testReadWithNonExistingFileShouldThrowException()
    {
        $fileSystemQueryTransfer = $this->createDocumentQueryTransfer();
        $fileSystemQueryTransfer->setPath('invalid path');

        $this->expectException(FileNotFoundException::class);

        $contents = $this->fileSystemFacade->read($fileSystemQueryTransfer);

        $this->assertNull($contents);
    }

    /**
     * @return void
     */
    public function testReadWithExistingFileShouldReturnContent()
    {
        $fileSystemQueryTransfer = $this->createDocumentQueryTransfer();
        $this->createDocumentFile();

        $contents = $this->fileSystemFacade->read($fileSystemQueryTransfer);

        $this->assertSame(static::FILE_CONTENT, $contents);
    }

    /**
     * @return void
     */
    public function testPut()
    {
        $fileSystemContentTransfer = $this->createContentTransfer();
        $this->createDocumentFile('Lorem Ipsum');

        $result = $this->fileSystemFacade->put($fileSystemContentTransfer);

        $this->assertTrue($result);
        $this->assertSame(static::FILE_CONTENT, $this->getDocumentFileContent());
    }

    /**
     * @return void
     */
    public function testWrite()
    {
        $fileSystemContentTransfer = $this->createContentTransfer();

        $result = $this->fileSystemFacade->write($fileSystemContentTransfer);

        $this->assertTrue($result);
        $this->assertSame(static::FILE_CONTENT, $this->getDocumentFileContent());
    }

    /**
     * @return void
     */
    public function testDelete()
    {
        $fileSystemDeleteTransfer = new FileSystemDeleteTransfer();
        $fileSystemDeleteTransfer->setFileSystemName(static::FILE_SYSTEM_DOCUMENT);
        $fileSystemDeleteTransfer->setPath('foo/' . static::FILE_DOCUMENT);

        $this->createDocumentFile();

        $result = $this->fileSystemFacade->delete($fileSystemDeleteTransfer);

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testRename()
    {
        $fileSystemRenameTransfer = new FileSystemRenameTransfer();
        $fileSystemRenameTransfer->setFileSystemName(static::FILE_SYSTEM_DOCUMENT);
        $fileSystemRenameTransfer->setPath('foo/' . static::FILE_DOCUMENT);
        $fileSystemRenameTransfer->setNewPath('foo/NEW_' . static::FILE_DOCUMENT);
        $this->createDocumentFile();

        $result = $this->fileSystemFacade->rename($fileSystemRenameTransfer);

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
        $fileSystemCopyTransfer = new FileSystemCopyTransfer();
        $fileSystemCopyTransfer->setFileSystemName(static::FILE_SYSTEM_DOCUMENT);
        $fileSystemCopyTransfer->setSourcePath('foo/' . static::FILE_DOCUMENT);
        $fileSystemCopyTransfer->setDestinationPath('foo/NEW_' . static::FILE_DOCUMENT);

        $this->createDocumentFile();

        $result = $this->fileSystemFacade->copy($fileSystemCopyTransfer);

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
        $fileSystemQueryTransfer = $this->createDocumentQueryTransfer();

        $metadataTransfer = $this->fileSystemFacade->getMetadata($fileSystemQueryTransfer);

        $this->assertInstanceOf(FileSystemResourceMetadataTransfer::class, $metadataTransfer);
    }

    /**
     * @return void
     */
    public function testGetMimeType()
    {
        $this->createDocumentFile();
        $fileSystemQueryTransfer = $this->createDocumentQueryTransfer();

        $mimeType = $this->fileSystemFacade->getMimetype($fileSystemQueryTransfer);

        $this->assertSame('text/plain', $mimeType);
    }

    /**
     * @return void
     */
    public function testGetTimestamp()
    {
        $fileSystemQueryTransfer = $this->createDocumentQueryTransfer();

        $timestampExpected = time();
        $this->createDocumentFile(null, $timestampExpected);

        $timestamp = $this->fileSystemFacade->getTimestamp($fileSystemQueryTransfer);

        $this->assertSame($timestamp, $timestampExpected);
    }

    /**
     * @return void
     */
    public function testGetSize()
    {
        $fileSystemQueryTransfer = $this->createDocumentQueryTransfer();
        $this->createDocumentFile();

        $file = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/' . static::FILE_DOCUMENT;
        $sizeExpected = filesize($file);

        $size = $this->fileSystemFacade->getSize($fileSystemQueryTransfer);

        $this->assertSame($sizeExpected, $size);
    }

    /**
     * @return void
     */
    public function testIsPrivate()
    {
        $fileSystemQueryTransfer = $this->createDocumentQueryTransfer();
        $this->createDocumentFile();

        $isPrivate = $this->fileSystemFacade->isPrivate($fileSystemQueryTransfer);

        $this->assertFalse($isPrivate);
    }

    /**
     * @return void
     */
    public function testMarkAsPrivate()
    {
        $fileSystemQueryTransfer = $this->createDocumentQueryTransfer();
        $fileSystemVisibilityTransfer = $this->createDocumentVisibilityTransfer();

        $this->createDocumentFile();

        $isPrivate = $this->fileSystemFacade->isPrivate($fileSystemQueryTransfer);
        $this->assertFalse($isPrivate);

        $this->fileSystemFacade->markAsPrivate($fileSystemVisibilityTransfer);

        $isPrivate = $this->fileSystemFacade->isPrivate($fileSystemQueryTransfer);
        $this->assertTrue($isPrivate);
    }

    /**
     * @return void
     */
    public function testMarkAsPublic()
    {
        $fileSystemQueryTransfer = $this->createDocumentQueryTransfer();
        $fileSystemVisibilityTransfer = $this->createDocumentVisibilityTransfer();

        $this->createDocumentFile();

        $this->fileSystemFacade->markAsPublic($fileSystemVisibilityTransfer);

        $isPublic = $this->fileSystemFacade->isPrivate($fileSystemQueryTransfer);
        $this->assertFalse($isPublic);
    }

    /**
     * @return void
     */
    public function testCreateDirectory()
    {
        $fileSystemCreateDirectoryTransfer = new FileSystemCreateDirectoryTransfer();
        $fileSystemCreateDirectoryTransfer->setFileSystemName(static::FILE_SYSTEM_DOCUMENT);
        $fileSystemCreateDirectoryTransfer->setPath('/foo/bar');

        $dirCreated = $this->fileSystemFacade->createDirectory($fileSystemCreateDirectoryTransfer);

        $dir = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/bar/';
        $isDir = is_dir($dir);

        $this->assertTrue($dirCreated);
        $this->assertTrue($isDir);
    }

    /**
     * @return void
     */
    public function testDeleteDirectory()
    {
        $fileSystemDeleteDirectoryTransfer = new FileSystemDeleteDirectoryTransfer();
        $fileSystemDeleteDirectoryTransfer->setFileSystemName(static::FILE_SYSTEM_DOCUMENT);
        $fileSystemDeleteDirectoryTransfer->setPath('foo/bar');

        $dir = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/bar';
        mkdir($dir, 0777, true);

        $dirDeleted = $this->fileSystemFacade->deleteDirectory($fileSystemDeleteDirectoryTransfer);

        $isDir = is_dir($dir);

        $this->assertTrue($dirDeleted);
        $this->assertFalse($isDir);
    }

    /**
     * @return void
     */
    public function testPutStream()
    {
        $fileSystemStreamTransfer = $this->createStreamTransfer();

        $stream = tmpfile();
        fwrite($stream, static::FILE_CONTENT);
        rewind($stream);

        $result = $this->fileSystemFacade->putStream($fileSystemStreamTransfer, $stream);

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
        $fileSystemStreamTransfer = $this->createStreamTransfer();
        $this->createDocumentFile();

        $stream = $this->fileSystemFacade->readStream($fileSystemStreamTransfer);

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
        $fileSystemStreamTransfer = $this->createStreamTransfer();
        $this->createDocumentFile();
        $this->createDocumentFileInRoot('Lorem Ipsum');

        $file = $this->testDataFileSystemRootDirectory . static::FILE_DOCUMENT;
        $stream = fopen($file, 'r+');

        $result = $this->fileSystemFacade->updateStream($fileSystemStreamTransfer, $stream);

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
        $fileSystemStreamTransfer = $this->createStreamTransfer();
        $this->createDocumentFileInRoot();
        $file = $this->testDataFileSystemRootDirectory . static::FILE_DOCUMENT;
        $stream = fopen($file, 'r+');

        $result = $this->fileSystemFacade->writeStream($fileSystemStreamTransfer, $stream);

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
        $fileSystemListTransfer = new FileSystemListTransfer();
        $fileSystemListTransfer->setFileSystemName(static::FILE_SYSTEM_DOCUMENT);
        $fileSystemListTransfer->setPath('/');
        $fileSystemListTransfer->setRecursive(true);

        $this->createDocumentFile();

        $content = $this->fileSystemFacade->listContents($fileSystemListTransfer);

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

    /**
     * @return \Generated\Shared\Transfer\FileSystemQueryTransfer
     */
    protected function createDocumentQueryTransfer()
    {
        $fileSystemQueryTransfer = new FileSystemQueryTransfer();
        $fileSystemQueryTransfer->setFileSystemName(static::FILE_SYSTEM_DOCUMENT);
        $fileSystemQueryTransfer->setPath('/foo/' . static::FILE_DOCUMENT);

        return $fileSystemQueryTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\FileSystemVisibilityTransfer
     */
    protected function createDocumentVisibilityTransfer()
    {
        $fileSystemVisibilityTransfer = new FileSystemVisibilityTransfer();
        $fileSystemVisibilityTransfer->setFileSystemName(static::FILE_SYSTEM_DOCUMENT);
        $fileSystemVisibilityTransfer->setPath('/foo/' . static::FILE_DOCUMENT);

        return $fileSystemVisibilityTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\FileSystemContentTransfer
     */
    protected function createContentTransfer()
    {
        $fileSystemContentTransfer = new FileSystemContentTransfer();
        $fileSystemContentTransfer->setFileSystemName(static::FILE_SYSTEM_DOCUMENT);
        $fileSystemContentTransfer->setPath('foo/' . static::FILE_DOCUMENT);
        $fileSystemContentTransfer->setContent(static::FILE_CONTENT);

        return $fileSystemContentTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\FileSystemStreamTransfer
     */
    protected function createStreamTransfer()
    {
        $fileSystemStreamTransfer = new FileSystemStreamTransfer();
        $fileSystemStreamTransfer->setFileSystemName(static::FILE_SYSTEM_DOCUMENT);
        $fileSystemStreamTransfer->setPath('foo/' . static::FILE_DOCUMENT);

        return $fileSystemStreamTransfer;
    }

}
