<?php

namespace SprykerFeature\Zed\FrontendExporter\Business;

use Composer\Installer\InstallerInterface;
use Generated\Zed\Ide\FactoryAutoCompletion\FrontendExporterBusiness;
use SprykerFeature\Shared\Library\Storage\StorageInstanceBuilder;
use SprykerFeature\Zed\FrontendExporter\Business\Exporter\FrontendExporter;
use SprykerFeature\Zed\FrontendExporter\Business\Exporter\Reader\KeyValue\RedisReader;
use SprykerFeature\Zed\FrontendExporter\Business\Exporter\SearchExporter;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\FrontendExporter\Business\Exporter\ExporterInterface;
use SprykerFeature\Zed\FrontendExporter\Business\Exporter\KeyBuilder\KvMarkerKeyBuilder;
use SprykerFeature\Zed\FrontendExporter\Business\Exporter\KeyBuilder\SearchMarkerKeyBuilder;
use SprykerFeature\Zed\FrontendExporter\Business\Exporter\KeyValueExporter;
use SprykerFeature\Zed\FrontendExporter\Business\Exporter\KeyValueMarker;
use SprykerFeature\Zed\FrontendExporter\Business\Exporter\MarkerInterface;
use SprykerFeature\Zed\FrontendExporter\Business\Exporter\Writer\KeyValue\RedisWriter;
use SprykerFeature\Zed\FrontendExporter\Business\Exporter\Writer\Search\ElasticsearchUpdateWriter;
use SprykerFeature\Zed\FrontendExporter\Business\Exporter\Writer\Search\ElasticsearchWriter;
use SprykerFeature\Zed\FrontendExporter\Business\Exporter\Writer\WriterInterface;
use SprykerFeature\Zed\FrontendExporter\Business\Internal\InstallElasticsearch;
use SprykerFeature\Zed\FrontendExporter\Business\Model\BatchResultInterface;
use SprykerFeature\Zed\FrontendExporter\Business\Model\FailedResultInterface;
use SprykerFeature\Zed\FrontendExporter\Persistence\FrontendExporterQueryContainer;
use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;

/**
 * @method FrontendExporterBusiness getFactory()
 */
class FrontendExporterDependencyContainer extends AbstractDependencyContainer
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
        $keyValueExporter = $this->getFactory()->createExporterKeyValueExporter(
            $this->createFrontendExporterQueryContainer(),
            $this->createKeyValueWriter(),
            $this->createKeyValueMarker(),
            $this->createFailedResultModel(),
            $this->createBatchResultModel()
        );

        $settings = $this->createSettings();

        foreach ($settings->getKeyValueProcessors() as $keyValueProcessor) {
            $keyValueExporter->addDataProcessor($keyValueProcessor);
        }

        foreach ($settings->getKeyValueQueryExpander() as $queryExpander) {
            $keyValueExporter->addQueryExpander($queryExpander);
        }

        foreach ($settings->getKeyValueExportFailedDeciders() as $decider) {
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
            StorageInstanceBuilder::getKvStorageReadWriteInstance()
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
            StorageInstanceBuilder::getKvStorageReadWriteInstance()
        );
    }

    /**
     * @return KvMarkerKeyBuilder|
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
     * @return FrontendExporterSettings
     */
    public function createSettings()
    {
        return $this->getFactory()->createFrontendExporterSettings($this->getLocator());
    }

    /**
     * @return FrontendExporter
     */
    public function getYvesSearchExporter()
    {
        $searchWriter = $this->createSearchWriter();
        $settings = $this->createSettings();

        return $this->getFactory()->createExporterFrontendExporter(
            $this->createFrontendExporterQueryContainer(),
            $this->createElasticsearchExporter(
                $searchWriter,
                $settings
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
                $this->createSettings()
            )
        );
    }

    /**
     * @param WriterInterface $searchWriter
     * @param FrontendExporterSettings $settings
     *
     * @return SearchExporter
     */
    protected function createElasticsearchExporter(WriterInterface $searchWriter, FrontendExporterSettings $settings)
    {
        $searchExporter = $this->getFactory()->createExporterSearchExporter(
            $this->createFrontendExporterQueryContainer(),
            $searchWriter,
            $this->createSearchMarker(),
            $this->createFailedResultModel(),
            $this->createBatchResultModel()
        );

        foreach ($settings->getSearchExportFailedDeciders() as $searchDecider) {
            $searchExporter->addDecider($searchDecider);
        }

        foreach ($settings->getSearchQueryExpander() as $queryExpander) {
            $searchExporter->addQueryExpander($queryExpander);
        }

        foreach ($settings->getSearchProcessors() as $processor) {
            $searchExporter->addDataProcessor($processor);
        }

        return $searchExporter;
    }

    /**
     * @return WriterInterface
     */
    protected function createSearchWriter()
    {
        $settings = $this->createSettings();

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
        $settings = $this->createSettings();

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
            $this->createSettings()->getSearchIndexName()
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
}
