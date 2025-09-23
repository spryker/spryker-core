<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\DataImportMerchant\Business\Importer;

use ArrayObject;
use DateTime;
use Generated\Shared\Transfer\DataImportConfigurationActionTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationContextTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportMessageTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileConditionsTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileCriteriaTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Shared\DataImportMerchant\DataImportMerchantConfig as SharedDataImportMerchantConfig;
use Spryker\Zed\DataImportMerchant\DataImportMerchantConfig;
use Spryker\Zed\DataImportMerchant\Dependency\Facade\DataImportMerchantToDataImportFacadeInterface;
use Spryker\Zed\DataImportMerchant\Dependency\Facade\DataImportMerchantToMerchantFacadeInterface;
use Spryker\Zed\DataImportMerchant\Dependency\Service\DataImportMerchantToUtilEncodingServiceInterface;
use Spryker\Zed\DataImportMerchant\Persistence\DataImportMerchantEntityManagerInterface;
use Spryker\Zed\DataImportMerchant\Persistence\DataImportMerchantRepositoryInterface;
use Throwable;

class DataImportMerchantFileImporter implements DataImportMerchantFileImporterInterface
{
    /**
     * @see \Spryker\Zed\DataImport\DataImportConfig::IMPORT_GROUP_FULL
     *
     * @var string
     */
    protected const IMPORT_GROUP_FULL = 'FULL';

    /**
     * @var string
     */
    protected const ERROR_KEY_ROW_NUMBER = 'row_number';

    /**
     * @var string
     */
    protected const ERROR_KEY_IDENTIFIER = 'identifier';

    /**
     * @var string
     */
    protected const ERROR_KEY_MESSAGE = 'message';

    /**
     * @var string
     */
    protected const ERROR_KEY_ERROR = 'error';

    /**
     * @var string
     */
    protected const GENERIC_ERROR_MESSAGE = 'Internal error occurred during import processing.';

    /**
     * @param \Spryker\Zed\DataImportMerchant\Persistence\DataImportMerchantRepositoryInterface $dataImportMerchantRepository
     * @param \Spryker\Zed\DataImportMerchant\Persistence\DataImportMerchantEntityManagerInterface $dataImportMerchantEntityManager
     * @param \Spryker\Zed\DataImportMerchant\DataImportMerchantConfig $dataImportMerchantConfig
     * @param \Spryker\Zed\DataImportMerchant\Dependency\Facade\DataImportMerchantToDataImportFacadeInterface $dataImportFacade
     * @param \Spryker\Zed\DataImportMerchant\Dependency\Service\DataImportMerchantToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\DataImportMerchant\Dependency\Facade\DataImportMerchantToMerchantFacadeInterface $merchantFacade
     */
    public function __construct(
        protected DataImportMerchantRepositoryInterface $dataImportMerchantRepository,
        protected DataImportMerchantEntityManagerInterface $dataImportMerchantEntityManager,
        protected DataImportMerchantConfig $dataImportMerchantConfig,
        protected DataImportMerchantToDataImportFacadeInterface $dataImportFacade,
        protected DataImportMerchantToUtilEncodingServiceInterface $utilEncodingService,
        protected DataImportMerchantToMerchantFacadeInterface $merchantFacade
    ) {
    }

