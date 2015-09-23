<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\CollectorBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerEngine\Zed\Touch\Persistence\TouchQueryContainer;
use SprykerFeature\Shared\Library\Storage\StorageInstanceBuilder;
use SprykerFeature\Zed\Collector\Business\Exporter\Collector;
use SprykerFeature\Zed\Collector\Business\Exporter\Reader\KeyValue\RedisReader;
use SprykerFeature\Zed\Collector\Business\Exporter\Reader\Search\ElasticsearchMarkerReader;
use SprykerFeature\Zed\Collector\Business\Exporter\SearchCollector;
use SprykerFeature\Zed\Collector\Business\Exporter\ExporterInterface;
use SprykerFeature\Zed\Collector\Business\Exporter\KeyBuilder\KvMarkerKeyBuilder;
use SprykerFeature\Zed\Collector\Business\Exporter\KeyBuilder\SearchMarkerKeyBuilder;
use SprykerFeature\Zed\Collector\Business\Exporter\MarkerInterface;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\Search\ElasticsearchMarkerWriter;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\Search\ElasticsearchWriter;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use SprykerFeature\Zed\Collector\Business\Internal\InstallElasticsearch;
use SprykerFeature\Zed\Collector\Business\Model\BatchResultInterface;
use SprykerFeature\Zed\Collector\Business\Model\FailedResultInterface;
use SprykerFeature\Zed\Collector\CollectorConfig;
use SprykerFeature\Zed\Collector\CollectorDependencyProvider;
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
            $this->createTouchQueryContainer(),
            $this->createKeyValueExporter()
        );
    }

    /**
     * @return TouchQueryContainer
     */
    protected function createTouchQueryContainer()
    {
        return $this->getProvidedDependency(CollectorDependencyProvider::QUERY_CONTAINER_TOUCH);
    }

    /**
     * @return ExporterInterface
     */
    protected function createKeyValueExporter()
    {
        $keyValueExporter = $this->getFactory()->createExporterKeyValueCollector(
            $this->createTouchQueryContainer(),
            $this->createKeyValueWriter(),
            $this->createKeyValueMarker(),
            $this->createFailedResultModel(),
            $this->createBatchResultModel(),
            $this->createExporterWriterKeyValueTouchUpdater()
        );

        foreach ($this->getProvidedDependency(CollectorDependencyProvider::STORAGE_PLUGINS) as $touchItemType => $collectorPlugin) {
            $keyValueExporter->addCollectorPlugin($touchItemType, $collectorPlugin);
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
        return $this->getFactory()->createExporterExportMarker(
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
     * @return TouchUpdaterInterface
     */
    protected function createExporterWriterSearchTouchUpdater()
    {
        return $this->getFactory()->createExporterWriterSearchTouchUpdater();
    }

    /**
     * @return TouchUpdaterInterface
     */
    protected function createExporterWriterKeyValueTouchUpdater()
    {
        return $this->getFactory()->createExporterWriterKeyValueTouchUpdater();
    }

    /**
     * @return Collector
     */
    public function getYvesSearchExporter()
    {
        $config = $this->getConfig();
        $searchWriter = $this->createSearchWriter();

        return $this->getFactory()->createExporterCollector(
            $this->createTouchQueryContainer(),
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
            $this->createTouchQueryContainer(),
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
            $this->createTouchQueryContainer(),
            $searchWriter,
            $this->createSearchMarker(),
            $this->createFailedResultModel(),
            $this->createBatchResultModel(),
            $this->createExporterWriterSearchTouchUpdater()
        );

        foreach ($this->getProvidedDependency(CollectorDependencyProvider::SEARCH_PLUGINS) as $touchItemType => $collectorPlugin) {
            $searchExporter->addCollectorPlugin($touchItemType, $collectorPlugin);
        }

        return $searchExporter;
    }

    /**
     * @return ElasticsearchWriter
     */
    protected function createSearchWriter()
    {
        $elasticSearchWriter = $this->getFactory()->createExporterWriterSearchElasticsearchWriter(
            StorageInstanceBuilder::getElasticsearchInstance(),
            $this->getConfig()->getSearchIndexName(),
            $this->getConfig()->getSearchDocumentType()
        );

        return $elasticSearchWriter;
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
        return $this->getFactory()->createExporterExportMarker(
            $this->createSearchMarkerWriter(),
            $this->createSearchMarkerReader(),
            $this->createSearchMarkerKeyBuilder()
        );
    }

    /**
     * @return ElasticsearchMarkerWriter
     */
    protected function createSearchMarkerWriter()
    {
        $elasticSearchWriter = $this->getFactory()->createExporterWriterSearchElasticsearchMarkerWriter(
            StorageInstanceBuilder::getElasticsearchInstance(),
            $this->getConfig()->getSearchIndexName()
        );

        return $elasticSearchWriter;
    }

    /**
     * @return ElasticsearchMarkerReader
     */
    protected function createSearchMarkerReader()
    {
        return $this->getFactory()->createExporterReaderSearchElasticsearchMarkerReader(
            StorageInstanceBuilder::getElasticsearchInstance(),
            $this->getConfig()->getSearchIndexName(),
            $this->getConfig()->getSearchDocumentType()
        );
    }

    /**
     * @return SearchMarkerKeyBuilder
     */
    protected function createSearchMarkerKeyBuilder()
    {
        return $this->getFactory()->createExporterKeyBuilderSearchMarkerKeyBuilder();
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

}
