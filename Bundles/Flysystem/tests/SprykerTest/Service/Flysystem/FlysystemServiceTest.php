<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Flysystem;

use Codeception\Configuration;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\FlysystemResourceTransfer;
use Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemReadException;
use Spryker\Service\Flysystem\FlysystemDependencyProvider;
use Spryker\Service\Flysystem\FlysystemService;
use Spryker\Service\Flysystem\FlysystemServiceFactory;
use Spryker\Service\FlysystemLocalFileSystem\Plugin\Flysystem\LocalFilesystemBuilderPlugin;
use Spryker\Service\Kernel\Container;
use SprykerTest\Service\Flysystem\Stub\FlysystemConfigStub;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group Flysystem
 * @group FlysystemServiceTest
 * Add your own group annotations below this line
 */
class FlysystemServiceTest extends Unit
{
    /**
     * @var string
     */
    public const RESOURCE_FILE_NAME = 'fileName.jpg';

    /**
     * @var string
     */
    public const FILE_SYSTEM_DOCUMENT = 'customerFileSystem';

    /**
     * @var string
     */
    public const FILE_SYSTEM_PRODUCT_IMAGE = 'productFileSystem';

    /**
     * @var string
     */
    public const ROOT_DIRECTORY = 'fileSystemRoot/uploads/';

    /**
     * @var string
     */
    public const PATH_DOCUMENT = 'documents/';

    /**
     * @var string
     */
    public const PATH_PRODUCT_IMAGE = 'images/product/';

    /**
     * @var string
     */
    public const FILE_DOCUMENT = 'customer.txt';

    /**
     * @var string
     */
    public const FILE_PRODUCT_IMAGE = 'image.png';

    /**
     * @var string
     */
    public const FILE_CONTENT = 'Hello World';

    /**
     * @var \SprykerTest\Service\Flysystem\FlysystemServiceTester
     */
    protected $tester;

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
    protected function setUp(): void
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
    protected function tearDown(): void
    {
        $this->directoryCleanup();
    }

