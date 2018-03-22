<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer\Search;

use Elastica\Client;
use Elastica\Document;
use Elastica\Exception\NotFoundException;
use Generated\Shared\Transfer\SearchCollectorConfigurationTransfer;
use Spryker\Zed\Collector\Business\Exporter\Exception\InvalidDataSetException;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;

class ElasticsearchWriter implements WriterInterface, ConfigurableSearchWriterInterface
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
     * @throws \Spryker\Zed\Collector\Business\Exporter\Exception\InvalidDataSetException
     *
     * @return bool
     */
    public function write(array $dataSet)
    {
        if ($this->hasIntegerKeys($dataSet)) {
            throw new InvalidDataSetException();
        }

        $documents = $this->createDocuments($dataSet);
        $this->getType()->addDocuments($documents);
        $response = $this->getIndex()->refresh();

        return $response->isOk();
    }

    /**
     * @param array $dataSet
     *
     * @throws \Spryker\Zed\Collector\Business\Exporter\Exception\InvalidDataSetException
     *
     * @return bool
     */
    public function delete(array $dataSet)
    {
        if ($this->hasIntegerKeys($dataSet)) {
            throw new InvalidDataSetException();
        }

        $documents = [];
        $keys = array_keys($dataSet);
        foreach ($keys as $key) {
            try {
                $documents[] = $this->getType()
                    ->getDocument($key, ['routing' => $key])
                    ->setRouting($key);
            } catch (NotFoundException $e) {
                continue;
            }
        }

        if (!$documents) {
            return true;
        }

        $response = $this->getIndex()->deleteDocuments($documents);
        $this->getIndex()->flush();

        return $response->isOk();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'elasticsearch-writer';
    }

    /**
     * @param array $dataSet
     *
     * @throws \Spryker\Zed\Collector\Business\Exporter\Exception\InvalidDataSetException
     *
     * @return array
     */
    protected function createDocuments(array $dataSet)
    {
        if ($this->hasIntegerKeys($dataSet)) {
            throw new InvalidDataSetException();
        }

        $documentPrototype = new Document();
        $documents = [];

        foreach ($dataSet as $key => $data) {
            $document = clone $documentPrototype;

            if (is_array($data)) {
                $this->setParent($document, $data);
            }

            $document->setId($key);
            $document->setData($data);
            $documents[] = $document;
        }

        return $documents;
    }

    /**
     * Checks if the given array has any integer based (non-textual) keys
     *
     * @param array $array
     *
     * @return bool
     */
    protected function hasIntegerKeys(array $array)
    {
        return count(array_filter(array_keys($array), 'is_int')) > 0;
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

    /**
     * @param \Elastica\Document $document
     * @param array $data
     *
     * @return void
     */
    protected function setParent(Document $document, array $data)
    {
        if (isset($data['parent'])) {
            $document->setParent($data['parent']);
        }
    }
}
