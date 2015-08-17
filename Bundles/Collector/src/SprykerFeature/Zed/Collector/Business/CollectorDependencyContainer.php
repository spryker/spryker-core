<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\CollectorBusiness;
use SprykerEngine\Zed\Locale\Business\LocaleFacade;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerEngine\Zed\Touch\Persistence\TouchQueryContainer;
use SprykerFeature\Shared\Library\Storage\StorageInstanceBuilder;
use SprykerFeature\Zed\Collector\Business\Exporter\Collector;
use SprykerFeature\Zed\Collector\Business\Exporter\Reader\KeyValue\RedisReader;
use SprykerFeature\Zed\Collector\Business\Exporter\SearchCollector;
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
            $this->createBatchResultModel()
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
            $this->createBatchResultModel()
        );

        foreach ($this->getProvidedDependency(CollectorDependencyProvider::SEARCH_PLUGINS) as $touchItemType => $collectorPlugin) {
            $searchExporter->addCollectorPlugin($touchItemType, $collectorPlugin);
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
