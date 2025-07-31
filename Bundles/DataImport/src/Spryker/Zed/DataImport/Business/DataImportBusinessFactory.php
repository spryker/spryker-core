<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business;

use Generated\Shared\Transfer\DataImportConfigurationActionTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterQueueReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\QueueDataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\Business\DataImporter\DataImporterCollectionCollector;
use Spryker\Zed\DataImport\Business\DataImporter\DataImporterCollectionCollectorInterface;
use Spryker\Zed\DataImport\Business\DataImporter\Queue\QueueDataImporter;
use Spryker\Zed\DataImport\Business\DataImporter\Queue\QueueDataImporterInterface;
use Spryker\Zed\DataImport\Business\DataImporter\Queue\QueueMessageHelper;
use Spryker\Zed\DataImport\Business\DataImporter\Queue\QueueMessageHelperInterface;
use Spryker\Zed\DataImport\Business\DataReader\CsvReader\CsvAdapterReader;
use Spryker\Zed\DataImport\Business\DataReader\QueueReader\QueueReader;
use Spryker\Zed\DataImport\Business\DataWriter\QueueWriter\QueueWriter;
use Spryker\Zed\DataImport\Business\DataWriter\QueueWriter\QueueWriterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImporter;
use Spryker\Zed\DataImport\Business\Model\DataImporterCollection;
use Spryker\Zed\DataImport\Business\Model\DataImporterDataSetWriterAware;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\AddLocalesStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\AddStoresStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\LocalizedAttributesExtractorStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\RenameDataSetKeysStep;
use Spryker\Zed\DataImport\Business\Model\DataReader\CsvReader\CsvReader;
use Spryker\Zed\DataImport\Business\Model\DataReader\CsvReader\CsvReaderConfiguration;
use Spryker\Zed\DataImport\Business\Model\DataReader\CsvReader\CsvReaderConfigurationInterface;
use Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface;
use Spryker\Zed\DataImport\Business\Model\DataReader\FileResolver\FileResolver;
use Spryker\Zed\DataImport\Business\Model\DataReader\FileResolver\FileResolverInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSet;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBroker;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerElasticBatchTransactionAware;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerTransactionAware;
use Spryker\Zed\DataImport\Business\Model\Dump\DataImporterAccessFactoryInterface;
use Spryker\Zed\DataImport\Business\Model\Dump\ImporterDumper;
use Spryker\Zed\DataImport\Business\Model\Dump\ImporterDumperInterface;
use Spryker\Zed\DataImport\Business\Model\ElasticBatch\ElasticBatchInterface;
use Spryker\Zed\DataImport\Business\Model\ElasticBatch\MemoryAllocatedElasticBatch;
use Spryker\Zed\DataImport\Business\Model\Memory\PhpSystemMemory;
use Spryker\Zed\DataImport\Business\Model\Memory\SystemMemoryInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\DataImport\DataImportDependencyProvider;
use Spryker\Zed\DataImport\Dependency\Client\DataImportToQueueClientInterface;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToGracefulRunnerInterface;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToStoreFacadeInterface;
use Spryker\Zed\DataImport\Dependency\Service\DataImportToFlysystemServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\DataImport\DataImportConfig getConfig()
 */
