<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business;

use Spryker\Zed\Collector\Business\Exporter\Writer\Search\ElasticsearchUpdateWriter;
use Spryker\Zed\Collector\Business\Exporter\Writer\KeyValue\TouchUpdater as KeyValueTouchUpdater;
use Spryker\Zed\Collector\Business\Exporter\Writer\Search\TouchUpdater;
use Spryker\Zed\Collector\Business\Model\BatchResult;
use Spryker\Zed\Collector\Business\Model\FailedResult;
use Spryker\Zed\Collector\Business\Exporter\ExportMarker;
use Spryker\Zed\Collector\Business\Exporter\Writer\KeyValue\RedisWriter;
use Spryker\Zed\Collector\Business\Exporter\KeyValueCollector;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Shared\Library\Storage\StorageInstanceBuilder;
use Spryker\Zed\Collector\Business\Exporter\Collector;
use Spryker\Zed\Collector\Business\Exporter\Reader\KeyValue\RedisReader;
use Spryker\Zed\Collector\Business\Exporter\Reader\Search\ElasticsearchMarkerReader;
use Spryker\Zed\Collector\Business\Exporter\SearchCollector;
use Spryker\Zed\Collector\Business\Exporter\KeyBuilder\KvMarkerKeyBuilder;
use Spryker\Zed\Collector\Business\Exporter\KeyBuilder\SearchMarkerKeyBuilder;
use Spryker\Zed\Collector\Business\Exporter\Writer\Search\ElasticsearchMarkerWriter;
use Spryker\Zed\Collector\Business\Exporter\Writer\Search\ElasticsearchWriter;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\Business\Internal\InstallElasticsearch;
use Spryker\Zed\Collector\CollectorConfig;
use Spryker\Zed\Collector\CollectorDependencyProvider;
use Spryker\Zed\Messenger\Business\Model\MessengerInterface;

/**
 * @method \Spryker\Zed\Collector\CollectorConfig getConfig()
 */
class CollectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Collector\Business\Exporter\Collector
     */
    public function createYvesKeyValueExporter()
    {
        return new Collector(
            $this->getTouchQueryContainer(),
            $this->createKeyValueExporter()
        );
    }

    /**
     * @return \Spryker\Zed\Touch\Persistence\TouchQueryContainer
     */
    protected function getTouchQueryContainer()
    {
        return $this->getProvidedDependency(CollectorDependencyProvider::QUERY_CONTAINER_TOUCH);
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Exporter\ExporterInterface
     */
    protected function createKeyValueExporter()
    {
        $keyValueExporter = new KeyValueCollector(
            $this->getTouchQueryContainer(),
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
     * @return \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface
     */
    protected function createKeyValueWriter()
    {
        return new RedisWriter(
            StorageInstanceBuilder::getStorageReadWriteInstance()
        );
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Exporter\MarkerInterface
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
     * @return \Spryker\Zed\Collector\Business\Exporter\Reader\KeyValue\RedisReader
     */
    protected function createRedisReader()
    {
        return new RedisReader(
            StorageInstanceBuilder::getStorageReadWriteInstance()
        );
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Exporter\KeyBuilder\KvMarkerKeyBuilder
     */
    protected function createKvMarkerKeyBuilder()
    {
        return new KvMarkerKeyBuilder();
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Model\FailedResultInterface
     */
    protected function createFailedResultModel()
    {
        return new FailedResult();
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Model\BatchResultInterface
     */
    protected function createBatchResultModel()
    {
        return new BatchResult();
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface
     */
    protected function createExporterWriterSearchTouchUpdater()
    {
        return new TouchUpdater();
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface
     */
    protected function createExporterWriterKeyValueTouchUpdater()
    {
        return new KeyValueTouchUpdater();
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Exporter\Collector
     */
    public function createYvesSearchExporter()
    {
        $config = $this->getConfig();
        $searchWriter = $this->createSearchWriter();

        return new Collector(
            $this->getTouchQueryContainer(),
            $this->createElasticsearchExporter(
                $searchWriter,
                $config
            )
        );
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Exporter\Collector
     */
    public function createYvesSearchUpdateExporter()
    {
        return new Collector(
            $this->getTouchQueryContainer(),
            $this->createElasticsearchExporter(
                $this->createSearchUpdateWriter(),
                $this->getConfig()
            )
        );
    }

    /**
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $searchWriter
     * @param \Spryker\Zed\Collector\CollectorConfig $config
     *
     * @return \Spryker\Zed\Collector\Business\Exporter\SearchCollector
     */
    protected function createElasticsearchExporter(WriterInterface $searchWriter, CollectorConfig $config)
    {
        $searchExporter = new SearchCollector(
            $this->getTouchQueryContainer(),
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
     * @return \Spryker\Zed\Collector\Business\Exporter\Writer\Search\ElasticsearchWriter
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
     * @return \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface
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
     * @return \Spryker\Zed\Collector\Business\Exporter\MarkerInterface
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
     * @return \Spryker\Zed\Collector\Business\Exporter\Writer\Search\ElasticsearchMarkerWriter
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
     * @return \Spryker\Zed\Collector\Business\Exporter\Reader\Search\ElasticsearchMarkerReader
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
     * @return \Spryker\Zed\Collector\Business\Exporter\KeyBuilder\SearchMarkerKeyBuilder
     */
    protected function createSearchMarkerKeyBuilder()
    {
        return new SearchMarkerKeyBuilder();
    }

    /**
     * @param \Spryker\Zed\Messenger\Business\Model\MessengerInterface $messenger
     *
     * @return \Spryker\Zed\Collector\Business\Internal\InstallElasticsearch
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
