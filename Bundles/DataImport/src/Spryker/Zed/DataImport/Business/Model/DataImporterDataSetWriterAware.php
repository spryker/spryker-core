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
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;

class DataImporterDataSetWriterAware extends DataImporter implements DataImporterDataSetWriterAwareInterface
{
    /**
     * @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected $dataSetWriter;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface $dataSetWriter
     *
     * @return void
     */
    public function setDataSetWriter(DataSetWriterInterface $dataSetWriter)
    {
        $this->dataSetWriter = $dataSetWriter;
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
        if (!$this->dataSetWriter) {
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

        $this->dataSetWriter->flush();

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

        $this->dataSetWriter->write($dataSet);
    }
}
