<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer\Search;

use Elastica\Client;
use Elastica\Type\Mapping;
use Generated\Shared\Transfer\SearchCollectorConfigurationTransfer;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;

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
     * @param \Elastica\Client $searchClient
     * @param string $indexName
     * @param string $type
     */
    public function __construct(Client $searchClient, $indexName, $type)
    {
        $this->client = $searchClient;

        $this->searchCollectorConfiguration = new SearchCollectorConfigurationTransfer();
        $this->searchCollectorConfiguration
            ->setIndexName($indexName)
            ->setTypeName($type);
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
            $mapping = new Mapping($this->getType());
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
        $mapping = new Mapping($this->getType());
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
     * @return \Elastica\Type
     */
    protected function getType()
    {
        return $this->getIndex()->getType($this->searchCollectorConfiguration->getTypeName());
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
}
