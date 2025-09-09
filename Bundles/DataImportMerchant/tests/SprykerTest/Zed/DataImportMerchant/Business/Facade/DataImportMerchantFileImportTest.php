<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImportMerchant\Business\Facade;

use Codeception\Test\Unit;
use Exception;
use Generated\Shared\Transfer\DataImporterReportMessageTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Shared\DataImportMerchant\DataImportMerchantConfig as SharedDataImportMerchantConfig;
use Spryker\Zed\DataImportMerchant\DataImportMerchantDependencyProvider;
use Spryker\Zed\DataImportMerchant\Dependency\Facade\DataImportMerchantToDataImportFacadeInterface;
use Spryker\Zed\DataImportMerchant\Dependency\Service\DataImportMerchantToFileSystemServiceInterface;
use SprykerTest\Zed\DataImportMerchant\DataImportMerchantBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DataImportMerchant
 * @group Business
 * @group Facade
 * @group DataImportMerchantFileImportTest
 * Add your own group annotations below this line
 */
class DataImportMerchantFileImportTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\DataImportMerchant\DataImportMerchantBusinessTester
     */
    protected DataImportMerchantBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureDataImportMerchantTablesAreEmpty();
        $this->tester->mockConfigMethod('getSupportedImporterTypes', ['merchant-product']);
        $this->tester->mockConfigMethod('getMaxFileImportsPerProcessing', 10);
        $this->tester->setDependency(
            DataImportMerchantDependencyProvider::FACADE_DATA_IMPORT,
            $this->createDataImportFacadeMock(),
        );
    }

    /**
     * @return void
     */
    public function testImportProcessesPendingFiles(): void
    {
        // Arrange
        $dataImportMerchantFileTransfer = $this->tester->haveDataImportMerchantFile(
            $this->tester->createValidDataImportMerchantFile()->setStatus(SharedDataImportMerchantConfig::STATUS_PENDING)->toArray(),
        );

        // Act
        $this->tester->getFacade()->import();

        // Assert
        $dataImportMerchantFileEntity = $this->tester->findDataImportMerchantEntity($dataImportMerchantFileTransfer);

        $this->assertSame(SharedDataImportMerchantConfig::STATUS_SUCCESSFUL, $dataImportMerchantFileEntity->getStatus());
        $this->assertNotNull($dataImportMerchantFileEntity->getImportResult());

        // Decode JSON string to check import result details
        $importResultData = json_decode($dataImportMerchantFileEntity->getImportResult(), true);
        $this->assertNotNull($importResultData['started_at']);
        $this->assertNotNull($importResultData['finished_at']);
        $this->assertSame('[]', $importResultData['errors']);
    }

    /**
     * @return void
     */
    public function testImportSkipsNonPendingFiles(): void
    {
        // Arrange
        $dataImportMerchantFileTransfer = $this->tester->haveDataImportMerchantFile(
            $this->tester->createValidDataImportMerchantFile()->setStatus(SharedDataImportMerchantConfig::STATUS_SUCCESSFUL)->toArray(),
        );

        // Act
        $this->tester->getFacade()->import();

        // Assert
        $dataImportMerchantFileEntity = $this->tester->findDataImportMerchantEntity($dataImportMerchantFileTransfer);
        $this->assertSame(SharedDataImportMerchantConfig::STATUS_SUCCESSFUL, $dataImportMerchantFileEntity->getStatus());
    }

    /**
     * @return void
     */
    public function testImportSetsInProgressStatusBeforeProcessing(): void
    {
        // Arrange
        $dataImportMerchantFileTransfer = $this->tester->haveDataImportMerchantFile(
            $this->tester->createValidDataImportMerchantFile()->setStatus(SharedDataImportMerchantConfig::STATUS_PENDING)->toArray(),
        );

        $dataImportFacadeMock = $this->createDataImportFacadeMockWithCallback(function () use ($dataImportMerchantFileTransfer) {
            $dataImportMerchantFileEntity = $this->tester->findDataImportMerchantEntity($dataImportMerchantFileTransfer);
            $this->assertSame(SharedDataImportMerchantConfig::STATUS_IN_PROGRESS, $dataImportMerchantFileEntity->getStatus());

            return $this->createSuccessfulDataImporterReportTransfer();
        });

        $this->tester->setDependency(DataImportMerchantDependencyProvider::FACADE_DATA_IMPORT, $dataImportFacadeMock);

        // Act
        $this->tester->getFacade()->import();

        // Assert
        $updatedFileTransfer = $this->tester->findDataImportMerchantEntity($dataImportMerchantFileTransfer);
        $this->assertSame(SharedDataImportMerchantConfig::STATUS_SUCCESSFUL, $updatedFileTransfer->getStatus());
    }

    /**
     * @return void
     */
    public function testImportHandlesFailedImport(): void
    {
        // Arrange
        $dataImportMerchantFileTransfer = $this->tester->haveDataImportMerchantFile(
            $this->tester->createValidDataImportMerchantFile()->setStatus(SharedDataImportMerchantConfig::STATUS_PENDING)->toArray(),
        );

        $dataImportFacadeMock = $this->createDataImportFacadeMockWithCallback(function () {
            return $this->createFailedDataImporterReportTransfer();
        });

        $this->tester->setDependency(DataImportMerchantDependencyProvider::FACADE_DATA_IMPORT, $dataImportFacadeMock);

        // Act
        $this->tester->getFacade()->import();

        // Assert
        $dataImportMerchantFileEntity = $this->tester->findDataImportMerchantEntity($dataImportMerchantFileTransfer);
        $this->assertSame(SharedDataImportMerchantConfig::STATUS_FAILED, $dataImportMerchantFileEntity->getStatus());

        $importResultData = json_decode($dataImportMerchantFileEntity->getImportResult(), true);
        $this->assertNotNull($importResultData['errors']);
    }

    /**
     * @return void
     */
    public function testImportHandlesPartiallySuccessfulImport(): void
    {
        // Arrange
        $dataImportMerchantFileTransfer = $this->tester->haveDataImportMerchantFile(
            $this->tester->createValidDataImportMerchantFile()->setStatus(SharedDataImportMerchantConfig::STATUS_PENDING)->toArray(),
        );

        $dataImportFacadeMock = $this->createDataImportFacadeMockWithCallback(function () {
            return $this->createPartiallySuccessfulDataImporterReportTransfer();
        });

        $this->tester->setDependency(DataImportMerchantDependencyProvider::FACADE_DATA_IMPORT, $dataImportFacadeMock);

        // Act
        $this->tester->getFacade()->import();

        // Assert
        $dataImportMerchantFileEntity = $this->tester->findDataImportMerchantEntity($dataImportMerchantFileTransfer);
        $this->assertSame(SharedDataImportMerchantConfig::STATUS_IMPORTED_WITH_ERRORS, $dataImportMerchantFileEntity->getStatus());
    }

    /**
     * @return void
     */
    public function testImportHandlesException(): void
    {
        // Arrange
        $dataImportMerchantFileTransfer = $this->tester->haveDataImportMerchantFile(
            $this->tester->createValidDataImportMerchantFile()->setStatus(SharedDataImportMerchantConfig::STATUS_PENDING)->toArray(),
        );

        $dataImportFacadeMock = $this->createMock(DataImportMerchantToDataImportFacadeInterface::class);
        $dataImportFacadeMock->method('importByAction')->willThrowException(new Exception('Import failed'));

        $this->tester->setDependency(DataImportMerchantDependencyProvider::FACADE_DATA_IMPORT, $dataImportFacadeMock);

        // Act
        $this->tester->getFacade()->import();

        // Assert
        $dataImportMerchantFileEntity = $this->tester->findDataImportMerchantEntity($dataImportMerchantFileTransfer);
        $this->assertSame(SharedDataImportMerchantConfig::STATUS_FAILED, $dataImportMerchantFileEntity->getStatus());
        $this->assertStringContainsString('Internal error occurred during import processing', $dataImportMerchantFileEntity->getImportResult());
    }

    /**
     * @return void
     */
    public function testImportRespectsMaxFileImportsPerProcessingLimit(): void
    {
        // Arrange
        $this->tester->mockConfigMethod('getMaxFileImportsPerProcessing', 2);

        $dataImportMerchantFileTransfer1 = $this->tester->haveDataImportMerchantFile(
            $this->tester->createValidDataImportMerchantFile()->setStatus(SharedDataImportMerchantConfig::STATUS_PENDING)->toArray(),
        );
        $dataImportMerchantFileTransfer2 = $this->tester->haveDataImportMerchantFile(
            $this->tester->createValidDataImportMerchantFile()->setStatus(SharedDataImportMerchantConfig::STATUS_PENDING)->toArray(),
        );
        $dataImportMerchantFileTransfer3 = $this->tester->haveDataImportMerchantFile(
            $this->tester->createValidDataImportMerchantFile()->setStatus(SharedDataImportMerchantConfig::STATUS_PENDING)->toArray(),
        );

        // Act
        $this->tester->getFacade()->import();

        // Assert
        $statuses = [
            $this->tester->findDataImportMerchantEntity($dataImportMerchantFileTransfer1)->getStatus(),
            $this->tester->findDataImportMerchantEntity($dataImportMerchantFileTransfer2)->getStatus(),
            $this->tester->findDataImportMerchantEntity($dataImportMerchantFileTransfer3)->getStatus(),
        ];

        $successfulCount = array_count_values($statuses)[SharedDataImportMerchantConfig::STATUS_SUCCESSFUL] ?? 0;
        $pendingCount = array_count_values($statuses)[SharedDataImportMerchantConfig::STATUS_PENDING] ?? 0;

        $this->assertSame(2, $successfulCount);
        $this->assertSame(1, $pendingCount);
    }

    /**
     * @return \Spryker\Zed\DataImportMerchant\Dependency\Service\DataImportMerchantToFileSystemServiceInterface
     */
    protected function createFileSystemServiceMock(): DataImportMerchantToFileSystemServiceInterface
    {
        $fileSystemServiceMock = $this->createMock(DataImportMerchantToFileSystemServiceInterface::class);
        $fileSystemServiceMock->method('write');

        return $fileSystemServiceMock;
    }

    /**
     * @return \Spryker\Zed\DataImportMerchant\Dependency\Facade\DataImportMerchantToDataImportFacadeInterface
     */
    protected function createDataImportFacadeMock(): DataImportMerchantToDataImportFacadeInterface
    {
        $dataImportFacadeMock = $this->createMock(DataImportMerchantToDataImportFacadeInterface::class);
        $dataImportFacadeMock->method('importByAction')
            ->willReturn($this->createSuccessfulDataImporterReportTransfer());

        return $dataImportFacadeMock;
    }

    /**
     * @param callable $callback
     *
     * @return \Spryker\Zed\DataImportMerchant\Dependency\Facade\DataImportMerchantToDataImportFacadeInterface
     */
    protected function createDataImportFacadeMockWithCallback(callable $callback): DataImportMerchantToDataImportFacadeInterface
    {
        $dataImportFacadeMock = $this->createMock(DataImportMerchantToDataImportFacadeInterface::class);
        $dataImportFacadeMock->method('importByAction')
            ->willReturnCallback($callback);

        return $dataImportFacadeMock;
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    protected function createSuccessfulDataImporterReportTransfer(): DataImporterReportTransfer
    {
        return (new DataImporterReportTransfer())
            ->setIsSuccess(true)
            ->setImportedDataSetCount(10);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    protected function createFailedDataImporterReportTransfer(): DataImporterReportTransfer
    {
        $dataImporterReportMessageTransfer1 = (new DataImporterReportMessageTransfer())
            ->setDataSetNumber(1)
            ->setDataSetIdentifier('product-123')
            ->setMessage('Invalid product SKU format');

        $dataImporterReportMessageTransfer2 = (new DataImporterReportMessageTransfer())
            ->setDataSetNumber(3)
            ->setDataSetIdentifier('product-456')
            ->setMessage('Required field "name" is missing');

        $dataImporterReportTransfer = (new DataImporterReportTransfer())
            ->addMessage($dataImporterReportMessageTransfer1)
            ->addMessage($dataImporterReportMessageTransfer2);

        return (new DataImporterReportTransfer())
            ->setIsSuccess(false)
            ->setImportedDataSetCount(0)
            ->addDataImporterReport($dataImporterReportTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    protected function createPartiallySuccessfulDataImporterReportTransfer(): DataImporterReportTransfer
    {
        return (new DataImporterReportTransfer())
            ->setIsSuccess(false)
            ->setImportedDataSetCount(5);
    }
}
