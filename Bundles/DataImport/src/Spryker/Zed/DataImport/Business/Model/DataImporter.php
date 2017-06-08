<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model;

use Countable;
use Exception;
use Generated\Shared\Transfer\AfterDataSetImporterEventTransfer;
use Generated\Shared\Transfer\AfterDataSetImportEventTransfer;
use Generated\Shared\Transfer\AfterImportEventTransfer;
use Generated\Shared\Transfer\BeforeDataSetImporterEventTransfer;
use Generated\Shared\Transfer\BeforeDataSetImportEventTransfer;
use Generated\Shared\Transfer\BeforeImportEventTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Shared\ErrorHandler\ErrorLogger;
use Spryker\Zed\DataImport\Business\Model\DataReader\ConfigurableDataReaderInterface;
use Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerInterface;
use Spryker\Zed\DataImport\Dependency\DataImportEvents;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventInterface;

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
     * @var \Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventInterface
     */
    protected $eventFacade;

    /**
     * @param string $importType
     * @param \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface $dataReader
     * @param \Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventInterface $eventFacade
     */
    public function __construct($importType, DataReaderInterface $dataReader, DataImportToEventInterface $eventFacade)
    {
        $this->importType = $importType;
        $this->dataReader = $dataReader;
        $this->eventFacade = $eventFacade;
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
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null)
    {
        $dataReader = $this->getDataReader($dataImporterConfigurationTransfer);
        $dataImporterReportTransfer = $this->prepareDataImportReport($dataReader);

        $this->triggerBeforeImportEvent($dataReader);

        $this->beforeImport();

        foreach ($dataReader as $dataSet) {
            try {
                $this->triggerBeforeDataSetImportEvent($dataReader);

                $this->importDataSet($dataSet);
                $dataImporterReportTransfer->setImportedDataSetCount($dataImporterReportTransfer->getImportedDataSetCount() + 1);

                $this->triggerAfterDataSetImportEvent($dataReader);
            } catch (Exception $dataImportException) {
                ErrorLogger::getInstance()->log($dataImportException);
                $dataImporterReportTransfer->setIsSuccess(false);
                $this->triggerDataSetImportFailedEvent($dataImportException, $dataReader);
            }

            unset($dataSet);
        }

        $this->triggerAfterImportEvent($dataImporterReportTransfer);

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
     * @throws \Exception
     *
     * @return void
     */
    protected function importDataSet(DataSetInterface $dataSet)
    {
        foreach ($this->dataSetStepBroker as $dataSetImporter) {
            $this->triggerBeforeDataSetImporterEvent($dataSetImporter);
            try {
                $dataSetImporter->execute($dataSet);
                $this->triggerAfterDataSetImporterEvent();
            } catch (Exception $exception) {
                $this->triggerDataSetImporterFailedEvent($exception);

                throw $exception;
            }
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
    protected function getDataReader(DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null)
    {
        if ($dataImporterConfigurationTransfer && $dataImporterConfigurationTransfer->getReaderConfiguration()) {
            if ($this->dataReader instanceof ConfigurableDataReaderInterface) {
                $this->dataReader->configure($dataImporterConfigurationTransfer->getReaderConfiguration());
            }
        }

        return $this->dataReader;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface $dataReader
     *
     * @return void
     */
    protected function triggerBeforeImportEvent(DataReaderInterface $dataReader)
    {
        $beforeImportEventTransfer = new BeforeImportEventTransfer();
        $beforeImportEventTransfer
            ->setImportType($this->getImportType())
            ->setIsReaderCountable(($dataReader instanceof Countable))
            ->setImportableDataSetCount(($dataReader instanceof Countable) ? $dataReader->count() : 0);

        $this->eventFacade->trigger(DataImportEvents::BEFORE_IMPORT, $beforeImportEventTransfer);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface $dataReader
     *
     * @return void
     */
    protected function triggerBeforeDataSetImportEvent(DataReaderInterface $dataReader)
    {
        $beforeDataSetImportEventTransfer = new BeforeDataSetImportEventTransfer();
        $beforeDataSetImportEventTransfer
            ->setImportType($this->getImportType())
            ->setDataSetKey($dataReader->key());

        $this->eventFacade->trigger(DataImportEvents::BEFORE_DATA_SET_IMPORT, $beforeDataSetImportEventTransfer);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerInterface $dataSetImporter
     *
     * @return void
     */
    protected function triggerBeforeDataSetImporterEvent(DataSetStepBrokerInterface $dataSetImporter)
    {
        $beforeDataSetImporterEventTransfer = new BeforeDataSetImporterEventTransfer();
        $beforeDataSetImporterEventTransfer
            ->setImportType($this->getImportType())
            ->setDataSetImporterClassName(get_class($dataSetImporter));

        $this->eventFacade->trigger(DataImportEvents::BEFORE_DATA_SET_IMPORTER, $beforeDataSetImporterEventTransfer);
    }

    /**
     * @return void
     */
    protected function triggerAfterDataSetImporterEvent()
    {
        $afterDataSetImporterEventTransfer = new AfterDataSetImporterEventTransfer();
        $afterDataSetImporterEventTransfer
            ->setImportType($this->getImportType())
            ->setIsSuccess(true);

        $this->eventFacade->trigger(DataImportEvents::AFTER_DATA_SET_IMPORTER, $afterDataSetImporterEventTransfer);
    }

    /**
     * @param \Exception $exception
     *
     * @return void
     */
    protected function triggerDataSetImporterFailedEvent(Exception $exception)
    {
        $afterDataSetImporterEventTransfer = new AfterDataSetImporterEventTransfer();
        $afterDataSetImporterEventTransfer
            ->setImportType($this->getImportType())
            ->setMessage($this->buildExceptionMessage($exception))
            ->setIsSuccess(false);

        $this->eventFacade->trigger(DataImportEvents::AFTER_DATA_SET_IMPORTER, $afterDataSetImporterEventTransfer);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface $dataReader
     *
     * @return void
     */
    protected function triggerAfterDataSetImportEvent(DataReaderInterface $dataReader)
    {
        $afterDataSetImportEventTransfer = new AfterDataSetImportEventTransfer();
        $afterDataSetImportEventTransfer
            ->setImportType($this->getImportType())
            ->setDataSetKey($dataReader->key())
            ->setIsSuccess(true);

        $this->eventFacade->trigger(DataImportEvents::AFTER_DATA_SET_IMPORT, $afterDataSetImportEventTransfer);
    }

    /**
     * @param \Exception $exception
     * @param \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface $dataReader
     *
     * @return void
     */
    protected function triggerDataSetImportFailedEvent(Exception $exception, DataReaderInterface $dataReader)
    {
        $afterDataSetImportEventTransfer = new AfterDataSetImportEventTransfer();
        $afterDataSetImportEventTransfer
            ->setImportType($this->getImportType())
            ->setDataSetKey($dataReader->key())
            ->setMessage($this->buildExceptionMessage($exception))
            ->setIsSuccess(false);

        $this->eventFacade->trigger(DataImportEvents::AFTER_DATA_SET_IMPORT, $afterDataSetImportEventTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterReportTransfer $dataImporterReportTransfer
     *
     * @return void
     */
    protected function triggerAfterImportEvent(DataImporterReportTransfer $dataImporterReportTransfer)
    {
        $afterImportEventTransfer = new AfterImportEventTransfer();
        $afterImportEventTransfer
            ->setImportType($this->getImportType())
            ->setImportedDataSetCount($dataImporterReportTransfer->getImportedDataSetCount());

        $this->eventFacade->trigger(DataImportEvents::AFTER_IMPORT, $afterImportEventTransfer);
    }

    /**
     * @param \Exception $exception
     *
     * @return string
     */
    protected function buildExceptionMessage(Exception $exception)
    {
        return sprintf('%s:%s %s %s', $exception->getFile(), $exception->getLine(), $exception->getMessage(), PHP_EOL . PHP_EOL . $exception->getTraceAsString());
    }

}