class DataImportBusinessFactory extends AbstractBusinessFactory implements DataImporterAccessFactoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterCollectionInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getImporterByConfigurationAction(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporterCollectionCollector = $this->createDataImporterCollectionCollector();

        return $dataImporterCollectionCollector->getDataImporterCollection(
            $this->createDataImporterCollection(),
            $dataImportConfigurationActionTransfer,
            $this->getDataImporterByType($dataImportConfigurationActionTransfer),
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\DataImporter\DataImporterCollectionCollectorInterface
     */
    public function createDataImporterCollectionCollector(): DataImporterCollectionCollectorInterface
    {
        return new DataImporterCollectionCollector($this->getDataImporterPlugins());
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|null
     */
    public function getDataImporterByType(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ): ?DataImporterInterface {
        return null;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterCollectionInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getImporter()
    {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataImporterCollectionInterface $dataImporterCollection */
        $dataImporterCollection = $this->createDataImporterCollection();

        return $dataImporterCollection;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterPluginCollectionInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterCollectionInterface
     */
    public function createDataImporterCollection()
    {
        $dataImporterCollection = new DataImporterCollection(
            $this->getDataImportBeforeImportHookPlugins(),
            $this->getDataImportAfterImportHookPlugins(),
            $this->getConfig(),
        );

        return $dataImporterCollection;
    }

    /**
     * @return array<\Spryker\Zed\DataImport\Dependency\Plugin\DataImportBeforeImportHookInterface>
     */
    public function getDataImportBeforeImportHookPlugins()
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::DATA_IMPORT_BEFORE_HOOK_PLUGINS);
    }

    /**
     * @return array<\Spryker\Zed\DataImport\Dependency\Plugin\DataImportAfterImportHookInterface>
     */
    public function getDataImportAfterImportHookPlugins()
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::DATA_IMPORT_AFTER_HOOK_PLUGINS);
    }

    /**
     * @return array<\Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface>
     */
    public function getDefaultDataImportWriterPlugins()
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::DATA_IMPORT_DEFAULT_WRITER_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisherInterface
     */
    public function createDataImporterPublisher()
    {
        return new DataImporterPublisher();
    }

    /**
     * @deprecated This method will be renamed to `getEventFacade()` and will be public in the next major.
     *
     * @return \Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface
     */
    public function getPublicEventFacade(): DataImportToEventFacadeInterface
    {
        return $this->getEventFacade();
    }

    /**
     * @deprecated Please make sure that your `getEventFacade()` method is public. With the next major we will use public methods only in this factory.
     *
     * @return \Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface
     */
    protected function getEventFacade()
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::FACADE_EVENT);
    }

    /**
     * @return array<\Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface>
     */
    public function getDataImporterPlugins(): array
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::DATA_IMPORTER_PLUGINS);
    }

    /**
     * @phpstan-return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface&\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface&\Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface&\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     *
     * @param string $importType
     * @param \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface $reader
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function createDataImporter($importType, DataReaderInterface $reader)
    {
        return new DataImporter($importType, $reader, $this->getGracefulRunnerFacade());
    }

    /**
     * @return \Spryker\Zed\DataImport\Dependency\Facade\DataImportToGracefulRunnerInterface
     */
    public function getGracefulRunnerFacade(): DataImportToGracefulRunnerInterface
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::FACADE_GRACEFUL_RUNNER);
    }

    /**
     * @param \Generated\Shared\Transfer\QueueDataImporterConfigurationTransfer $queueDataImporterConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\DataImporter\Queue\QueueDataImporterInterface
     */
    public function createQueueDataImporter(QueueDataImporterConfigurationTransfer $queueDataImporterConfigurationTransfer): QueueDataImporterInterface
    {
        $dataReader = $this->createQueueReaderFromConfig(
            $queueDataImporterConfigurationTransfer->getReaderConfiguration(),
        );

        return new QueueDataImporter(
            $queueDataImporterConfigurationTransfer->getImportType(),
            $dataReader,
            $this->getQueueClient(),
            $this->createQueueMessageHelper(),
            $this->getGracefulRunnerFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\DataImporter\Queue\QueueMessageHelperInterface
     */
    public function createQueueMessageHelper(): QueueMessageHelperInterface
    {
        return new QueueMessageHelper($this->getUtilEncodingService());
    }

    /**
     * @param string $importType
     * @param \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface $reader
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterDataSetWriterAwareInterface
     */
    public function createDataImporterWriterAware($importType, DataReaderInterface $reader)
    {
        return new DataImporterDataSetWriterAware($importType, $reader, $this->getGracefulRunnerFacade());
    }

    /**
     * DataImportFactoryTrait::createTransactionAwareDataSetStepBroker is preferable for usage.
     *
     * @phpstan-return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface&\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface&\Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface&\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer $dataImporterConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getCsvDataImporterFromConfig(DataImporterConfigurationTransfer $dataImporterConfigurationTransfer)
    {
        $csvReader = $this->createCsvReaderFromConfig($dataImporterConfigurationTransfer->getReaderConfiguration());

        return $this->createDataImporter($dataImporterConfigurationTransfer->getImportType(), $csvReader);
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer $dataImporterConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterDataSetWriterAwareInterface
     */
    public function getCsvDataImporterWriterAwareFromConfig(DataImporterConfigurationTransfer $dataImporterConfigurationTransfer)
    {
        $csvReader = $this->createCsvReaderFromConfig($dataImporterConfigurationTransfer->getReaderConfiguration());

        return $this->createDataImporterWriterAware($dataImporterConfigurationTransfer->getImportType(), $csvReader);
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer $dataImporterReaderConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface
     */
    public function createCsvReaderFromConfig(DataImporterReaderConfigurationTransfer $dataImporterReaderConfigurationTransfer)
    {
        $csvReaderConfiguration = new CsvReaderConfiguration(
            $dataImporterReaderConfigurationTransfer,
            $this->createFileResolver(),
            $this->getConfig(),
        );

        if ($this->getConfig()->isDataImportFromOtherSourceEnabled() === false) {
            return $this->createCsvReader($csvReaderConfiguration);
        }

        return $this->createCsvAdapterReader($csvReaderConfiguration);
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataReader\FileResolver\FileResolverInterface
     */
    public function createFileResolver(): FileResolverInterface
    {
        return new FileResolver();
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\DataImport\Business\DataImportBusinessFactory::createCsvAdapterReader()} instead.
     *
     * @param \Spryker\Zed\DataImport\Business\Model\DataReader\CsvReader\CsvReaderConfigurationInterface $csvReaderConfiguration
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataReader\CsvReader\CsvReader|\Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface
     */
    public function createCsvReader(CsvReaderConfigurationInterface $csvReaderConfiguration)
    {
        $csvReader = new CsvReader($csvReaderConfiguration, $this->createDataSet());

        return $csvReader;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataReader\CsvReader\CsvReaderConfigurationInterface $csvReaderConfiguration
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataReader\CsvReader\CsvReader|\Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface
     */
    public function createCsvAdapterReader(CsvReaderConfigurationInterface $csvReaderConfiguration)
    {
        return new CsvAdapterReader(
            $csvReaderConfiguration,
            $this->getFlysystemService(),
            $this->createDataSet(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerInterface|\Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepAwareInterface
     */
    public function createDataSetStepBroker()
    {
        return new DataSetStepBroker();
    }

    /**
     * DataImportFactoryTrait::createTransactionAwareDataSetStepBroker is preferable for usage.
     *
     * @phpstan-return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerInterface&\Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepAwareInterface
     *
     * @param int|null $bulkSize
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerInterface|\Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepAwareInterface
     */
    public function createTransactionAwareDataSetStepBroker($bulkSize = null)
    {
        return new DataSetStepBrokerTransactionAware($this->getPropelConnection(), $bulkSize);
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerInterface|\Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepAwareInterface
     */
    public function createElasticBatchTransactionAwareDataSetStepBroker()
    {
        return new DataSetStepBrokerElasticBatchTransactionAware(
            $this->getPropelConnection(),
            $this->createMemoryAllocatedElasticBatch(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\ElasticBatch\ElasticBatchInterface
     */
    public function createMemoryAllocatedElasticBatch(): ElasticBatchInterface
    {
        return new MemoryAllocatedElasticBatch(
            $this->createMemoryModel(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\Memory\SystemMemoryInterface
     */
    public function createMemoryModel(): SystemMemoryInterface
    {
        return new PhpSystemMemory();
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface
     */
    public function createDataSet(array $data = [])
    {
        return new DataSet($data);
    }

    /**
     * Use this one to rename keys in your data set. `$map` has as key the current data set key and as value the new expected key.
     *
     * @param array $map
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\RenameDataSetKeysStep|\Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createRenameDataSetKeysStep(array $map)
    {
        return new RenameDataSetKeysStep($map);
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\Dump\ImporterDumperInterface
     */
    public function createImportDumper(): ImporterDumperInterface
    {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataImporterCollectionInterface $importer */
        $importer = $this->getImporter();

        return new ImporterDumper(
            $importer,
            $this,
            $this->getDataImporterPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\DataWriter\QueueWriter\QueueWriterInterface
     */
    public function createQueueWriter(): QueueWriterInterface
    {
        return new QueueWriter(
            $this->getQueueClient(),
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterQueueReaderConfigurationTransfer $queueReaderConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\DataReader\QueueReader\QueueReader|\Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface
     */
    public function createQueueReaderFromConfig(DataImporterQueueReaderConfigurationTransfer $queueReaderConfigurationTransfer): DataReaderInterface
    {
        return new QueueReader(
            $this->getQueueClient(),
            $this->createDataSet(),
            $queueReaderConfigurationTransfer,
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Dependency\Client\DataImportToQueueClientInterface
     */
    public function getQueueClient(): DataImportToQueueClientInterface
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::CLIENT_QUEUE);
    }

    /**
     * @return \Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService()
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\DataImport\Dependency\Facade\DataImportToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Zed\DataImport\Dependency\Propel\DataImportToPropelConnectionInterface
     */
    protected function getPropelConnection()
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::PROPEL_CONNECTION);
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    protected function createAddLocalesStep()
    {
        return new AddLocalesStep($this->getDataImportStoreFacade());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    protected function createAddStoresStep(): DataImportStepInterface
    {
        return new AddStoresStep($this->getDataImportStoreFacade());
    }

    /**
     * @param array $defaultAttributes
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\LocalizedAttributesExtractorStep
     */
    protected function createLocalizedAttributesExtractorStep(array $defaultAttributes = [])
    {
        return new LocalizedAttributesExtractorStep($defaultAttributes);
    }

    /**
     * @return \Spryker\Zed\DataImport\Dependency\Facade\DataImportToStoreFacadeInterface
     */
    public function getDataImportStoreFacade(): DataImportToStoreFacadeInterface
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::DATA_IMPORT_STORE_FACADE);
    }

    /**
     * @return \Spryker\Zed\DataImport\Dependency\Service\DataImportToFlysystemServiceInterface
     */
    protected function getFlysystemService(): DataImportToFlysystemServiceInterface
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::SERVICE_FLYSYSTEM);
    }
}
