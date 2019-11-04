<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Writer;

use Elastica\Client;
use Elastica\Document;
use Exception;
use Generated\Shared\Transfer\SearchContextTransfer;

class Writer implements WriterInterface
{
    /**
     * @var \Elastica\Client
     */
    protected $client;

    /**
     * @param \Elastica\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function write(array $data, SearchContextTransfer $searchContextTransfer): bool
    {
        $indexName = $searchContextTransfer->getElasticsearchContext()->getSourceName();

        if ($this->hasIntegerKeys($data)) {
            throw new Exception();
        }

        $documents = $this->createDocuments($data);
        $type = $this->client->getIndex($indexName)->getType('_doc');
        $type->addDocuments($documents);
        $response = $type->getIndex()->refresh();

        return $response->isOk();
    }

    /**
     * @param array $array
     *
     * @return bool
     */
    protected function hasIntegerKeys(array $array): bool
    {
        $keys = array_keys($array);
        $integerKeys = array_filter($keys, 'is_int');

        return count($integerKeys) > 0;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function createDocuments(array $data): array
    {
        $documentPrototype = new Document();
        $documents = [];

        foreach ($data as $key => $datum) {
            $document = clone $documentPrototype;
            $document->setId($key);
            $document->setData($datum);
            $documents[] = $document;
        }

        return $documents;
    }
}
