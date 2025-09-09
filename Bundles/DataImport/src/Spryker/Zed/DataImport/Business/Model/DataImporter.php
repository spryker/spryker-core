<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model;

use Countable;
use Exception;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportMessageTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generator;
use Spryker\Shared\ErrorHandler\ErrorLogger;
use Spryker\Zed\DataImport\Business\DataImporter\DataImporterDataSetIdentifierAwareInterface;
use Spryker\Zed\DataImport\Business\DataImporter\DataImporterImportGroupAwareInterface;
use Spryker\Zed\DataImport\Business\Exception\DataImporterGeneratorException;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\DataImport\Business\Exception\DataSetException;
use Spryker\Zed\DataImport\Business\Exception\TransactionRolledBackAwareExceptionInterface;
use Spryker\Zed\DataImport\Business\Model\DataReader\ConfigurableDataReaderInterface;
use Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerInterface;
use Spryker\Zed\DataImport\DataImportConfig;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToGracefulRunnerInterface;

class DataImporter implements
    DataImporterBeforeImportAwareInterface,
    DataImporterInterface,
    DataImporterAfterImportAwareInterface,
    DataSetStepBrokerAwareInterface,
    DataImporterImportGroupAwareInterface,
    DataImporterDataSetIdentifierAwareInterface
{
    /**
     * @var string
     */
    public const KEY_CONTEXT = 'CONTEXT';

    /**
     * @var string
     */
    protected $importType;

    /**
     * @var \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface
     */
    protected $dataReader;

    /**
     * @var array<\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportInterface>
     */
    protected $beforeImportHooks = [];

    /**
     * @var array<\Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportInterface>
     */
    protected $afterImportHooks = [];

    /**
     * @var array<\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerInterface>
     */
    protected $dataSetStepBroker = [];

    /**
     * @var string
     */
    protected $importGroup;

    /**
     * @var \Spryker\Zed\DataImport\Dependency\Facade\DataImportToGracefulRunnerInterface
     */
    protected $gracefulRunnerFacade;

    /**
     * @var string|null
     */
    protected ?string $dataSetIdentifierKey = null;

    /**
     * @param string $importType
     * @param \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface $dataReader
     * @param \Spryker\Zed\DataImport\Dependency\Facade\DataImportToGracefulRunnerInterface $gracefulRunnerFacade
     */
    public function __construct($importType, DataReaderInterface $dataReader, DataImportToGracefulRunnerInterface $gracefulRunnerFacade)
    {
        $this->importType = $importType;
        $this->dataReader = $dataReader;
        $this->gracefulRunnerFacade = $gracefulRunnerFacade;
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
     * @param string $dataSetIdentifierKey
     *
     * @return void
     */
    public function setDataSetIdentifierKey(string $dataSetIdentifierKey): void
    {
        $this->dataSetIdentifierKey = $dataSetIdentifierKey;
    }

    /**
     * {@inheritDoc}
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null)
    {
        $start = microtime(true);

        $dataImporterReportTransfer = $this->importByDataImporterConfiguration($dataImporterConfigurationTransfer);
        $dataImporterReportTransfer->setImportTime(microtime(true) - $start);

        $this->afterImport();

        return $dataImporterReportTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    protected function importByDataImporterConfiguration(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterReportTransfer {
        $dataReader = $this->getDataReader($dataImporterConfigurationTransfer);
        $source = $this->getSourceFromDataImporterConfigurationTransfer($dataImporterConfigurationTransfer);
        $dataImporterReportTransfer = $this->prepareDataImportReport($dataReader, $source);

        $this->beforeImport();

        $dataImportGenerator = $this->createDataImportGenerator($dataReader, $dataImporterReportTransfer, $dataImporterConfigurationTransfer);

        $this->gracefulRunnerFacade->run($dataImportGenerator, DataImporterGeneratorException::class);

        return $dataImportGenerator->getReturn();
    }

    /**
     * This method is turned into a `\Generator` by using the `yield` operator. Every iteration of it will be fully
     * completed until a signal was received.
     *
     * @param \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface $dataReader
     * @param \Generated\Shared\Transfer\DataImporterReportTransfer $dataImporterReportTransfer
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataImportException
     *
     * @return \Generator<\Generated\Shared\Transfer\DataImporterReportTransfer|null>
     */
    protected function createDataImportGenerator(
        DataReaderInterface $dataReader,
        DataImporterReportTransfer $dataImporterReportTransfer,
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): Generator {
        try {
            foreach ($dataReader as $dataSet) {
                yield;

                $dataSet[static::KEY_CONTEXT] = $dataImporterConfigurationTransfer?->getContext();

                try {
                    $this->processDataSet($dataSet, $dataImporterReportTransfer);
                } catch (Exception $dataImportException) {
                    if ($dataImportException instanceof TransactionRolledBackAwareExceptionInterface) {
                        $dataImporterReportTransfer = $this->recalculateImportedDataSetCount($dataImporterReportTransfer, $dataImportException);
                    }
                    $exceptionMessage = $this->buildExceptionMessage($dataImportException, $dataImporterReportTransfer->getImportedDataSetCount() + 1);

                    if ($dataImporterConfigurationTransfer && $dataImporterConfigurationTransfer->getThrowException()) {
                        throw new DataImportException($exceptionMessage, 0, $dataImportException);
                    }

                    ErrorLogger::getInstance()->log($dataImportException);

                    $dataImporterReportMessageTransfer = $this->createDataSetExceptionReportMessage(
                        $dataImportException,
                        $dataReader,
                        $dataSet,
                    );

                    $dataImporterReportTransfer
                        ->setIsSuccess(false)
                        ->addMessage($dataImporterReportMessageTransfer);
                }

                unset($dataSet);
            }
        } catch (DataSetException $exception) {
            $dataImporterReportMessageTransfer = (new DataImporterReportMessageTransfer())
                ->setMessage($exception->getMessage())
                ->setDataSetNumber($dataReader->key());

            if ($exception->findError()) {
                $dataImporterReportMessageTransfer->setError($exception->findError());
            }

            $dataImporterReportTransfer
                ->setIsSuccess(false)
                ->addMessage($dataImporterReportMessageTransfer);
        } catch (DataImporterGeneratorException $exception) {
        }

        return $dataImporterReportTransfer;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param \Generated\Shared\Transfer\DataImporterReportTransfer $dataImporterReportTransfer
     *
     * @return void
     */
    protected function processDataSet(DataSetInterface $dataSet, DataImporterReportTransfer $dataImporterReportTransfer): void
    {
        $this->importDataSet($dataSet);
        $dataImporterReportTransfer->setImportedDataSetCount($dataImporterReportTransfer->getImportedDataSetCount() + 1);
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterReportTransfer $dataImporterReportTransfer
     * @param \Spryker\Zed\DataImport\Business\Exception\TransactionRolledBackAwareExceptionInterface $exception
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    protected function recalculateImportedDataSetCount(
        DataImporterReportTransfer $dataImporterReportTransfer,
        TransactionRolledBackAwareExceptionInterface $exception
    ): DataImporterReportTransfer {
        if ($dataImporterReportTransfer->getImportedDataSetCount() === 0) {
            return $dataImporterReportTransfer;
        }

        $dataImporterReportTransfer->setImportedDataSetCount($dataImporterReportTransfer->getImportedDataSetCount() - $exception->getRolledBackRowsCount());

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
     * {@inheritDoc}
     *
     * @return string
     */
    public function getImportType()
    {
        return $this->importType;
    }

    /**
     * @param string $importGroup
     *
     * @return void
     */
    public function setImportGroup(string $importGroup): void
    {
        $this->importGroup = $importGroup;
    }

    /**
     * @return string
     */
    public function getImportGroup(): string
    {
        return $this->importGroup ?: DataImportConfig::IMPORT_GROUP_FULL;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface $dataReader
     * @param string|null $source
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    protected function prepareDataImportReport(DataReaderInterface $dataReader, ?string $source = null)
    {
        $dataImporterReportTransfer = new DataImporterReportTransfer();
        $dataImporterReportTransfer
            ->setImportType($this->getImportType())
            ->setImportedDataSetCount(0)
            ->setIsSuccess(true)
            ->setIsReaderCountable(false)
            ->setSource($source);

        if ($dataReader instanceof Countable) {
            $dataImporterReportTransfer->setIsReaderCountable(true);
            $dataImporterReportTransfer->setExpectedImportableDataSetCount($dataReader->count());
        }

        return $dataImporterReportTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface
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
     * @param int|null $dataSetPosition
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

    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return string|null
     */
    protected function getSourceFromDataImporterConfigurationTransfer(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer): ?string
    {
        if ($dataImporterConfigurationTransfer && $dataImporterConfigurationTransfer->getReaderConfiguration()) {
            return $dataImporterConfigurationTransfer->getReaderConfiguration()->getFileName();
        }

        return null;
    }

    /**
     * @param \Exception $dataImportException
     * @param \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface $dataReader
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Generated\Shared\Transfer\DataImporterReportMessageTransfer
     */
    protected function createDataSetExceptionReportMessage(
        Exception $dataImportException,
        DataReaderInterface $dataReader,
        DataSetInterface $dataSet
    ): DataImporterReportMessageTransfer {
        $dataSetIdentifier = $dataSet[$this->dataSetIdentifierKey] ?? null;

        $dataImporterReportMessageTransfer = (new DataImporterReportMessageTransfer())
            ->setMessage($dataImportException->getMessage())
            ->setDataSetNumber($dataReader->key())
            ->setDataSetIdentifier($dataSetIdentifier);

        $previousException = $dataImportException->getPrevious();
        if ($previousException instanceof DataImportException && $previousException->findError()) {
            $dataImporterReportMessageTransfer->setError($previousException->findError());
        }

        return $dataImporterReportMessageTransfer;
    }
}