    /**
     * @return void
     */
    public function import(): void
    {
        $dataImportMerchantFileTransfers = $this->dataImportMerchantRepository
            ->getDataImportMerchantFileCollection($this->createDataImportMerchantFileCriteriaTransfer())
            ->getDataImportMerchantFiles();

        $indexedMerchantIds = $this->getMerchantIdsIndexedByMerchantReference($dataImportMerchantFileTransfers);

        foreach ($dataImportMerchantFileTransfers as $dataImportMerchantFileTransfer) {
            $this->beforeImport($dataImportMerchantFileTransfer);

            try {
                $dataImporterReportTransfer = $this->dataImportFacade->importByAction(
                    $this->createDataImportConfigurationActionTransfer($dataImportMerchantFileTransfer, $indexedMerchantIds),
                    $this->createDataImporterConfigurationTransfer($dataImportMerchantFileTransfer, $indexedMerchantIds),
                );
            } catch (Throwable) {
                $this->onImportException($dataImportMerchantFileTransfer);

                return;
            }

            $this->afterImport($dataImportMerchantFileTransfer, $dataImporterReportTransfer);
        }
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\DataImportMerchantFileTransfer> $dataImportMerchantFileTransfers
     *
     * @return array<string, int>
     */
    protected function getMerchantIdsIndexedByMerchantReference(ArrayObject $dataImportMerchantFileTransfers): array
    {
        $merchantReferences = [];
        foreach ($dataImportMerchantFileTransfers as $dataImportMerchantFileTransfer) {
            $merchantReferences[] = $dataImportMerchantFileTransfer->getMerchantReferenceOrFail();
        }

        $merchantCriteriaTransfer = (new MerchantCriteriaTransfer())
            ->setMerchantReferences(array_unique($merchantReferences));

        $indexedMerchantIds = [];
        foreach ($this->merchantFacade->get($merchantCriteriaTransfer)->getMerchants() as $merchantTransfer) {
            $indexedMerchantIds[$merchantTransfer->getMerchantReferenceOrFail()] = $merchantTransfer->getIdMerchantOrFail();
        }

        return $indexedMerchantIds;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     *
     * @return void
     */
    protected function beforeImport(DataImportMerchantFileTransfer $dataImportMerchantFileTransfer): void
    {
        $dataImportMerchantFileTransfer
            ->setStatus(SharedDataImportMerchantConfig::STATUS_IN_PROGRESS)
            ->getImportResultOrFail()->setStartedAt((new DateTime())->format('Y-m-d H:i:s.u'));

        $this->dataImportMerchantEntityManager->saveDataImportMerchantFile($dataImportMerchantFileTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     * @param \Generated\Shared\Transfer\DataImporterReportTransfer $dataImporterReportTransfer
     *
     * @return void
     */
    protected function afterImport(
        DataImportMerchantFileTransfer $dataImportMerchantFileTransfer,
        DataImporterReportTransfer $dataImporterReportTransfer
    ): void {
        $dataImportMerchantFileTransfer
            ->setStatus($this->resolveStatus($dataImporterReportTransfer))
            ->getImportResultOrFail()
            ->setErrors($this->extractErrors($dataImporterReportTransfer))
            ->setFinishedAt((new DateTime())->format('Y-m-d H:i:s.u'));

        $this->dataImportMerchantEntityManager->saveDataImportMerchantFile($dataImportMerchantFileTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     *
     * @return void
     */
    protected function onImportException(DataImportMerchantFileTransfer $dataImportMerchantFileTransfer): void
    {
        $errors = [$this->getGenericDataImportErrorData()];

        /** @var string $errorsEncoded */
        $errorsEncoded = $this->utilEncodingService->encodeJson($errors);

        $dataImportMerchantFileTransfer
            ->setStatus(SharedDataImportMerchantConfig::STATUS_FAILED)
            ->getImportResultOrFail()->setErrors($errorsEncoded);

        $this->dataImportMerchantEntityManager->saveDataImportMerchantFile($dataImportMerchantFileTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterReportTransfer $dataImporterReportTransfer
     *
     * @return string
     */
    protected function resolveStatus(DataImporterReportTransfer $dataImporterReportTransfer): string
    {
        if (!$dataImporterReportTransfer->getIsSuccess() && $dataImporterReportTransfer->getImportedDataSetCount() > 0) {
            return SharedDataImportMerchantConfig::STATUS_IMPORTED_WITH_ERRORS;
        }

        return $dataImporterReportTransfer->getIsSuccess()
            ? SharedDataImportMerchantConfig::STATUS_SUCCESSFUL
            : SharedDataImportMerchantConfig::STATUS_FAILED;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterReportTransfer $dataImporterReportTransfer
     *
     * @return string
     */
    protected function extractErrors(DataImporterReportTransfer $dataImporterReportTransfer): string
    {
        $errors = [];
        foreach ($dataImporterReportTransfer->getDataImporterReports() as $dataImporterReport) {
            foreach ($dataImporterReport->getMessages() as $messageTransfer) {
                $errors[] = $this->mapDataImporterReportMessageTransferToArray($messageTransfer);
            }
        }

        /** @phpstan-var string */
        return $this->utilEncodingService->encodeJson($errors);
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterReportMessageTransfer $dataImporterReportMessageTransfer
     *
     * @return array<string, mixed>
     */
    protected function mapDataImporterReportMessageTransferToArray(
        DataImporterReportMessageTransfer $dataImporterReportMessageTransfer
    ): array {
        return [
            static::ERROR_KEY_ROW_NUMBER => $dataImporterReportMessageTransfer->getDataSetNumber(),
            static::ERROR_KEY_IDENTIFIER => $dataImporterReportMessageTransfer->getDataSetIdentifier(),
            static::ERROR_KEY_MESSAGE => $dataImporterReportMessageTransfer->getMessage(),
            static::ERROR_KEY_ERROR => $dataImporterReportMessageTransfer->getError()?->toArray(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getGenericDataImportErrorData(): array
    {
        return [
            static::ERROR_KEY_MESSAGE => static::GENERIC_ERROR_MESSAGE,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     * @param array<string, int> $indexedMerchantIds
     *
     * @return \Generated\Shared\Transfer\DataImportConfigurationActionTransfer
     */
    protected function createDataImportConfigurationActionTransfer(
        DataImportMerchantFileTransfer $dataImportMerchantFileTransfer,
        array $indexedMerchantIds
    ): DataImportConfigurationActionTransfer {
        $dataImportMerchantFileInfoTransfer = $dataImportMerchantFileTransfer->getFileInfoOrFail();

        return (new DataImportConfigurationActionTransfer())
            ->setDataEntity($dataImportMerchantFileTransfer->getImporterTypeOrFail())
            ->setSource($dataImportMerchantFileInfoTransfer->getUploadedUrlOrFail())
            ->setFileSystem($dataImportMerchantFileInfoTransfer->getFileSystemNameOrFail())
            ->setContext($this->createDataImporterConfigurationContextTransfer($dataImportMerchantFileTransfer, $indexedMerchantIds));
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     * @param array<string, int> $indexedMerchantIds
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    protected function createDataImporterConfigurationTransfer(
        DataImportMerchantFileTransfer $dataImportMerchantFileTransfer,
        array $indexedMerchantIds
    ): DataImporterConfigurationTransfer {
        $dataImportMerchantFileInfoTransfer = $dataImportMerchantFileTransfer->getFileInfoOrFail();

        $dataImportReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName($dataImportMerchantFileInfoTransfer->getUploadedUrlOrFail())
            ->setFileSystem($dataImportMerchantFileInfoTransfer->getFileSystemNameOrFail());

        return (new DataImporterConfigurationTransfer())
            ->setImportGroup(static::IMPORT_GROUP_FULL)
            ->setImportType($dataImportMerchantFileTransfer->getImporterTypeOrFail())
            ->setReaderConfiguration($dataImportReaderConfigurationTransfer)
            ->setContext($this->createDataImporterConfigurationContextTransfer($dataImportMerchantFileTransfer, $indexedMerchantIds));
    }

    /**
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCriteriaTransfer
     */
    protected function createDataImportMerchantFileCriteriaTransfer(): DataImportMerchantFileCriteriaTransfer
    {
        $dataImportMerchantFileConditionsTransfer = (new DataImportMerchantFileConditionsTransfer())
            ->addStatus(SharedDataImportMerchantConfig::STATUS_PENDING);

        $paginationTransfer = (new PaginationTransfer())
            ->setPage(1)
            ->setMaxPerPage($this->dataImportMerchantConfig->getMaxFileImportsPerProcessing());

        return (new DataImportMerchantFileCriteriaTransfer())
            ->setDataImportMerchantFileConditions($dataImportMerchantFileConditionsTransfer)
            ->setPagination($paginationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     * @param array<string, int> $indexedMerchantIds
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationContextTransfer
     */
    protected function createDataImporterConfigurationContextTransfer(
        DataImportMerchantFileTransfer $dataImportMerchantFileTransfer,
        array $indexedMerchantIds
    ): DataImporterConfigurationContextTransfer {
        $idMerchant = $indexedMerchantIds[$dataImportMerchantFileTransfer->getMerchantReferenceOrFail()] ?? null;

        return (new DataImporterConfigurationContextTransfer())->setIdMerchant($idMerchant);
    }
}
