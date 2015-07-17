<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\FrontendExporter\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\FrontendExporterBusiness;
use SprykerEngine\Zed\Locale\Business\LocaleFacade;
use SprykerFeature\Shared\Library\Storage\StorageInstanceBuilder;
use SprykerFeature\Zed\FrontendExporter\Business\Exporter\FrontendExporter;
use SprykerFeature\Zed\FrontendExporter\Business\Exporter\Reader\KeyValue\RedisReader;
use SprykerFeature\Zed\FrontendExporter\Business\Exporter\SearchExporter;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\FrontendExporter\Business\Exporter\ExporterInterface;
use SprykerFeature\Zed\FrontendExporter\Business\Exporter\KeyBuilder\KvMarkerKeyBuilder;
use SprykerFeature\Zed\FrontendExporter\Business\Exporter\KeyBuilder\SearchMarkerKeyBuilder;
use SprykerFeature\Zed\FrontendExporter\Business\Exporter\MarkerInterface;
use SprykerFeature\Zed\FrontendExporter\Business\Exporter\Writer\WriterInterface;
use SprykerFeature\Zed\FrontendExporter\Business\Internal\InstallElasticsearch;
use SprykerFeature\Zed\FrontendExporter\Business\Model\BatchResultInterface;
use SprykerFeature\Zed\FrontendExporter\Business\Model\FailedResultInterface;
use SprykerFeature\Zed\FrontendExporter\FrontendExporterConfig;
use SprykerFeature\Zed\FrontendExporter\FrontendExporterDependencyProvider;
use SprykerFeature\Zed\FrontendExporter\Persistence\FrontendExporterQueryContainer;
use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;

/**
 * @method FrontendExporterBusiness getFactory()
 * @method FrontendExporterConfig getConfig()
 */
class FrontendExporterDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return FrontendExporter
     */
    public function createYvesKeyValueExporter()
    {
        return $this->getFactory()->createExporterFrontendExporter(
            $this->createFrontendExporterQueryContainer(),
            $this->createKeyValueExporter()
        );
    }

    /**
     * @return FrontendExporterQueryContainer
     */
    protected function createFrontendExporterQueryContainer()
    {
        return $this->getLocator()->FrontendExporter()->queryContainer();
    }

    /**
     * @return ExporterInterface
     */
    protected function createKeyValueExporter()
    {
        $config = $this->getConfig();

        $keyValueExporter = $this->getFactory()->createExporterKeyValueExporter(
            $this->createFrontendExporterQueryContainer(),
            $this->createKeyValueWriter(),
            $this->createKeyValueMarker(),
            $this->createFailedResultModel(),
            $this->createBatchResultModel()
        );

        $keyValueExporter->setStandardChunkSize($config->getStandardChunkSize());
        $keyValueExporter->setChunkSizeTypeMap($config->getChunkSizeTypeMap());

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
     * @return FrontendExporter
     */
    public function getYvesSearchExporter()
    {
        $searchWriter = $this->createSearchWriter();
        $config = $this->getConfig();

        return $this->getFactory()->createExporterFrontendExporter(
            $this->createFrontendExporterQueryContainer(),
            $this->createElasticsearchExporter(
                $searchWriter,
                $config
            )
        );
    }

    /**
     * @return FrontendExporter
     */
    public function getYvesSearchUpdateExporter()
    {
        return $this->getFactory()->createExporterFrontendExporter(
            $this->createFrontendExporterQueryContainer(),
            $this->createElasticsearchExporter(
                $this->createSearchUpdateWriter(),
                $this->getConfig()
            )
        );
    }

    /**
     * @param WriterInterface $searchWriter
     * @param FrontendExporterConfig $config
     *
     * @return SearchExporter
     */
    protected function createElasticsearchExporter(WriterInterface $searchWriter, FrontendExporterConfig $config)
    {
        $searchExporter = $this->getFactory()->createExporterSearchExporter(
            $this->createFrontendExporterQueryContainer(),
            $searchWriter,
            $this->createSearchMarker(),
            $this->createFailedResultModel(),
            $this->createBatchResultModel()
        );

        $searchExporter->setStandardChunkSize($config->getStandardChunkSize());
        $searchExporter->setChunkSizeTypeMap($config->getChunkSizeTypeMap());

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
        return $this->getProvidedDependency(FrontendExporterDependencyProvider::FACADE_LOCALE);
    }

}
