<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\CollectorBusiness;
use SprykerEngine\Zed\Locale\Business\LocaleFacade;
use SprykerFeature\Shared\Library\Storage\StorageInstanceBuilder;
use SprykerFeature\Zed\Collector\Business\Exporter\Collector;
use SprykerFeature\Zed\Collector\Business\Exporter\Reader\KeyValue\RedisReader;
use SprykerFeature\Zed\Collector\Business\Exporter\SearchCollector;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Collector\Business\Exporter\ExporterInterface;
use SprykerFeature\Zed\Collector\Business\Exporter\KeyBuilder\KvMarkerKeyBuilder;
use SprykerFeature\Zed\Collector\Business\Exporter\KeyBuilder\SearchMarkerKeyBuilder;
use SprykerFeature\Zed\Collector\Business\Exporter\MarkerInterface;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use SprykerFeature\Zed\Collector\Business\Internal\InstallElasticsearch;
use SprykerFeature\Zed\Collector\Business\Model\BatchResultInterface;
use SprykerFeature\Zed\Collector\Business\Model\FailedResultInterface;
use SprykerFeature\Zed\Collector\CollectorConfig;
use SprykerFeature\Zed\Collector\CollectorDependencyProvider;
use SprykerFeature\Zed\Collector\Persistence\CollectorQueryContainer;
use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;

/**
 * @method CollectorBusiness getFactory()
 * @method CollectorConfig getConfig()
 */
class CollectorDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return Collector
     */
    public function createYvesKeyValueExporter()
    {
        return $this->getFactory()->createExporterCollector(
            $this->createCollectorQueryContainer(),
            $this->createKeyValueExporter()
        );
    }

    /**
     * @return CollectorQueryContainer
     */
    protected function createCollectorQueryContainer()
    {
        return $this->getProvidedDependency(CollectorDependencyProvider::QUERY_CONTAINER_COLLECTOR);
    }

    /**
     * @return ExporterInterface
     */
    protected function createKeyValueExporter()
    {
        $config = $this->getConfig();

        $keyValueExporter = $this->getFactory()->createExporterKeyValueCollector(
            $this->createCollectorQueryContainer(),
            $this->createKeyValueWriter(),
            $this->createKeyValueMarker(),
            $this->createFailedResultModel(),
            $this->createBatchResultModel()
        );

        $keyValueExporter->setStandardChunkSize($config->getStandardChunkSize());
        $keyValueExporter->setChunkSizeTypeMap($config->getChunkSizeTypeMap());

        foreach ($config->getStorageCollectors() as $collectorPlugin) {
            $keyValueExporter->addCollectorPlugin($collectorPlugin);
        }
        foreach ($config->getKeyValueProcessors() as $keyValueProcessor) {
            $keyValueExporter->addDataProcessor($keyValueProcessor);
        }

        foreach ($config->getKeyValueQueryExpander() as $queryExpander) {
            $keyValueExporter->addQueryExpander($queryExpander);
        }

        foreach ($config->getKeyValueExportFailedDeciders() as $decider) {
            $keyValueExporter->addDecider($decider);
        }

        return $keyValueExporter;
    }

    /**
     * @return WriterInterface
     */
    protected function createKeyValueWriter()
    {
        return $this->getFactory()->createExporterWriterKeyValueRedisWriter(
            StorageInstanceBuilder::getStorageReadWriteInstance()
        );
    }

    /**
     * @return MarkerInterface
     */
    protected function createKeyValueMarker()
    {
        return $this->getFactory()->createExporterKeyValueMarker(
            $this->createKeyValueWriter(),
            $this->createRedisReader(),
            $this->createKvMarkerKeyBuilder()
        );
    }

    /**
     * @return RedisReader
     */
    protected function createRedisReader()
    {
        return $this->getFactory()->createExporterReaderKeyValueRedisReader(
            StorageInstanceBuilder::getStorageReadWriteInstance()
        );
    }

    /**
     * @return KvMarkerKeyBuilder
     */
    protected function createKvMarkerKeyBuilder()
    {
        return $this->getFactory()->createExporterKeyBuilderKvMarkerKeyBuilder();
    }

    /**
     * @return FailedResultInterface
     */
    protected function createFailedResultModel()
    {
        return $this->getFactory()->createModelFailedResult();
    }

    /**
     * @return BatchResultInterface
     */
    protected function createBatchResultModel()
    {
        return $this->getFactory()->createModelBatchResult();
    }

    /**
     * @return Collector
     */
    public function getYvesSearchExporter()
    {
        $searchWriter = $this->createSearchWriter();
        $config = $this->getConfig();

        return $this->getFactory()->createExporterCollector(
            $this->createCollectorQueryContainer(),
            $this->createElasticsearchExporter(
                $searchWriter,
                $config
            )
        );
    }

    /**
     * @return Collector
     */
    public function getYvesSearchUpdateExporter()
    {
        return $this->getFactory()->createExporterCollector(
            $this->createCollectorQueryContainer(),
            $this->createElasticsearchExporter(
                $this->createSearchUpdateWriter(),
                $this->getConfig()
            )
        );
    }

    /**
     * @param WriterInterface $searchWriter
     * @param CollectorConfig $config
     *
     * @return SearchCollector
     */
    protected function createElasticsearchExporter(WriterInterface $searchWriter, CollectorConfig $config)
    {
        $searchExporter = $this->getFactory()->createExporterSearchCollector(
            $this->createCollectorQueryContainer(),
            $searchWriter,
            $this->createSearchMarker(),
            $this->createFailedResultModel(),
            $this->createBatchResultModel()
        );

        $searchExporter->setStandardChunkSize($config->getStandardChunkSize());
        $searchExporter->setChunkSizeTypeMap($config->getChunkSizeTypeMap());

        foreach ($config->getSearchCollectors() as $collectorPlugin) {
            $searchExporter->addCollectorPlugin($collectorPlugin);
        }

        foreach ($config->getSearchExportFailedDeciders() as $searchDecider) {
            $searchExporter->addDecider($searchDecider);
        }

        foreach ($config->getSearchQueryExpander() as $queryExpander) {
            $searchExporter->addQueryExpander($queryExpander);
        }

        foreach ($config->getSearchProcessors() as $processor) {
            $searchExporter->addDataProcessor($processor);
        }

        return $searchExporter;
    }

    /**
     * @return WriterInterface
     */
    protected function createSearchWriter()
    {
        $settings = $this->getConfig();

        $elasticsearchWriter = $this->getFactory()->createExporterWriterSearchElasticsearchWriter(
            StorageInstanceBuilder::getElasticsearchInstance(),
            $settings->getSearchIndexName(),
            $settings->getSearchDocumentType()
        );

        return $elasticsearchWriter;
    }

    /**
     * @return WriterInterface
     */
    protected function createSearchUpdateWriter()
    {
        $settings = $this->getConfig();

        $elasticsearchUpdateWriter = $this->getFactory()->createExporterWriterSearchElasticsearchUpdateWriter(
            StorageInstanceBuilder::getElasticsearchInstance(),
            $settings->getSearchIndexName(),
            $settings->getSearchDocumentType()
        );

        return $elasticsearchUpdateWriter;
    }

    /**
     * @return MarkerInterface
     */
    protected function createSearchMarker()
    {
        return $this->getFactory()->createExporterKeyValueMarker(
            $this->createKeyValueWriter(),
            $this->createRedisReader(),
            $this->createSearchMarkerKeyBuilder()
        );
    }

    /**
     * @param MessengerInterface $messenger
     *
     * @return InstallElasticsearch
     */
    public function createInstaller(MessengerInterface $messenger)
    {
        $installer = $this->getFactory()->createInternalInstallElasticsearch(
            StorageInstanceBuilder::getElasticsearchInstance(),
            $this->getConfig()->getSearchIndexName()
        );

        $installer->setMessenger($messenger);

        return $installer;
    }

    /**
     * @return SearchMarkerKeyBuilder
     */
    protected function createSearchMarkerKeyBuilder()
    {
        return $this->getFactory()->createExporterKeyBuilderSearchMarkerKeyBuilder();
    }

    /**
     * @return LocaleFacade
     */
    public function createLocaleFacade()
    {
        return $this->getProvidedDependency(CollectorDependencyProvider::FACADE_LOCALE);
    }

}
