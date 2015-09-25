<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter\Writer\Search;

use Elastica\Client;
use Elastica\Document;
use Elastica\Index;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\WriterInterface;

class ElasticsearchWriter implements WriterInterface
{

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Index
     */
    protected $index;

    /**
     * @var string
     */
    protected $type;

    /**
     * @param Client $searchClient
     * @param string $indexName
     * @param string $type
     */
    public function __construct(Client $searchClient, $indexName, $type)
    {
        $this->client = $searchClient;
        $this->index = $this->client->getIndex($indexName);
        $this->type = $type;
    }

    /**
     * @param array $dataSet
     * @param string $type
     *
     * @return bool
     */
    public function write(array $dataSet, $type = '')
    {
        //@todo this is wrong, the touched type does not directly map to the processed type
        $type = $this->index->getType($this->type);
        $type->addDocuments($this->createDocuments($dataSet));
        $response = $type->getIndex()->refresh();

        return $response->isOk();
    }

    /**
     * @param array $dataSet
     *
     * @return bool
     */
    public function delete(array $dataSet)
    {
        $documents = [];
        foreach ($dataSet as $key) {
            $documents[] = $this->index->getType($this->type)->getDocument($key);
        }

        $response = $this->index->deleteDocuments($documents);
        $this->index->flush(true);

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
     * @return array
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

}
