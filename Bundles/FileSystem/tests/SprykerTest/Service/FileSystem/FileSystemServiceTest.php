<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\FileSystem;

use Codeception\Configuration;
use Codeception\Test\Unit;
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
use PHPUnit\Framework\Assert;
use Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException;
use Spryker\Service\FileSystem\FileSystemDependencyProvider;
use Spryker\Service\FileSystem\FileSystemService;
use Spryker\Service\FileSystem\FileSystemServiceFactory;
use Spryker\Service\Flysystem\FlysystemDependencyProvider;
use Spryker\Service\Flysystem\FlysystemService;
use Spryker\Service\Flysystem\FlysystemServiceFactory;
use Spryker\Service\Flysystem\Plugin\FileSystem\FileSystemReaderPlugin;
use Spryker\Service\Flysystem\Plugin\FileSystem\FileSystemStreamPlugin;
use Spryker\Service\Flysystem\Plugin\FileSystem\FileSystemWriterPlugin;
use Spryker\Service\FlysystemLocalFileSystem\Plugin\Flysystem\LocalFilesystemBuilderPlugin;
use Spryker\Service\Kernel\Container;
use SprykerTest\Service\FileSystem\Stub\FileSystemConfigStub;
use SprykerTest\Service\FileSystem\Stub\FlysystemConfigStub;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group FileSystem
 * @group FileSystemServiceTest
 * Add your own group annotations below this line
 */
