<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business;

use SprykerFeature\Zed\Collector\Business\Exporter\Writer\Search\ElasticsearchUpdateWriter;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\KeyValue\TouchUpdater as KeyValueTouchUpdater;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\Search\TouchUpdater;
use SprykerFeature\Zed\Collector\Business\Model\BatchResult;
use SprykerFeature\Zed\Collector\Business\Model\FailedResult;
use SprykerFeature\Zed\Collector\Business\Exporter\ExportMarker;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\KeyValue\RedisWriter;
use SprykerFeature\Zed\Collector\Business\Exporter\KeyValueCollector;
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
 * @method CollectorConfig getConfig()
 */
class CollectorDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return Collector
     */
    public function createYvesKeyValueExporter()
    {
        return new Collector(
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
        $keyValueExporter = new KeyValueCollector(
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
        return new RedisWriter(
            StorageInstanceBuilder::getStorageReadWriteInstance()
        );
    }

    /**
     * @return MarkerInterface
     */
    public function createKeyValueMarker()
    {
        return new ExportMarker(
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
        return new RedisReader(
            StorageInstanceBuilder::getStorageReadWriteInstance()
        );
    }

    /**
     * @return KvMarkerKeyBuilder
     */
    protected function createKvMarkerKeyBuilder()
    {
        return new KvMarkerKeyBuilder();
    }

    /**
     * @return FailedResultInterface
     */
    protected function createFailedResultModel()
    {
        return new FailedResult();
    }

    /**
     * @return BatchResultInterface
     */
    protected function createBatchResultModel()
    {
        return new BatchResult();
    }

    /**
     * @return TouchUpdaterInterface
     */
    protected function createExporterWriterSearchTouchUpdater()
    {
        return new TouchUpdater();
    }

    /**
     * @return TouchUpdaterInterface
     */
    protected function createExporterWriterKeyValueTouchUpdater()
    {
        return new KeyValueTouchUpdater();
    }

    /**
     * @return Collector
     */
    public function getYvesSearchExporter()
    {
        $config = $this->getConfig();
        $searchWriter = $this->createSearchWriter();

        return new Collector(
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
        return new Collector(
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
        $searchExporter = new SearchCollector(
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
        $elasticSearchWriter = new ElasticsearchWriter(
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

        $elasticsearchUpdateWriter = new ElasticsearchUpdateWriter(
            StorageInstanceBuilder::getElasticsearchInstance(),
            $settings->getSearchIndexName(),
            $settings->getSearchDocumentType()
        );

        return $elasticsearchUpdateWriter;
    }

    /**
     * @return MarkerInterface
     */
    public function createSearchMarker()
    {
        return new ExportMarker(
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
        $elasticSearchWriter = new ElasticsearchMarkerWriter(
            StorageInstanceBuilder::getElasticsearchInstance(),
            $this->getConfig()->getSearchIndexName(),
            $this->getConfig()->getSearchDocumentType()
        );

        return $elasticSearchWriter;
    }

    /**
     * @return ElasticsearchMarkerReader
     */
    protected function createSearchMarkerReader()
    {
        return new ElasticsearchMarkerReader(
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
        return new SearchMarkerKeyBuilder();
    }

    /**
     * @param MessengerInterface $messenger
     *
     * @return InstallElasticsearch
     */
    public function createInstaller(MessengerInterface $messenger)
    {
        $installer = new InstallElasticsearch(
            StorageInstanceBuilder::getElasticsearchInstance(),
            $this->getConfig()->getSearchIndexName()
        );

        $installer->setMessenger($messenger);

        return $installer;
    }

}
