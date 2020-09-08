<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer\Search;

use Elastica\Client;
use Generated\Shared\Transfer\SearchCollectorConfigurationTransfer;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\Business\Mapping\MappingFactoryInterface;

class ElasticsearchMarkerWriter implements WriterInterface, ConfigurableSearchWriterInterface
{
    public const WRITER_NAME = 'elasticsearch-marker-writer';

    /**
     * @var \Elastica\Client
     */
    protected $client;

    /**
     * TODO stateful property must be refactored
     *
     * @var array
     */
    protected $metaData = [];

    /**
     * @var \Generated\Shared\Transfer\SearchCollectorConfigurationTransfer
     */
    protected $searchCollectorConfiguration;

    /**
     * @var \Spryker\Zed\Collector\Business\Mapping\MappingFactoryInterface
     */
    protected $mappingFactory;

    /**
     * @param \Elastica\Client $searchClient
     * @param string $indexName
     * @param string $type
     * @param \Spryker\Zed\Collector\Business\Mapping\MappingFactoryInterface $mappingFactory
     */
    public function __construct(Client $searchClient, $indexName, $type, MappingFactoryInterface $mappingFactory)
    {
        $this->client = $searchClient;

        $this->searchCollectorConfiguration = new SearchCollectorConfigurationTransfer();
        $this->searchCollectorConfiguration
            ->setIndexName($indexName)
            ->setTypeName($type);

        $this->mappingFactory = $mappingFactory;
    }

    /**
     * @param array $dataSet
     *
     * @return bool
     */
    public function write(array $dataSet)
    {
        foreach ($dataSet as $key => $value) {
            $this->metaData[$key] = $value;
        }

        return true;
    }

    /**
     * TODO Needs refactoring
     */
    public function __destruct()
    {
        if (!empty($this->metaData)) {
            $mapping = $this->getMapping();
            $mapping->setMeta($this->metaData)->send();
        }
    }

    /**
     * Deletes all timestamps. Parameter $dataSet is ignored.
     * TODO Needs refactoring
     *
     * @param array $dataSet
     *
     * @return bool
     */
    public function delete(array $dataSet)
    {
        $mapping = $this->getMapping();
        $response = $mapping->setMeta(['' => ''])->send(); // Empty mapping causes ClassCastException[java.util.ArrayList cannot be cast to java.util.Map]

        return $response->isOk();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::WRITER_NAME;
    }

    /**
     * @return \Elastica\Index
     */
    protected function getIndex()
    {
        return $this->client->getIndex($this->searchCollectorConfiguration->getIndexName());
    }

    /**
     * @param \Generated\Shared\Transfer\SearchCollectorConfigurationTransfer $collectorConfigurationTransfer
     *
     * @return void
     */
    public function setSearchCollectorConfiguration(SearchCollectorConfigurationTransfer $collectorConfigurationTransfer)
    {
        $this->searchCollectorConfiguration->fromArray($collectorConfigurationTransfer->modifiedToArray());
    }

    /**
     * @return \Generated\Shared\Transfer\SearchCollectorConfigurationTransfer
     */
    public function getSearchCollectorConfiguration()
    {
        return $this->searchCollectorConfiguration;
    }

    /**
     * @return \Elastica\Type\Mapping|\Spryker\Zed\Collector\Business\Mapping\MappingAdapterInterface
     */
    protected function getMapping()
    {
        return $this->mappingFactory->createMapping($this->client, $this->getSearchCollectorConfiguration());
    }
}