    /**
     * @return void
     */
    public function testHasShouldReturnFalseWithNonExistingFile(): void
    {
        // Act
        $result = $this->flysystemService->has(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
        );

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testHasShouldReturnTrueWithExistingFile(): void
    {
        // Arrange
        $this->createDocumentFile();

        // Act
        $result = $this->flysystemService->has(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
        );

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testReadWithNonExistingFileShouldThrowFileSystemException(): void
    {
        // Assert
        $this->expectException(FileSystemReadException::class);

        // Act
        $contents = $this->flysystemService->read(
            static::FILE_SYSTEM_PRODUCT_IMAGE,
            'nonExistingFile.nil',
        );
    }

    /**
     * @return void
     */
    public function testReadWithExistingFileShouldReturnContent(): void
    {
        // Arrange
        $this->createDocumentFile();

        // Act
        $contents = $this->flysystemService->read(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
        );

        // Assert
        $this->assertSame(static::FILE_CONTENT, $contents);
    }

    /**
     * @return void
     */
    public function testWrite(): void
    {
        // Act
        $this->flysystemService->write(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
            static::FILE_CONTENT,
        );

        $file = $this->getLocalDocumentFile();
        $fileContent = file_get_contents($file);

        // Assert
        $this->assertSame(static::FILE_CONTENT, $fileContent);
    }

    /**
     * @return void
     */
    public function testDelete(): void
    {
        // Arrange
        $this->createDocumentFile();

        // Act
        $this->flysystemService->delete(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
        );

        $file = $this->getLocalDocumentFile();

        //Assert
        $this->assertFileDoesNotExist($file);
    }

    /**
     * @return void
     */
    public function testRename(): void
    {
        // Arrange
        $this->createDocumentFile();

        // Act
        $this->flysystemService->rename(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
            'foo/' . 'NEW_' . static::FILE_DOCUMENT,
        );

        $originalFile = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/' . static::FILE_DOCUMENT;
        $renamedFile = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/NEW_' . static::FILE_DOCUMENT;

        // Assert
        $this->assertFileDoesNotExist($originalFile);
        $this->assertFileExists($renamedFile);
    }

    /**
     * @return void
     */
    public function testCopy(): void
    {
        // Arrange
        $this->createDocumentFile();

        // Act
        $this->flysystemService->copy(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
            'foo/' . 'NEW_' . static::FILE_DOCUMENT,
        );

        $originalFile = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/' . static::FILE_DOCUMENT;
        $copiedFile = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/NEW_' . static::FILE_DOCUMENT;

        // Assert
        $this->assertFileExists($originalFile);
        $this->assertFileExists($copiedFile);
    }

    /**
     * @return void
     */
    public function testGetMimeType(): void
    {
        // Arrange
        $this->createDocumentFile();

        // Act
        $mimeType = $this->flysystemService->getMimetype(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
        );

        // Assert
        $this->assertSame('text/plain', $mimeType);
    }

    /**
     * @return void
     */
    public function testGetTimestamp(): void
    {
        // Arrange
        $timestampExpected = time();
        $this->createDocumentFile(null, $timestampExpected);

        // Act
        $timestamp = $this->flysystemService->getTimestamp(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
        );

        // Assert
        $this->assertSame($timestamp, $timestampExpected);
    }

    /**
     * @return void
     */
    public function testGetSize(): void
    {
        // Arrange
        $this->createDocumentFile();
        $file = $this->getLocalDocumentFile();
        $sizeExpected = filesize($file);

        // Act
        $size = $this->flysystemService->getSize(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
        );

        // Assert
        $this->assertSame($sizeExpected, $size);
    }

    /**
     * @return void
     */
    public function testIsPrivate(): void
    {
        // Arrange
        $this->createDocumentFile();

        // Act
        $isPrivate = $this->flysystemService->isPrivate(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
        );

        // Assert
        $this->assertFalse($isPrivate);
    }

    /**
     * @return void
     */
    public function testMarkAsPrivate(): void
    {
        // Arrange
        $this->createDocumentFile();

        $isPrivate = $this->flysystemService->isPrivate(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
        );

        $this->assertFalse($isPrivate);

        // Act
        $this->flysystemService->markAsPrivate(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
        );

        $isPrivate = $this->flysystemService->isPrivate(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
        );

        // Assert
        $this->assertTrue($isPrivate);
    }

    /**
     * @return void
     */
    public function testIsPublic(): void
    {
        // Arrange
        $this->createDocumentFile();

        // Act
        $isPrivate = $this->flysystemService->isPrivate(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
        );

        // Assert
        $this->assertFalse($isPrivate);
    }

    /**
     * @return void
     */
    public function testMarkAsPublic(): void
    {
        // Arrange
        $this->createDocumentFile();

        // Act
        $this->flysystemService->markAsPublic(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
        );

        $isPrivate = $this->flysystemService->isPrivate(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
        );

        // Assert
        $this->assertFalse($isPrivate);
    }

    /**
     * @return void
     */
    public function testCreateDir(): void
    {
        // Act
        $this->flysystemService->createDir(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/bar',
        );

        $dir = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/bar/';

        // Assert
        $this->assertDirectoryExists($dir);
    }

    /**
     * @return void
     */
    public function testDeleteDir(): void
    {
        // Arrange
        $dir = $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/bar';
        mkdir($dir, 0777, true);

        // Act
        $this->flysystemService->deleteDir(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/bar',
        );

        // Assert
        $this->assertDirectoryDoesNotExist($dir);
    }

    /**
     * @return void
     */
    public function testReadStream(): void
    {
        // Arrange
        $this->createDocumentFile();

        // Act
        $stream = $this->flysystemService->readStream(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
        );

        $content = stream_get_contents($stream);
        if ($stream !== false) {
            fclose($stream);
        }

        // Assert
        $this->assertSame(static::FILE_CONTENT, $content);
    }

    /**
     * @return void
     */
    public function testUpdateStream(): void
    {
        // Arrange
        $this->createDocumentFile();
        $this->createDocumentFileInRoot('Lorem Ipsum');

        $file = $this->testDataFileSystemRootDirectory . static::FILE_DOCUMENT;
        $stream = fopen($file, 'r+');

        // Act
        $this->flysystemService->writeStream(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
            $stream,
        );

        if ($stream !== false) {
            fclose($stream);
        }

        $file = $this->getLocalDocumentFile();
        $content = file_get_contents($file);

        // Assert
        $this->assertFileExists($file);
        $this->assertSame('Lorem Ipsum', $content);
    }

    /**
     * @return void
     */
    public function testWriteStream(): void
    {
        // Arrange
        $this->createDocumentFileInRoot();
        $file = $this->testDataFileSystemRootDirectory . static::FILE_DOCUMENT;
        $stream = fopen($file, 'r+');

        // Act
        $this->flysystemService->writeStream(
            static::FILE_SYSTEM_DOCUMENT,
            'foo/' . static::FILE_DOCUMENT,
            $stream,
        );

        if ($stream !== false) {
            fclose($stream);
        }

        $file = $this->getLocalDocumentFile();
        $content = file_get_contents($file);

        // Assert
        $this->assertFileExists($file);
        $this->assertSame(static::FILE_CONTENT, $content);
    }

    /**
     * @return void
     */
    public function testListContents(): void
    {
        // Arrange
        $this->createDocumentFile();

        // Act
        $content = $this->flysystemService->listContents(
            static::FILE_SYSTEM_DOCUMENT,
            '/',
            true,
        );

        // Assert
        $this->assertGreaterThan(0, count($content));
    }

    /**
     * Tests that when a FilesystemReader returns data with properties that do not exists in the transfer object
     * the `AbstractTransfer::fromArray()` method will not throw an exception.
     *
     * @return void
     */
    public function testListContentsIgnoresMissingProperties(): void
    {
        // Arrange
        $this->tester->arrangeFilesystemProviderThatReturnsDataWithPropertiesThatAreNotPresentInTheFlysystemResourceTransfer();

        // Act
        $flysystemResourceTransferCollection = $this->tester->getService()->listContents('foo');

        // Assert
        $this->tester->assertAllInstanceOf(FlysystemResourceTransfer::class, $flysystemResourceTransferCollection);
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
     * @return string|bool
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
    protected function directoryCleanup(): void
    {
        foreach ($this->getFileListForCleanup() as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        foreach ($this->getDirectoryListForCleanup() as $dir) {
            if (is_dir($dir)) {
                rmdir($dir);
            }
        }
    }

    /**
     * @return list<string>
     */
    protected function getFileListForCleanup(): array
    {
        return [
            $this->getLocalDocumentFile(),
            $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/NEW_' . static::FILE_DOCUMENT,
            $this->testDataFileSystemRootDirectory . static::PATH_PRODUCT_IMAGE . static::FILE_PRODUCT_IMAGE,
            $this->testDataFileSystemRootDirectory . static::FILE_DOCUMENT,
        ];
    }

    /**
     * @return list<string>
     */
    protected function getDirectoryListForCleanup(): array
    {
        return [
            $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'bar',
            $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/bar',
            $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo',
            $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT,
            $this->testDataFileSystemRootDirectory . static::PATH_PRODUCT_IMAGE,
            $this->testDataFileSystemRootDirectory . 'images/',
        ];
    }

    /**
     * @return string
     */
    protected function getLocalDocumentFile(): string
    {
        return $this->testDataFileSystemRootDirectory . static::PATH_DOCUMENT . 'foo/' . static::FILE_DOCUMENT;
    }
}
