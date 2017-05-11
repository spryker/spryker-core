<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model;

use Countable;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Business\Model\DataReader\ConfigurableDataReaderInterface;
use Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSet;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetImporterAwareInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class DataImporter implements DataImporterInterface, DataSetImporterAwareInterface
{

    /**
     * @var string
     */
    protected $importType;

    /**
     * @var \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface
     */
    protected $dataReader;

    /**
     * @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetImporterInterface[]
     */
    protected $dataSetHandler = [];

    /**
     * @param string $importType
     * @param \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface $dataReader
     */
    public function __construct($importType, DataReaderInterface $dataReader)
    {
        $this->importType = $importType;
        $this->dataReader = $dataReader;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetImporterInterface $dataSetHandler
     *
     * @return $this
     */
    public function addDataSetImporter(DataSetImporterInterface $dataSetHandler)
    {
        $this->dataSetHandler[] = $dataSetHandler;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null)
    {
        $dataReader = $this->getDataReader($dataImporterConfigurationTransfer);
        $dataImporterReportTransfer = $this->prepareDataImportReport($dataReader);

        foreach ($dataReader as $dataSet) {
            $this->importDataSet($dataSet, $dataImporterReportTransfer);
            $dataImporterReportTransfer->setImportedDataSets($dataImporterReportTransfer->getImportedDataSets() + 1);
        }

        return $dataImporterReportTransfer;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function importDataSet(DataSetInterface $dataSet)
    {
        foreach ($this->dataSetHandler as $dataSetHandler) {
            $dataSetHandler->execute($dataSet);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getImportType()
    {
        return $this->importType;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface $dataReader
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    protected function prepareDataImportReport(DataReaderInterface $dataReader)
    {
        $dataImporterReportTransfer = new DataImporterReportTransfer();
        $dataImporterReportTransfer
            ->setImportType($this->getImportType())
            ->setImportedDataSets(0)
            ->setIsReaderCountable(false);

        if ($dataReader instanceof Countable) {
            $dataImporterReportTransfer->setIsReaderCountable(true);
            $dataImporterReportTransfer->setExpectedImportableDataSets($dataReader->count());
        }

        return $dataImporterReportTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSet[]
     */
    protected function getDataReader(DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null)
    {
        if ($dataImporterConfigurationTransfer && $dataImporterConfigurationTransfer->getReaderConfiguration()) {
            if ($this->dataReader instanceof ConfigurableDataReaderInterface) {
                $this->dataReader->configure($dataImporterConfigurationTransfer->getReaderConfiguration());
            }
        }

        return $this->dataReader;
    }

}
