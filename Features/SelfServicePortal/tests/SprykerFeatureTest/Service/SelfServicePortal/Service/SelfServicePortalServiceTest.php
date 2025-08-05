<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Service\SelfServicePortal\Service;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;
use Spryker\Service\FileManager\FileManagerServiceInterface;
use Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemStreamException;
use Spryker\Shared\Log\Config\LoggerConfigInterface;
use SprykerFeature\Service\SelfServicePortal\Downloader\FileDownloader;
use SprykerFeature\Service\SelfServicePortal\SelfServicePortalService;
use SprykerFeature\Service\SelfServicePortal\SelfServicePortalServiceFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Service
 * @group SelfServicePortal
 * @group Service
 * @group SelfServicePortalServiceTest
 *
 * Add your own group annotations below this line
 */
class SelfServicePortalServiceTest extends Unit
{
    public function testCreateFileDownloadResponseCreatesStreamedResponseWhenFileExists(): void
    {
        // Arrange
        $fileContent = 'file content';
        $fileName = 'test.txt';
        $fileType = 'text/plain';
        $storageName = 'local';
        $storageFileName = 'test.txt';

        $fileTransfer = (new FileTransfer())
            ->setFileName($fileName)
            ->setFileInfo(new ArrayObject(
                [
                    (new FileInfoTransfer())
                        ->setStorageName($storageName)
                        ->setStorageFileName($storageFileName)
                        ->setType($fileType),
                ],
            ));

        $fileStream = fopen('php://memory', 'rb+');
        fwrite($fileStream, $fileContent);
        rewind($fileStream);

        $fileManagerServiceMock = $this->getFileManagerServiceMock($fileStream, $storageFileName, $storageName);
        $service = $this->createService($fileManagerServiceMock);

        // Act
        $response = $service->createFileDownloadResponse($fileTransfer, 1024);

        // Assert
        $this->assertInstanceOf(StreamedResponse::class, $response);
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue($response->headers->has('Content-Disposition'));
        $this->assertTrue($response->headers->has('Content-Type'));
        $this->assertStringContainsString($fileName, (string)$response->headers->get('Content-Disposition'));
        $this->assertSame($fileType, $response->headers->get('Content-Type'));

        ob_start();
        $response->sendContent();
        $output = ob_get_clean();

        $this->assertSame($fileContent, $output);
    }

    public function testCreateFileDownloadResponseCreatesErrorResponseWhenFileDoesNotExist(): void
    {
        // Arrange
        $storageName = 'local';
        $storageFileName = 'non-existent-file.txt';

        $fileTransfer = (new FileTransfer())
            ->setFileName('non-existent-file.txt')
            ->setFileInfo(new ArrayObject(
                [
                    (new FileInfoTransfer())
                        ->setStorageName($storageName)
                        ->setStorageFileName($storageFileName)
                        ->setType('text/plain'),
                ],
            ));

        $fileManagerServiceMock = $this->getFileManagerServiceMockThatThrowsException($storageFileName, $storageName);
        $service = $this->createService($fileManagerServiceMock);

        // Act
        $response = $service->createFileDownloadResponse($fileTransfer, 1024);

        // Assert
        $this->assertInstanceOf(StreamedResponse::class, $response);
        $this->assertSame(Response::HTTP_NOT_FOUND, $response->getStatusCode());

        ob_start();
        $response->sendContent();
        $output = ob_get_clean();

        $this->assertSame('File not available', $output);
    }

    /**
     * @param resource $fileStream
     * @param string $storageFileName
     * @param string $storageName
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Service\FileManager\FileManagerServiceInterface
     */
    protected function getFileManagerServiceMock(
        $fileStream,
        string $storageFileName,
        string $storageName
    ): FileManagerServiceInterface {
        $fileManagerServiceMock = $this->createMock(FileManagerServiceInterface::class);
        $fileManagerServiceMock->method('readStream')
            ->with($storageFileName, $storageName)
            ->willReturn($fileStream);

        return $fileManagerServiceMock;
    }

    /**
     * @param string $storageFileName
     * @param string $storageName
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Service\FileManager\FileManagerServiceInterface
     */
    protected function getFileManagerServiceMockThatThrowsException(
        string $storageFileName,
        string $storageName
    ): FileManagerServiceInterface {
        $fileManagerServiceMock = $this->createMock(FileManagerServiceInterface::class);
        $fileManagerServiceMock->method('readStream')
            ->with($storageFileName, $storageName)
            ->willThrowException(new FileSystemStreamException());

        return $fileManagerServiceMock;
    }

    protected function createService(MockObject $fileManagerServiceMock): SelfServicePortalService
    {
        $service = new SelfServicePortalService();
        $factoryMock = $this->getMockBuilder(SelfServicePortalServiceFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['createFileDownloader'])
            ->getMock();

        $fileDownloader = new class ($fileManagerServiceMock, $this->createLoggerMock()) extends FileDownloader {
            /**
             * @var \Psr\Log\LoggerInterface
             */
            protected LoggerInterface $loggerMock;

            /**
             * @param mixed $fileManagerService
             * @param \Psr\Log\LoggerInterface $loggerMock
             *
             * void
             */
            public function __construct($fileManagerService, LoggerInterface $loggerMock)
            {
                parent::__construct($fileManagerService);
                $this->loggerMock = $loggerMock;
            }

            protected function getLogger(?LoggerConfigInterface $loggerConfig = null): LoggerInterface
            {
                return $this->loggerMock;
            }
        };

        $factoryMock->method('createFileDownloader')->willReturn($fileDownloader);

        $service->setFactory($factoryMock);

        return $service;
    }

    protected function createLoggerMock(): LoggerInterface
    {
        return $this->createMock(LoggerInterface::class);
    }
}
