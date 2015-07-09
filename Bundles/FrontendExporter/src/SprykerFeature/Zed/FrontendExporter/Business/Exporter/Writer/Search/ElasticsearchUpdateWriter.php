<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\FrontendExporter\Business\Exporter\Writer\Search;

use Elastica\Index;
use SprykerFeature\Zed\FrontendExporter\Business\Exporter\Writer\WriterInterface;
use Elastica\Client;
use Elastica\Document;

class ElasticsearchUpdateWriter implements WriterInterface
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
     * @param array  $dataSet
     * @param string $type
     *
     * @return bool
     */
    public function write(array $dataSet, $type = '')
    {
        $type = $this->index->getType($this->type);
        $type->updateDocuments($this->createDocuments($dataSet));
        $response = $type->getIndex()->refresh();

        return ($response->isOk());
    }

    /**
     * @param array $dataSet
     *
     * @return Document[]
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

}
