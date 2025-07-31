<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\FileImportMerchantPortalGui\Business\Importer;

use DateTime;
use Generated\Shared\Transfer\DataImportConfigurationActionTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationContextTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportMessageTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\MerchantFileImportConditionsTransfer;
use Generated\Shared\Transfer\MerchantFileImportCriteriaTransfer;
use Generated\Shared\Transfer\MerchantFileImportTransfer;
use Spryker\Shared\FileImportMerchantPortalGui\FileImportMerchantPortalGuiConfig as SharedFileImportMerchantPortalGuiConfig;
use Spryker\Zed\FileImportMerchantPortalGui\Business\Reader\MerchantFileImportReaderInterface;
use Spryker\Zed\FileImportMerchantPortalGui\Business\Saver\MerchantFileImportSaverInterface;
use Spryker\Zed\FileImportMerchantPortalGui\Dependency\Facade\FileImportMerchantPortalGuiToDataImportFacadeInterface;
use Spryker\Zed\FileImportMerchantPortalGui\FileImportMerchantPortalGuiConfig;
use Throwable;

class MerchantFileImporter implements MerchantFileImporterInterface
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
    protected const GENERIC_ERROR_MESSAGE = 'Internal error occurred during import processing.';

    /**
     * @param \Spryker\Zed\FileImportMerchantPortalGui\Business\Reader\MerchantFileImportReaderInterface $merchantFileImportReader
     * @param \Spryker\Zed\FileImportMerchantPortalGui\Business\Saver\MerchantFileImportSaverInterface $merchantFileImportSaver
     * @param \Spryker\Zed\FileImportMerchantPortalGui\Dependency\Facade\FileImportMerchantPortalGuiToDataImportFacadeInterface $dataImportFacade
     * @param \Spryker\Zed\FileImportMerchantPortalGui\FileImportMerchantPortalGuiConfig $config
     */
    public function __construct(
        protected MerchantFileImportReaderInterface $merchantFileImportReader,
        protected MerchantFileImportSaverInterface $merchantFileImportSaver,
        protected FileImportMerchantPortalGuiToDataImportFacadeInterface $dataImportFacade,
        protected FileImportMerchantPortalGuiConfig $config
    ) {
    }

    /**
     * @return void
     */
    public function import(): void
    {
        $merchantFileImportCriteriaTransfer = $this->createMerchantFileImportCriteria();

        $merchantFileImportCollectionTransfer = $this->merchantFileImportReader->getMerchantFileImportCollection(
            $merchantFileImportCriteriaTransfer,
        );

        foreach ($merchantFileImportCollectionTransfer->getMerchantFileImports() as $merchantFileImportTransfer) {
            $this->beforeImport($merchantFileImportTransfer);

            $dataImportConfigurationActionTransfer = $this->createDataImportConfigurationActionTransfer(
                $merchantFileImportTransfer,
            );
            $dataImporterConfiguration = $this->createDataImporterConfigurationTransfer($merchantFileImportTransfer);

            try {
                $dataImporterReportTransfer = $this->dataImportFacade->importByAction(
                    $dataImportConfigurationActionTransfer,
                    $dataImporterConfiguration,
                );
            } catch (Throwable) {
                $this->onImportException($merchantFileImportTransfer);

                return;
            }

            $this->afterImport($merchantFileImportTransfer, $dataImporterReportTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileImportTransfer $merchantFileImportTransfer
     *
     * @return void
     */
    protected function beforeImport(MerchantFileImportTransfer $merchantFileImportTransfer): void
    {
        $merchantFileImportTransfer
            ->setStatus(SharedFileImportMerchantPortalGuiConfig::STATUS_IN_PROGRESS)
            ->setStartedAt((new DateTime())->format('Y-m-d H:i:s.u'));

        $this->merchantFileImportSaver->saveMerchantFileImport($merchantFileImportTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileImportTransfer $merchantFileImportTransfer
     * @param \Generated\Shared\Transfer\DataImporterReportTransfer $dataImporterReportTransfer
     *
     * @return void
     */
    protected function afterImport(
        MerchantFileImportTransfer $merchantFileImportTransfer,
        DataImporterReportTransfer $dataImporterReportTransfer
    ): void {
        $merchantFileImportTransfer->setStatus($this->resolveStatus($dataImporterReportTransfer));
        $merchantFileImportTransfer->setErrors($this->extractErrors($dataImporterReportTransfer));

        $this->merchantFileImportSaver->saveMerchantFileImport($merchantFileImportTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileImportTransfer $merchantFileImportTransfer
     *
     * @return void
     */
    protected function onImportException(MerchantFileImportTransfer $merchantFileImportTransfer): void
    {
        $errors = [$this->getGenericDataImportErrorData()];

        /** @var string $errorsEncoded */
        $errorsEncoded = json_encode($errors);

        $merchantFileImportTransfer->setStatus(SharedFileImportMerchantPortalGuiConfig::STATUS_FAILED);
        $merchantFileImportTransfer->setErrors($errorsEncoded);

        $this->merchantFileImportSaver->saveMerchantFileImport($merchantFileImportTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterReportTransfer $dataImporterReportTransfer
     *
     * @return string
     */
    protected function resolveStatus(DataImporterReportTransfer $dataImporterReportTransfer): string
    {
        if (!$dataImporterReportTransfer->getIsSuccess() && $dataImporterReportTransfer->getImportedDataSetCount() > 0) {
            return SharedFileImportMerchantPortalGuiConfig::STATUS_IMPORTED_WITH_ERRORS;
        }

        return $dataImporterReportTransfer->getIsSuccess()
            ? SharedFileImportMerchantPortalGuiConfig::STATUS_SUCCESSFUL
            : SharedFileImportMerchantPortalGuiConfig::STATUS_FAILED;
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
        return json_encode($errors);
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
     * @param \Generated\Shared\Transfer\MerchantFileImportTransfer $merchantFileImportTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportConfigurationActionTransfer
     */
    protected function createDataImportConfigurationActionTransfer(
        MerchantFileImportTransfer $merchantFileImportTransfer
    ): DataImportConfigurationActionTransfer {
        $merchantFileTransfer = $merchantFileImportTransfer->getMerchantFileOrFail();

        return (new DataImportConfigurationActionTransfer())
            ->setDataEntity($merchantFileImportTransfer->getEntityType())
            ->setSource($merchantFileTransfer->getUploadedUrl())
            ->setContext($this->createDataImporterConfigurationContextTransfer($merchantFileImportTransfer))
            ->setFileSystem($merchantFileTransfer->getFileSystemName() ?? $this->config->getFileSystemName());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileImportTransfer $merchantFileImportTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    protected function createDataImporterConfigurationTransfer(
        MerchantFileImportTransfer $merchantFileImportTransfer
    ): DataImporterConfigurationTransfer {
        $merchantFileTransfer = $merchantFileImportTransfer->getMerchantFileOrFail();

        $dataImportReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName($merchantFileTransfer->getUploadedUrl())
            ->setFileSystem($merchantFileTransfer->getFileSystemName() ?? $this->config->getFileSystemName());

        return (new DataImporterConfigurationTransfer())
            ->setImportGroup(static::IMPORT_GROUP_FULL)
            ->setImportType($merchantFileImportTransfer->getEntityType())
            ->setReaderConfiguration($dataImportReaderConfigurationTransfer)
            ->setContext($this->createDataImporterConfigurationContextTransfer($merchantFileImportTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileImportTransfer $merchantFileImportTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationContextTransfer
     */
    protected function createDataImporterConfigurationContextTransfer(
        MerchantFileImportTransfer $merchantFileImportTransfer
    ): DataImporterConfigurationContextTransfer {
        $merchantFileTransfer = $merchantFileImportTransfer->getMerchantFileOrFail();

        return (new DataImporterConfigurationContextTransfer())
            ->setIdMerchant($merchantFileTransfer->getFkMerchant());
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantFileImportCriteriaTransfer
     */
    protected function createMerchantFileImportCriteria(): MerchantFileImportCriteriaTransfer
    {
        $merchantFileImportConditionsTransfer = (new MerchantFileImportConditionsTransfer())
            ->addStatus(SharedFileImportMerchantPortalGuiConfig::STATUS_PENDING);

        return (new MerchantFileImportCriteriaTransfer())
            ->setMerchantFileImportConditions($merchantFileImportConditionsTransfer)
            ->setLimit($this->config->getMaxFileImportsPerProcessing());
    }
}
