<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer\Search;

use Elastica\Client;
use Elastica\Document;
use Generated\Shared\Transfer\SearchCollectorConfigurationTransfer;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\Business\Index\IndexFactoryInterface;

class ElasticsearchUpdateWriter implements WriterInterface, ConfigurableSearchWriterInterface
{
    /**
     * @var \Elastica\Client
     */
    protected $client;

    /**
     * @var \Generated\Shared\Transfer\SearchCollectorConfigurationTransfer
     */
    protected $searchCollectorConfiguration;

    /**
     * @var \Spryker\Zed\Collector\Business\Index\IndexFactoryInterface
     */
    protected $indexFactory;

    /**
     * @param \Elastica\Client $searchClient
     * @param string $indexName
     * @param string $type
     * @param \Spryker\Zed\Collector\Business\Index\IndexFactoryInterface $indexFactory
     */
    public function __construct(Client $searchClient, $indexName, $type, IndexFactoryInterface $indexFactory)
    {
        $this->client = $searchClient;

        $this->searchCollectorConfiguration = new SearchCollectorConfigurationTransfer();
        $this->searchCollectorConfiguration
            ->setIndexName($indexName)
            ->setTypeName($type);

        $this->indexFactory = $indexFactory;
    }

    /**
     * @param array $dataSet
     *
     * @return bool
     */
    public function write(array $dataSet)
    {
        $this->getIndex()->updateDocuments($this->createDocuments($dataSet));
        $response = $this->getIndex()->refresh();

        return $response->isOk();
    }

    /**
     * @param array $dataSet
     *
     * @return bool
     */
    public function delete(array $dataSet)
    {
        return false;
    }

    /**
     * @param array $dataSet
     *
     * @return \Elastica\Document[]
     */
    protected function createDocuments(array $dataSet)
    {
        $documentPrototype = new Document();
        $documents = [];

        foreach ($dataSet as $key => $data) {
            $document = clone $documentPrototype;
            $document->setId($key);
            $document->setData($data);
            $documents[] = $document;
        }

        return $documents;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'elasticsearch-update-writer';
    }

    /**
     * @return \Elastica\Index|\Spryker\Zed\Collector\Business\Index\IndexAdapterInterface
     */
    protected function getIndex()
    {
        return $this->indexFactory->createIndex($this->client, $this->getSearchCollectorConfiguration());
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
