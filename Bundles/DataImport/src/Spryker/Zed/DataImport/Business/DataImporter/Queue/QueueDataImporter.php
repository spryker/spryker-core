<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\DataImporter\Queue;

use Exception;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\QueueReceiveMessageTransfer;
use SplQueue;
use Spryker\Shared\ErrorHandler\ErrorLogger;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\DataImport\Business\Model\DataImporterDataSetWriterAware;
use Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\DataImportConfig;
use Spryker\Zed\DataImport\Dependency\Client\DataImportToQueueClientInterface;
use Throwable;

class QueueDataImporter extends DataImporterDataSetWriterAware implements QueueDataImporterInterface
{
    use DataSetWriterPersistenceStateAwareTrait;

    /**
     * @var \Spryker\Zed\DataImport\Dependency\Client\DataImportToQueueClientInterface
     */
    protected $queueClient;

    /**
     * @var \Spryker\Zed\DataImport\Business\DataImporter\Queue\QueueMessageHelperInterface
     */
    protected $queueMessageHelper;

    /**
     * @var \SplQueue|\Generated\Shared\Transfer\QueueReceiveMessageTransfer[]
     */
    protected $queueReceiveMessageBuffer;

    /**
     * @param string $importType
     * @param \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface $dataReader
     * @param \Spryker\Zed\DataImport\Dependency\Client\DataImportToQueueClientInterface $queueClient
     * @param \Spryker\Zed\DataImport\Business\DataImporter\Queue\QueueMessageHelperInterface $queueMessageHelper
     */
    public function __construct(
        string $importType,
        DataReaderInterface $dataReader,
        DataImportToQueueClientInterface $queueClient,
        QueueMessageHelperInterface $queueMessageHelper
    ) {
        parent::__construct($importType, $dataReader);

        $this->queueClient = $queueClient;
        $this->queueMessageHelper = $queueMessageHelper;
        $this->queueReceiveMessageBuffer = new SplQueue();
    }

    /**
     * @return string
     */
    public function getImportGroup(): string
    {
        return $this->importGroup ?: DataImportConfig::IMPORT_GROUP_QUEUE_READERS;
    }

    /**
     * @return void
     */
    protected function flushDataSetWriter(): void
    {
        try {
            parent::flushDataSetWriter();

            $this->handleSuccessfulImport();
        } catch (Throwable $exception) {
            $this->handleFailedImport($exception);
        } finally {
            $this->resetDataSetWriterPersistenceState();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataImportException
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    protected function importByDataImporterConfiguration(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): DataImporterReportTransfer
    {
        $dataReader = $this->getDataReader($dataImporterConfigurationTransfer);
        $dataImporterReportTransfer = $this->prepareDataImportReport($dataReader);

        $this->beforeImport();

        foreach ($dataReader as $dataSet) {
            try {
                $this->importDataSet($dataSet);
                $dataImporterReportTransfer->setImportedDataSetCount($dataImporterReportTransfer->getImportedDataSetCount() + 1);
                $this->handleSuccessfulImport();
            } catch (Exception $dataImportException) {
                $dataImporterReportTransfer = $this->recalculateImportedDataSetCountAfterFailure($dataImporterReportTransfer);
                $this->handleFailedImport($dataImportException);

                if ($dataImporterConfigurationTransfer && $dataImporterConfigurationTransfer->getThrowException()) {
                    $dataImportExceptionMessage = $this->buildExceptionMessage($dataImportException, $dataImporterReportTransfer->getImportedDataSetCount() + 1);

                    throw new DataImportException($dataImportExceptionMessage, 0, $dataImportException);
                }

                $dataImporterReportTransfer->setIsSuccess(false);
            } finally {
                $this->resetDataSetWriterPersistenceState();
            }

            unset($dataSet);
        }

        return $dataImporterReportTransfer;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function importDataSet(DataSetInterface $dataSet): void
    {
        $queueReceiveMessageTransfer = $this->getQueueReceiveMessageTransferFromDataSet($dataSet);
        $this->collectQueueReceiveMessage($queueReceiveMessageTransfer);

        $dataSet->exchangeArray(
            $this->queueMessageHelper->getDecodedMessageBody($queueReceiveMessageTransfer)
        );

        foreach ($this->dataSetStepBroker as $dataSetStep) {
            $dataSetStep->execute($dataSet);
        }

        $this->dataSetWriter->write($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer
     */
    protected function getQueueReceiveMessageTransferFromDataSet(DataSetInterface $dataSet): QueueReceiveMessageTransfer
    {
        return (new QueueReceiveMessageTransfer())->fromArray($dataSet->getArrayCopy());
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueReceiveMessageTransfer
     *
     * @return void
     */
    protected function processQueueMessage(QueueReceiveMessageTransfer $queueReceiveMessageTransfer): void
    {
        if ($queueReceiveMessageTransfer->getAcknowledge()) {
            $this->queueClient->acknowledge($queueReceiveMessageTransfer);

            return;
        }

        if ($queueReceiveMessageTransfer->getReject()) {
            $this->queueClient->reject($queueReceiveMessageTransfer);
        }

        if ($queueReceiveMessageTransfer->getHasError()) {
            $this->queueClient->handleError($queueReceiveMessageTransfer);
        }
    }

    /**
     * @param \Throwable $exception
     *
     * @return string
     */
    protected function buildErrorMessage(Throwable $exception): string
    {
        return sprintf(
            'Failed to handle data import. Exception: "%s", "%s".',
            $exception->getMessage(),
            $exception->getTraceAsString()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueReceiveMessageTransfer
     *
     * @return void
     */
    protected function collectQueueReceiveMessage(QueueReceiveMessageTransfer $queueReceiveMessageTransfer): void
    {
        $this->queueReceiveMessageBuffer->enqueue($queueReceiveMessageTransfer);
    }

    /**
     * @return void
     */
    protected function handleSuccessfulImport(): void
    {
        if (!$this->isDataSetWriterDataPersisted()) {
            return;
        }

        while ($this->queueReceiveMessageBuffer->count()) {
            $queueReceiveMessageTransfer = $this->queueReceiveMessageBuffer->dequeue();
            $this->queueMessageHelper->handleSuccessMessage($queueReceiveMessageTransfer);
            $this->processQueueMessage($queueReceiveMessageTransfer);
        }
    }

    /**
     * @param \Throwable $exception
     *
     * @return void
     */
    protected function handleFailedImport(Throwable $exception): void
    {
        ErrorLogger::getInstance()->log($exception);
        $errorMessage = $this->buildErrorMessage($exception);

        while ($this->queueReceiveMessageBuffer->count()) {
            $queueReceiveMessageTransfer = $this->queueReceiveMessageBuffer->dequeue();
            $this->queueMessageHelper->handleFailedMessage($queueReceiveMessageTransfer, $errorMessage);
            $this->processQueueMessage($queueReceiveMessageTransfer);
        }
    }

    /**
     * @return void
     */
    protected function resetDataSetWriterPersistenceState(): void
    {
        $this->setDataSetWriterPersistenceState(true);
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterReportTransfer $dataImporterReportTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    protected function recalculateImportedDataSetCountAfterFailure(DataImporterReportTransfer $dataImporterReportTransfer): DataImporterReportTransfer
    {
        $dataImporterReportTransfer->setImportedDataSetCount(
            $dataImporterReportTransfer->getImportedDataSetCount() - $this->queueReceiveMessageBuffer->count() + 1
        );

        return $dataImporterReportTransfer;
    }
}
