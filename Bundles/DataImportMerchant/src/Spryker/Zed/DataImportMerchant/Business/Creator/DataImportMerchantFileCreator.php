<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchant\Business\Creator;

use ArrayObject;
use Generated\Shared\Transfer\DataImportMerchantFileCollectionRequestTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileInfoTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileTransfer;
use Spryker\Zed\DataImportMerchant\Business\Filter\DataImportMerchantFileFilterInterface;
use Spryker\Zed\DataImportMerchant\Business\Validator\DataImportMerchantFileValidatorInterface;
use Spryker\Zed\DataImportMerchant\Business\Writer\DataImportMerchantFileWriterInterface;
use Spryker\Zed\DataImportMerchant\DataImportMerchantConfig;
use Spryker\Zed\DataImportMerchant\Persistence\DataImportMerchantEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class DataImportMerchantFileCreator implements DataImportMerchantFileCreatorInterface
{
    use TransactionTrait;

    /**
     * @param \Spryker\Zed\DataImportMerchant\Persistence\DataImportMerchantEntityManagerInterface $dataImportMerchantEntityManager
     * @param \Spryker\Zed\DataImportMerchant\Business\Validator\DataImportMerchantFileValidatorInterface $dataImportMerchantFileValidator
     * @param \Spryker\Zed\DataImportMerchant\Business\Filter\DataImportMerchantFileFilterInterface $dataImportMerchantFileFilter
     * @param \Spryker\Zed\DataImportMerchant\Business\Writer\DataImportMerchantFileWriterInterface $dataImportMerchantFileWriter
     * @param \Spryker\Zed\DataImportMerchant\DataImportMerchantConfig $dataImportMerchantConfig
     * @param list<\Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin\DataImportMerchantFileRequestExpanderPluginInterface> $dataImportMerchantFileRequestExpanderPlugins
     */
    public function __construct(
        protected DataImportMerchantEntityManagerInterface $dataImportMerchantEntityManager,
        protected DataImportMerchantFileValidatorInterface $dataImportMerchantFileValidator,
        protected DataImportMerchantFileFilterInterface $dataImportMerchantFileFilter,
        protected DataImportMerchantFileWriterInterface $dataImportMerchantFileWriter,
        protected DataImportMerchantConfig $dataImportMerchantConfig,
        protected array $dataImportMerchantFileRequestExpanderPlugins
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionRequestTransfer $dataImportMerchantFileCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer
     */
    public function createDataImportMerchantFileCollection(
        DataImportMerchantFileCollectionRequestTransfer $dataImportMerchantFileCollectionRequestTransfer
    ): DataImportMerchantFileCollectionResponseTransfer {
        $this->assertRequiredFields($dataImportMerchantFileCollectionRequestTransfer);

        $dataImportMerchantFileCollectionRequestTransfer = $this->executeDataImportMerchantFileRequestExpanderPlugins($dataImportMerchantFileCollectionRequestTransfer);

        $dataImportMerchantFileCollectionResponseTransfer = (new DataImportMerchantFileCollectionResponseTransfer())
            ->setDataImportMerchantFiles($dataImportMerchantFileCollectionRequestTransfer->getDataImportMerchantFiles());

        $dataImportMerchantFileCollectionResponseTransfer = $this->dataImportMerchantFileValidator->validate($dataImportMerchantFileCollectionResponseTransfer);

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers */
        $errorTransfers = $dataImportMerchantFileCollectionResponseTransfer->getErrors();

        if ($dataImportMerchantFileCollectionRequestTransfer->getIsTransactional() && $errorTransfers->count()) {
            return $dataImportMerchantFileCollectionResponseTransfer;
        }

        [$validDataImportMerchantFileTransfers, $invalidDataImportMerchantFileTransfers] = $this->dataImportMerchantFileFilter
            ->filterDataImportMerchantFilesByValidity($dataImportMerchantFileCollectionResponseTransfer);

        if ($validDataImportMerchantFileTransfers->count()) {
            $validDataImportMerchantFileTransfers = $this->getTransactionHandler()->handleTransaction(function () use ($validDataImportMerchantFileTransfers) {
                return $this->executeCreateDataImportMerchantFileCollectionTransaction($validDataImportMerchantFileTransfers);
            });
        }

        /** @var \ArrayObject<int, \Generated\Shared\Transfer\DataImportMerchantFileTransfer> $mergedDataImportMerchantFileTransfers */
        $mergedDataImportMerchantFileTransfers = $this->dataImportMerchantFileFilter->mergeDataImportMerchantFiles(
            $validDataImportMerchantFileTransfers,
            $invalidDataImportMerchantFileTransfers,
        );

        return $dataImportMerchantFileCollectionResponseTransfer->setDataImportMerchantFiles($mergedDataImportMerchantFileTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionRequestTransfer $dataImportMerchantFileCollectionRequestTransfer
     *
     * @return void
     */
    protected function assertRequiredFields(DataImportMerchantFileCollectionRequestTransfer $dataImportMerchantFileCollectionRequestTransfer): void
    {
        $dataImportMerchantFileCollectionRequestTransfer
            ->requireIsTransactional()
            ->requireDataImportMerchantFiles();

        foreach ($dataImportMerchantFileCollectionRequestTransfer->getDataImportMerchantFiles() as $dataImportMerchantFileTransfer) {
            $this->assertRequiredDataImportMerchantFileFields($dataImportMerchantFileTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     *
     * @return void
     */
    protected function assertRequiredDataImportMerchantFileFields(DataImportMerchantFileTransfer $dataImportMerchantFileTransfer): void
    {
        $dataImportMerchantFileTransfer
            ->requireIdUser()
            ->requireMerchantReference()
            ->requireImporterType()
            ->requireFileInfo();

        $this->assertRequiredFileInfoFields($dataImportMerchantFileTransfer->getFileInfoOrFail());
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileInfoTransfer $dataImportMerchantFileInfoTransfer
     *
     * @return void
     */
    protected function assertRequiredFileInfoFields(DataImportMerchantFileInfoTransfer $dataImportMerchantFileInfoTransfer): void
    {
        $dataImportMerchantFileInfoTransfer
            ->requireOriginalFileName()
            ->requireContentType()
            ->requireSize()
            ->requireContent();
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\DataImportMerchantFileTransfer> $dataImportMerchantFileTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\DataImportMerchantFileTransfer>
     */
    protected function executeCreateDataImportMerchantFileCollectionTransaction(
        ArrayObject $dataImportMerchantFileTransfers
    ): ArrayObject {
        $persistedDataImportMerchantFileTransfers = new ArrayObject();
        $initialStatus = $this->dataImportMerchantConfig->getInitialStatus();

        foreach ($dataImportMerchantFileTransfers as $entityIdentifier => $dataImportMerchantFileTransfer) {
            $dataImportMerchantFileTransfer->setStatus($initialStatus);
            $dataImportMerchantFileTransfer = $this->dataImportMerchantFileWriter->writeFileToFileSystem($dataImportMerchantFileTransfer);
            $dataImportMerchantFileTransfer = $this->dataImportMerchantEntityManager->saveDataImportMerchantFile($dataImportMerchantFileTransfer);
            $persistedDataImportMerchantFileTransfers->offsetSet($entityIdentifier, $dataImportMerchantFileTransfer);
        }

        return $persistedDataImportMerchantFileTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionRequestTransfer $dataImportMerchantFileCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCollectionRequestTransfer
     */
    protected function executeDataImportMerchantFileRequestExpanderPlugins(
        DataImportMerchantFileCollectionRequestTransfer $dataImportMerchantFileCollectionRequestTransfer
    ): DataImportMerchantFileCollectionRequestTransfer {
        foreach ($this->dataImportMerchantFileRequestExpanderPlugins as $dataImportMerchantFileRequestExpanderPlugin) {
            $dataImportMerchantFileCollectionRequestTransfer = $dataImportMerchantFileRequestExpanderPlugin->expand($dataImportMerchantFileCollectionRequestTransfer);
        }

        return $dataImportMerchantFileCollectionRequestTransfer;
    }
}
