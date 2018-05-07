<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model;

use Exception;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Shared\ErrorHandler\ErrorLogger;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\Writer\DataImportWriterInterface;

class DataImporterWriterAware extends DataImporter implements DataImporterWriterAwareInterface
{
    /**
     * @var \Spryker\Zed\DataImport\Business\Model\Writer\DataImportWriterInterface
     */
    protected $dataImportWriter;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\Writer\DataImportWriterInterface $dataImportWriter
     *
     * @return void
     */
    public function setDataImportWriter(DataImportWriterInterface $dataImportWriter)
    {
        $this->dataImportWriter = $dataImportWriter;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataImportException
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null)
    {
        if (!$this->dataImportWriter) {
            throw new Exception('Writer is not defined.');
        }

        $dataReader = $this->getDataReader($dataImporterConfigurationTransfer);
        $dataImporterReportTransfer = $this->prepareDataImportReport($dataReader);

        $this->beforeImport();

        $start = microtime(true);

        foreach ($dataReader as $dataSet) {
            try {
                $this->importDataSet($dataSet);
                $dataImporterReportTransfer->setImportedDataSetCount($dataImporterReportTransfer->getImportedDataSetCount() + 1);
            } catch (Exception $dataImportException) {
                if ($dataImporterConfigurationTransfer && $dataImporterConfigurationTransfer->getThrowException()) {
                    throw new DataImportException($this->buildExceptionMessage($dataImportException, $dataImporterReportTransfer->getImportedDataSetCount() + 1), 0, $dataImportException);
                }

                ErrorLogger::getInstance()->log($dataImportException);
                $dataImporterReportTransfer->setIsSuccess(false);
            }

            unset($dataSet);
        }

        $this->dataImportWriter->flush();

        $dataImporterReportTransfer->setImportTime(microtime(true) - $start);

        $this->afterImport();

        return $dataImporterReportTransfer;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function importDataSet(DataSetInterface $dataSet)
    {
        foreach ($this->dataSetStepBroker as $dataSetStep) {
            $dataSetStep->execute($dataSet);
        }

        $this->dataImportWriter->write($dataSet);
    }
}
