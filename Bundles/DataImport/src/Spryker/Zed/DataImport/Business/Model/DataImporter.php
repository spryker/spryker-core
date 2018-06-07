<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model;

use Countable;
use Exception;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Shared\ErrorHandler\ErrorLogger;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\DataImport\Business\Model\DataReader\ConfigurableDataReaderInterface;
use Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerInterface;

class DataImporter implements
    DataImporterBeforeImportAwareInterface,
    DataImporterInterface,
    DataImporterAfterImportAwareInterface,
    DataSetStepBrokerAwareInterface
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
     * @var \Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportInterface[]
     */
    protected $beforeImportHooks = [];

    /**
     * @var \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportInterface[]
     */
    protected $afterImportHooks = [];

    /**
     * @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerInterface[]
     */
    protected $dataSetStepBroker = [];

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
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerInterface $dataSetStepBroker
     *
     * @return $this
     */
    public function addDataSetStepBroker(DataSetStepBrokerInterface $dataSetStepBroker)
    {
        $this->dataSetStepBroker[] = $dataSetStepBroker;

        return $this;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportInterface $beforeImportHook
     *
     * @return $this
     */
    public function addBeforeImportHook(DataImporterBeforeImportInterface $beforeImportHook)
    {
        $this->beforeImportHooks[] = $beforeImportHook;

        return $this;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportInterface $afterImportHook
     *
     * @return $this
     */
    public function addAfterImportHook(DataImporterAfterImportInterface $afterImportHook)
    {
        $this->afterImportHooks[] = $afterImportHook;

        return $this;
    }

    /**
     * @return void
     */
    public function beforeImport()
    {
        foreach ($this->beforeImportHooks as $beforeImportHook) {
            $beforeImportHook->beforeImport();
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataImportException
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null)
    {
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

        $dataImporterReportTransfer->setImportTime(microtime(true) - $start);

        $this->afterImport();

        return $dataImporterReportTransfer;
    }

    /**
     * @return void
     */
    public function afterImport()
    {
        foreach ($this->afterImportHooks as $afterImportHook) {
            $afterImportHook->afterImport();
        }
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
            ->setImportedDataSetCount(0)
            ->setIsSuccess(true)
            ->setIsReaderCountable(false);

        if ($dataReader instanceof Countable) {
            $dataImporterReportTransfer->setIsReaderCountable(true);
            $dataImporterReportTransfer->setExpectedImportableDataSetCount($dataReader->count());
        }

        return $dataImporterReportTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSet[]
     */
    protected function getDataReader(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null)
    {
        if ($dataImporterConfigurationTransfer && $dataImporterConfigurationTransfer->getReaderConfiguration()) {
            if ($this->dataReader instanceof ConfigurableDataReaderInterface) {
                $this->dataReader->configure($dataImporterConfigurationTransfer->getReaderConfiguration());
            }
        }

        return $this->dataReader;
    }

    /**
     * @param \Exception $exception
     * @param null|int $dataSetPosition
     *
     * @return string
     */
    protected function buildExceptionMessage(Exception $exception, $dataSetPosition = null)
    {
        $message = $exception->getMessage() . PHP_EOL . PHP_EOL;
        if ($dataSetPosition && $this->getImportType() !== 'full') {
            $message .= sprintf('DataImport for "%s" at data set position "%s" has an error.', $this->getImportType(), $dataSetPosition) . PHP_EOL . PHP_EOL;
            $message .= sprintf('For debugging execute "vendor/bin/console data:import:%s -o %s -l 1 -t"', $this->getImportType(), $dataSetPosition) . PHP_EOL . PHP_EOL;
        }

        $message .= sprintf('%s:%s %s', $exception->getFile(), $exception->getLine(), PHP_EOL . PHP_EOL . $exception->getTraceAsString());

        return $message;
    }
}