class FileSystemServiceTest extends Unit
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
    protected function setUp(): void
    {
        parent::setUp();

        $this->testDataFileSystemRootDirectory = Configuration::dataDir() . static::ROOT_DIRECTORY;

        $container = new Container();
        $container = $this->setupContainerAndFlysystemService($container);

        $config = new FileSystemConfigStub();
        $factory = new FileSystemServiceFactory();
        $factory->setConfig($config);
        $factory->setContainer($container);

        $this->fileSystemService = new FileSystemService();
        $this->fileSystemService->setFactory($factory);
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function setupContainerAndFlysystemService(Container $container): Container
    {
        $flysystemContainer = new Container();
        $flysystemContainer[FlysystemDependencyProvider::PLUGIN_COLLECTION_FILESYSTEM_BUILDER] = function () {
            return [
                new LocalFilesystemBuilderPlugin(),
            ];
        };

        $flysystemContainer[FlysystemDependencyProvider::PLUGIN_COLLECTION_FLYSYSTEM] = function () {
            return [];
        };

        $flysystemConfig = new FlysystemConfigStub();

        $flysystemFactory = new FlysystemServiceFactory();
        $flysystemFactory->setContainer($flysystemContainer);
        $flysystemFactory->setConfig($flysystemConfig);

        $flysystemService = new FlysystemService();
        $flysystemService->setFactory($flysystemFactory);

        $fileSystemReaderPlugin = new FileSystemReaderPlugin();
        $fileSystemReaderPlugin->setService($flysystemService);

        $fileSystemWriterPlugin = new FileSystemWriterPlugin();
        $fileSystemWriterPlugin->setService($flysystemService);

        $fileSystemStreamPlugin = new FileSystemStreamPlugin();
        $fileSystemStreamPlugin->setService($flysystemService);

        $container[FileSystemDependencyProvider::PLUGIN_READER] = function (Container $container) use ($fileSystemReaderPlugin) {
            return $fileSystemReaderPlugin;
        };

        $container[FileSystemDependencyProvider::PLUGIN_WRITER] = function (Container $container) use ($fileSystemWriterPlugin) {
            return $fileSystemWriterPlugin;
        };

        $container[FileSystemDependencyProvider::PLUGIN_STREAM] = function (Container $container) use ($fileSystemStreamPlugin) {
            return $fileSystemStreamPlugin;
        };

        return $container;
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        $this->directoryCleanup();
    }

    /**
     * @return void
     */
    public function testHasShouldReturnFalseWithNonExistingFile(): void
    {
        $fileSystemQueryTransfer = $this->createDocumentQueryTransfer();
        $fileSystemQueryTransfer->setPath('invalid filename');

        $has = $this->fileSystemService->has($fileSystemQueryTransfer);

        $this->assertFalse($has);
    }

    /**
     * @return void
     */
    public function testHasShouldReturnTrueWithExistingFile(): void
    {
        $fileSystemQueryTransfer = $this->createDocumentQueryTransfer();
        $this->createDocumentFile();

        $has = $this->fileSystemService->has($fileSystemQueryTransfer);

        $this->assertTrue($has);
    }

    /**
     * @return void
     */
    public function testReadWithNonExistingFileShouldThrowException(): void
    {
        $fileSystemQueryTransfer = $this->createDocumentQueryTransfer();
        $fileSystemQueryTransfer->setPath('invalid path');

        $this->expectException(FileSystemReadException::class);

        $this->fileSystemService->read($fileSystemQueryTransfer);
    }

    /**
     * @return void
     */
    public function testReadWithExistingFileShouldReturnContent(): void
    {
        $fileSystemQueryTransfer = $this->createDocumentQueryTransfer();
        $this->createDocumentFile();

        $contents = $this->fileSystemService->read($fileSystemQueryTransfer);

        $this->assertSame(static::FILE_CONTENT, $contents);
    }

    /**
     * @return void
     */
    public function testPut(): void
    {
        $fileSystemContentTransfer = $this->createContentTransfer();
        $this->createDocumentFile('Lorem Ipsum');

        $this->fileSystemService->put($fileSystemContentTransfer);

        $this->assertSame(static::FILE_CONTENT, $this->getDocumentFileContent());
    }

    /**
     * @return void
     */
    public function testWrite(): void
    {
        $fileSystemContentTransfer = $this->createContentTransfer();

        $this->fileSystemService->write($fileSystemContentTransfer);

        $this->assertSame(static::FILE_CONTENT, $this->getDocumentFileContent());
    }

    /**
     * @return void
     */
    public function testDelete(): void
    {
        $fileSystemDeleteTransfer = new FileSystemDeleteTransfer();
        $fileSystemDeleteTransfer->setFileSystemName(static::FILE_SYSTEM_DOCUMENT);
        $fileSystemDeleteTransfer->setPath('foo/' . static::FILE_DOCUMENT);
        $this->createDocumentFile();

        $this->fileSystemService->delete($fileSystemDeleteTransfer);

        $this->assertFileNotExists($this->getDocumentFIleName());
    }

    /**
     * @return void
     */
    public function testRename(): void
    {
        $fileSystemRenameTransfer = new FileSystemRenameTransfer();
        $fileSystemRenameTransfer->setFileSystemName(static::FILE_SYSTEM_DOCUMENT);
        $fileSystemRenameTransfer->setPath('foo/' . static::FILE_DOCUMENT);
        $fileSystemRenameTransfer->setNewPath('foo/NEW_' . static::FILE_DOCUMENT);
        $this->createDocumentFile();

        $this->fileSystemService->rename($fileSystemRenameTransfer);

        $originalFile = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/' . static::FILE_DOCUMENT;
        $renamedFile = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/NEW_' . static::FILE_DOCUMENT;

        $this->assertFileNotExists($originalFile);
        $this->assertFileExists($renamedFile);
    }

    /**
     * @return void
     */
    public function testUpdate(): void
    {
        $this->createDocumentFile('Foo Bar');
        $fileSystemContentTransfer = $this->createContentTransfer();

        $this->fileSystemService->update($fileSystemContentTransfer);

        $this->assertSame(static::FILE_CONTENT, $this->getDocumentFileContent());
    }

    /**
     * @return void
     */
    public function testCopy(): void
    {
        $fileSystemCopyTransfer = new FileSystemCopyTransfer();
        $fileSystemCopyTransfer->setFileSystemName(static::FILE_SYSTEM_DOCUMENT);
        $fileSystemCopyTransfer->setSourcePath('foo/' . static::FILE_DOCUMENT);
        $fileSystemCopyTransfer->setDestinationPath('foo/NEW_' . static::FILE_DOCUMENT);
        $this->createDocumentFile();

        $this->fileSystemService->copy($fileSystemCopyTransfer);

        $originalFile = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/' . static::FILE_DOCUMENT;
        $copiedFile = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/NEW_' . static::FILE_DOCUMENT;

        $this->assertFileExists($originalFile);
        $this->assertFileExists($copiedFile);
    }

    /**
     * @return void
     */
    public function testGetMetadata(): void
    {
        $this->createDocumentFile();
        $fileSystemQueryTransfer = $this->createDocumentQueryTransfer();

        $metadataTransfer = $this->fileSystemService->getMetadata($fileSystemQueryTransfer);

        $this->assertInstanceOf(FileSystemResourceMetadataTransfer::class, $metadataTransfer);
    }

    /**
     * @return void
     */
    public function testGetMimeType(): void
    {
        $this->createDocumentFile();
        $fileSystemQueryTransfer = $this->createDocumentQueryTransfer();

        $mimeType = $this->fileSystemService->getMimetype($fileSystemQueryTransfer);

        $this->assertSame('text/plain', $mimeType);
    }

    /**
     * @return void
     */
    public function testGetTimestamp(): void
    {
        $fileSystemQueryTransfer = $this->createDocumentQueryTransfer();

        $timestampExpected = time();
        $this->createDocumentFile(null, $timestampExpected);

        $timestamp = $this->fileSystemService->getTimestamp($fileSystemQueryTransfer);

        $this->assertSame($timestamp, $timestampExpected);
    }

    /**
     * @return void
     */
    public function testGetSize(): void
    {
        $fileSystemQueryTransfer = $this->createDocumentQueryTransfer();
        $this->createDocumentFile();

        $file = $this->getDocumentFIleName();
        $sizeExpected = filesize($file);

        $size = $this->fileSystemService->getSize($fileSystemQueryTransfer);

        $this->assertSame($sizeExpected, $size);
    }

    /**
     * @return void
     */
    public function testIsPrivate(): void
    {
        $fileSystemQueryTransfer = $this->createDocumentQueryTransfer();
        $this->createDocumentFile();

        $isPrivate = $this->fileSystemService->isPrivate($fileSystemQueryTransfer);

        $this->assertFalse($isPrivate);
    }

    /**
     * @return void
     */
    public function testMarkAsPrivate(): void
    {
        $fileSystemQueryTransfer = $this->createDocumentQueryTransfer();
        $fileSystemVisibilityTransfer = $this->createDocumentVisibilityTransfer();

        $this->createDocumentFile();

        $isPrivate = $this->fileSystemService->isPrivate($fileSystemQueryTransfer);
        $this->assertFalse($isPrivate);

        $this->fileSystemService->markAsPrivate($fileSystemVisibilityTransfer);

        $isPrivate = $this->fileSystemService->isPrivate($fileSystemQueryTransfer);
        $this->assertTrue($isPrivate);
    }

    /**
     * @return void
     */
    public function testMarkAsPublic(): void
    {
        $fileSystemQueryTransfer = $this->createDocumentQueryTransfer();
        $fileSystemVisibilityTransfer = $this->createDocumentVisibilityTransfer();

        $this->createDocumentFile();

        $this->fileSystemService->markAsPublic($fileSystemVisibilityTransfer);

        $isPublic = $this->fileSystemService->isPrivate($fileSystemQueryTransfer);
        $this->assertFalse($isPublic);
    }

    /**
     * @return void
     */
    public function testCreateDirectory(): void
    {
        $fileSystemCreateDirectoryTransfer = new FileSystemCreateDirectoryTransfer();
        $fileSystemCreateDirectoryTransfer->setFileSystemName(static::FILE_SYSTEM_DOCUMENT);
        $fileSystemCreateDirectoryTransfer->setPath('/foo/bar');

        $this->fileSystemService->createDirectory($fileSystemCreateDirectoryTransfer);

        $dir = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/bar/';
        $this->assertDirectoryExists($dir);
    }

    /**
     * @return void
     */
    public function testDeleteDirectory(): void
    {
        $fileSystemDeleteDirectoryTransfer = new FileSystemDeleteDirectoryTransfer();
        $fileSystemDeleteDirectoryTransfer->setFileSystemName(static::FILE_SYSTEM_DOCUMENT);
        $fileSystemDeleteDirectoryTransfer->setPath('foo/bar');

        $dir = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/bar';
        mkdir($dir, 0777, true);

        $this->fileSystemService->deleteDirectory($fileSystemDeleteDirectoryTransfer);

        $this->assertDirectoryDoesNotExist($dir);
    }

    /**
     * @deprecated Will be removed once PHPUnit 8 support is dropped.
     *
     * @param string $directory
     * @param string $message
     *
     * @return void
     */
    public static function assertDirectoryDoesNotExist(string $directory, string $message = ''): void
    {
        if (method_exists(Assert::class, 'assertDirectoryDoesNotExist')) {
            parent::assertDirectoryDoesNotExist($directory, $message);

            return;
        }

        static::assertDirectoryNotExists($directory, $message);
    }

    /**
     * @return void
     */
    public function testPutStream(): void
    {
        $fileSystemStreamTransfer = $this->createStreamTransfer();

        $stream = tmpfile();
        fwrite($stream, static::FILE_CONTENT);
        rewind($stream);

        $this->fileSystemService->putStream($fileSystemStreamTransfer, $stream);

        if ($stream !== false) {
            fclose($stream);
        }

        $file = $this->getDocumentFIleName();
        $content = file_get_contents($file);

        $this->assertFileExists($file);
        $this->assertSame(static::FILE_CONTENT, $content);
    }

    /**
     * @return void
     */
    public function testReadStream(): void
    {
        $fileSystemStreamTransfer = $this->createStreamTransfer();
        $this->createDocumentFile();

        $stream = $this->fileSystemService->readStream($fileSystemStreamTransfer);

        $content = stream_get_contents($stream);
        if ($stream !== false) {
            fclose($stream);
        }

        $this->assertSame(static::FILE_CONTENT, $content);
    }

    /**
     * @return void
     */
    public function testUpdateStream(): void
    {
        $fileSystemStreamTransfer = $this->createStreamTransfer();
        $this->createDocumentFile();
        $this->createDocumentFileInRoot('Lorem Ipsum');

        $file = $this->testDataFileSystemRootDirectory . static::FILE_DOCUMENT;
        $stream = fopen($file, 'r+');

        $this->fileSystemService->updateStream($fileSystemStreamTransfer, $stream);

        if ($stream !== false) {
            fclose($stream);
        }

        $file = $this->getDocumentFIleName();
        $content = file_get_contents($file);

        $this->assertFileExists($file);
        $this->assertSame('Lorem Ipsum', $content);
    }

    /**
     * @return void
     */
    public function testWriteStream(): void
    {
        $fileSystemStreamTransfer = $this->createStreamTransfer();
        $this->createDocumentFileInRoot();
        $file = $this->testDataFileSystemRootDirectory . static::FILE_DOCUMENT;
        $stream = fopen($file, 'r+');

        $this->fileSystemService->writeStream($fileSystemStreamTransfer, $stream);

        if ($stream !== false) {
            fclose($stream);
        }

        $file = $this->getDocumentFIleName();
        $content = file_get_contents($file);

        $this->assertFileExists($file);
        $this->assertSame(static::FILE_CONTENT, $content);
    }

    /**
     * @return void
     */
    public function testListContentsWithoutRecursiveShouldReturnOnlyFirstLevelFiles(): void
    {
        // Arrange
        $fileSystemListTransfer = (new FileSystemListTransfer())
            ->setFileSystemName(static::FILE_SYSTEM_DOCUMENT)
            ->setPath('/')
            ->setRecursive(false);

        $this->createDocumentFile();
        $this->createDocumentFileInRoot();

        // Act
        $content = $this->fileSystemService->listContents($fileSystemListTransfer);

        // Assert
        $this->assertCount(1, $content);
    }

    /**
     * @return void
     */
    public function testListContentsWithRecursiveShouldReturnAllLevelsFiles(): void
    {
        // Arrange
        $fileSystemListTransfer = (new FileSystemListTransfer())
            ->setFileSystemName(static::FILE_SYSTEM_DOCUMENT)
            ->setPath('/')
            ->setRecursive(true);

        $this->createDocumentFile();
        $this->createDocumentFileInRoot();

        // Act
        $content = $this->fileSystemService->listContents($fileSystemListTransfer);

        // Assert
        $this->assertCount(2, $content);
    }

    /**
     * @param string|null $content
     * @param string|null $modifiedTimestamp
     *
     * @return void
     */
    protected function createDocumentFile(?string $content = null, ?string $modifiedTimestamp = null): void
    {
        $dir = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $file = $this->getDocumentFIleName();

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
    protected function createDocumentFileInRoot(?string $content = null, ?string $modifiedTimestamp = null): void
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
        $file = $this->getDocumentFIleName();
        if (!is_file($file)) {
            return false;
        }

        return file_get_contents($file);
    }

    /**
     * @return void
     */
    protected function directoryCleanup(): void
    {
        $file = $this->getDocumentFIleName();
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
     * @return \Generated\Shared\Transfer\FileSystemQueryTransfer
     */
    protected function createDocumentQueryTransfer(): FileSystemQueryTransfer
    {
        $fileSystemQueryTransfer = new FileSystemQueryTransfer();
        $fileSystemQueryTransfer->setFileSystemName(static::FILE_SYSTEM_DOCUMENT);
        $fileSystemQueryTransfer->setPath('/foo/' . static::FILE_DOCUMENT);

        return $fileSystemQueryTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\FileSystemVisibilityTransfer
     */
    protected function createDocumentVisibilityTransfer(): FileSystemVisibilityTransfer
    {
        $fileSystemVisibilityTransfer = new FileSystemVisibilityTransfer();
        $fileSystemVisibilityTransfer->setFileSystemName(static::FILE_SYSTEM_DOCUMENT);
        $fileSystemVisibilityTransfer->setPath('/foo/' . static::FILE_DOCUMENT);

        return $fileSystemVisibilityTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\FileSystemContentTransfer
     */
    protected function createContentTransfer(): FileSystemContentTransfer
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
    protected function createStreamTransfer(): FileSystemStreamTransfer
    {
        $fileSystemStreamTransfer = new FileSystemStreamTransfer();
        $fileSystemStreamTransfer->setFileSystemName(static::FILE_SYSTEM_DOCUMENT);
        $fileSystemStreamTransfer->setPath('foo/' . static::FILE_DOCUMENT);

        return $fileSystemStreamTransfer;
    }

    /**
     * @return string
     */
    protected function getDocumentFIleName(): string
    {
        return $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/' . static::FILE_DOCUMENT;
    }
}
