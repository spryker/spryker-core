<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model;

use Exception;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;

class DataImporterDataSetWriterAware extends DataImporter implements DataImporterDataSetWriterAwareInterface
{
    /**
     * @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface|null
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
     * {@inheritDoc}
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null)
    {
        if (!$this->dataSetWriter) {
            throw new Exception('Writer is not defined.');
        }

        $start = microtime(true);
        $dataImporterReportTransfer = $this->importByDataImporterConfiguration($dataImporterConfigurationTransfer);
        $this->flushDataSetWriter();
        $dataImporterReportTransfer->setImportTime(microtime(true) - $start);

        $this->afterImport();

        return $dataImporterReportTransfer;
    }

    /**
     * @return void
     */
    protected function flushDataSetWriter(): void
    {
        $this->dataSetWriter->flush();
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
