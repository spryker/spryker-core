<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Shared\Library\Storage\StorageInstanceBuilder;
use Spryker\Zed\Collector\Business\Exporter\CollectorExporter;
use Spryker\Zed\Collector\Business\Exporter\ExportMarker;
use Spryker\Zed\Collector\Business\Exporter\FileExporter;
use Spryker\Zed\Collector\Business\Exporter\KeyBuilder\KvMarkerKeyBuilder;
use Spryker\Zed\Collector\Business\Exporter\KeyBuilder\SearchMarkerKeyBuilder;
use Spryker\Zed\Collector\Business\Exporter\Reader\File\FileReader;
use Spryker\Zed\Collector\Business\Exporter\Reader\Search\ElasticsearchMarkerReader;
use Spryker\Zed\Collector\Business\Exporter\Reader\Search\ElasticsearchReader;
use Spryker\Zed\Collector\Business\Exporter\Reader\Storage\RedisReader;
use Spryker\Zed\Collector\Business\Exporter\SearchExporter;
use Spryker\Zed\Collector\Business\Exporter\StorageExporter;
use Spryker\Zed\Collector\Business\Exporter\Writer\File\Adapter\CsvAdapter;
use Spryker\Zed\Collector\Business\Exporter\Writer\File\FileWriter;
use Spryker\Zed\Collector\Business\Exporter\Writer\File\NameGenerator\CsvNameGenerator;
use Spryker\Zed\Collector\Business\Exporter\Writer\File\NameGenerator\CsvNameGeneratorBuilder;
use Spryker\Zed\Collector\Business\Exporter\Writer\Search\ElasticsearchMarkerWriter;
use Spryker\Zed\Collector\Business\Exporter\Writer\Search\ElasticsearchUpdateWriter;
use Spryker\Zed\Collector\Business\Exporter\Writer\Search\ElasticsearchWriter;
use Spryker\Zed\Collector\Business\Exporter\Writer\Search\TouchUpdater as SearchTouchUpdater;
use Spryker\Zed\Collector\Business\Exporter\Writer\Storage\RedisWriter;
use Spryker\Zed\Collector\Business\Exporter\Writer\Storage\TouchUpdater as StorageTouchUpdater;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\Business\Manager\CollectorManager;
use Spryker\Zed\Collector\Business\Model\BatchResult;
use Spryker\Zed\Collector\Business\Model\BulkTouchQueryBuilder;
use Spryker\Zed\Collector\Business\Model\FailedResult;
use Spryker\Zed\Collector\CollectorDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Collector\CollectorConfig getConfig()
 */
class CollectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Collector\Business\Exporter\CollectorExporter
     */
    public function createYvesStorageExporter()
    {
        return new CollectorExporter(
            $this->getTouchQueryContainer(),
            $this->getLocaleFacade(),
            $this->createStorageExporter(),
            $this->getConfig()->getAvailableCollectorTypes()
        );
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Exporter\CollectorExporter
     */
    public function createYvesFileExporter()
    {
        return new CollectorExporter(
            $this->getTouchQueryContainer(),
            $this->getLocaleFacade(),
            $this->createFileExporter(),
            $this->getConfig()->getAvailableCollectorTypes()
        );
    }

    /**
     * @return \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected function getTouchQueryContainer()
    {
        return $this->getProvidedDependency(CollectorDependencyProvider::QUERY_CONTAINER_TOUCH);
    }

    /**
     * @return \Spryker\Zed\Collector\Dependency\Facade\CollectorToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(CollectorDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Exporter\ExporterInterface
     */
    protected function createStorageExporter()
    {
        $storageExporter = new StorageExporter(
            $this->getTouchQueryContainer(),
            $this->createRedisReader(),
            $this->createStorageWriter(),
            $this->createStorageMarker(),
            $this->createFailedResultModel(),
            $this->createBatchResultModel(),
            $this->createExporterWriterStorageTouchUpdater(),
            $this->getCollectorStorageExporterPlugins()
        );

        return $storageExporter;
    }

    /**
     * @return \Spryker\Zed\Collector\Dependency\Plugin\CollectorPluginCollectionInterface
     */
    protected function getCollectorStorageExporterPlugins()
    {
        return $this->getProvidedDependency(CollectorDependencyProvider::STORAGE_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Exporter\ExporterInterface
     */
    protected function createFileExporter()
    {
        $fileExporter = new FileExporter(
            $this->getTouchQueryContainer(),
            $this->createFileReader(),
            $this->createFileWriter(),
            $this->createStorageMarker(),
            $this->createFailedResultModel(),
            $this->createBatchResultModel(),
            $this->createExporterWriterStorageTouchUpdater(),
            $this->getCollectorFileExporterPlugins(),
            $this->createCsvNameGeneratorBuilder()
        );

        return $fileExporter;
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Exporter\Writer\File\FileWriter
     */
    public function createFileWriter()
    {
        $csvFileWriterAdapter = $this->createCsvFileWriterAdapter();
        $fileWriter = new FileWriter($csvFileWriterAdapter);

        return $fileWriter;
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Exporter\Reader\File\FileReader
     */
    public function createFileReader()
    {
        $fileReader = new FileReader();

        return $fileReader;
    }

    /**
     * @param string $type
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Spryker\Zed\Collector\Business\Exporter\Writer\File\NameGenerator\CsvNameGenerator
     */
    public function createCsvNameGenerator($type, LocaleTransfer $localeTransfer)
    {
        return new CsvNameGenerator($type, $localeTransfer);
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Exporter\Writer\File\NameGenerator\CsvNameGeneratorBuilder
     */
    public function createCsvNameGeneratorBuilder()
    {
        return new CsvNameGeneratorBuilder();
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Exporter\Writer\File\Adapter\CsvAdapter
     */
    protected function createCsvFileWriterAdapter()
    {
        return new CsvAdapter($this->getConfig()->getFileExporterOutputDir());
    }

    /**
     * @return \Spryker\Zed\Collector\Dependency\Plugin\CollectorPluginCollectionInterface
     */
    protected function getCollectorFileExporterPlugins()
    {
        return $this->getProvidedDependency(CollectorDependencyProvider::FILE_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface
     */
    protected function createStorageWriter()
    {
        return new RedisWriter(
            StorageInstanceBuilder::getStorageReadWriteInstance()
        );
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Exporter\MarkerInterface
     */
    public function createStorageMarker()
    {
        return new ExportMarker(
            $this->createStorageWriter(),
            $this->createRedisReader(),
            $this->createKvMarkerKeyBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Exporter\Reader\Storage\RedisReader
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
        return new SearchTouchUpdater(
            $this->createBulkUpdateTouchQuery(),
            $this->createBulkDeleteTouchQuery()
        );
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface
     */
    protected function createExporterWriterStorageTouchUpdater()
    {
        return new StorageTouchUpdater(
            $this->createBulkUpdateTouchQuery(),
            $this->createBulkDeleteTouchQuery()
        );
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Exporter\CollectorExporter
     */
    public function createYvesSearchExporter()
    {
        $searchWriter = $this->createSearchWriter();

        return new CollectorExporter(
            $this->getTouchQueryContainer(),
            $this->getLocaleFacade(),
            $this->createElasticsearchExporter($searchWriter),
            $this->getConfig()->getAvailableCollectorTypes()
        );
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Exporter\CollectorExporter
     */
    public function createYvesSearchUpdateExporter()
    {
        return new CollectorExporter(
            $this->getTouchQueryContainer(),
            $this->getLocaleFacade(),
            $this->createElasticsearchExporter($this->createSearchUpdateWriter()),
            $this->getConfig()->getAvailableCollectorTypes()
        );
    }

    /**
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $searchWriter
     *
     * @return \Spryker\Zed\Collector\Business\Exporter\SearchExporter
     */
    protected function createElasticsearchExporter(WriterInterface $searchWriter)
    {
        $searchExporter = new SearchExporter(
            $this->getTouchQueryContainer(),
            $this->createSearchReader(),
            $searchWriter,
            $this->createSearchMarker(),
            $this->createFailedResultModel(),
            $this->createBatchResultModel(),
            $this->createExporterWriterSearchTouchUpdater(),
            $this->getCollectorSearchExporterPlugins()
        );

        return $searchExporter;
    }

    /**
     * @return \Spryker\Zed\Collector\Dependency\Plugin\CollectorPluginCollectionInterface
     */
    protected function getCollectorSearchExporterPlugins()
    {
        return $this->getProvidedDependency(CollectorDependencyProvider::SEARCH_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Exporter\Writer\Search\ElasticsearchWriter
     */
    protected function createSearchWriter()
    {
        $elasticsearchWriter = new ElasticsearchWriter(
            StorageInstanceBuilder::getElasticsearchInstance(),
            $this->getConfig()->getSearchIndexName(),
            $this->getConfig()->getSearchDocumentType()
        );

        return $elasticsearchWriter;
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
        $elasticsearchMarkerWriter = new ElasticsearchMarkerWriter(
            StorageInstanceBuilder::getElasticsearchInstance(),
            $this->getConfig()->getSearchIndexName(),
            $this->getConfig()->getSearchDocumentType()
        );

        return $elasticsearchMarkerWriter;
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
     * @return \Spryker\Zed\Collector\Business\Exporter\Reader\Search\ElasticsearchReader
     */
    protected function createSearchReader()
    {
        return new ElasticsearchReader(
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
     * @return \Spryker\Zed\Collector\Persistence\Pdo\BulkUpdateTouchKeyByIdQueryInterface
     */
    protected function createBulkUpdateTouchQuery()
    {
        return $this->createBulkTouchQueryBuilder()
            ->createBulkTouchUpdateQuery();
    }

    /**
     * @return \Spryker\Zed\Collector\Persistence\Pdo\BulkDeleteTouchByIdQueryInterface
     */
    protected function createBulkDeleteTouchQuery()
    {
        return $this->createBulkTouchQueryBuilder()
            ->createBulkTouchDeleteQuery();
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Model\BulkTouchQueryBuilder
     */
    protected function createBulkTouchQueryBuilder()
    {
        return new BulkTouchQueryBuilder($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Manager\CollectorManager
     */
    public function createCollectorManager()
    {
        return new CollectorManager();
    }

}
