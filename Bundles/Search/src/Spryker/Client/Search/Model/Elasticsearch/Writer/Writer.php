<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Writer;

use Elastica\Client;
use Elastica\Document;
use Elastica\Exception\NotFoundException;
use Spryker\Client\Search\Exception\InvalidDataSetException;
use Symfony\Component\VarDumper\VarDumper;

class Writer implements WriterInterface
{
    /**
     * @var \Elastica\Client
     */
    protected $client;

    /**
     * @var \Elastica\Index
     */
    protected $index;

    /**
     * @var string
     */
    protected $type;

    /**
     * @param \Elastica\Client $searchClient
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
     * @param string|null $typeName
     * @param string|null $indexName
     *
     * @throws \Spryker\Client\Search\Exception\InvalidDataSetException
     *
     * @return bool
     */
    public function write(array $dataSet, $typeName = null, $indexName = null)
    {
        $defaultType = $this->type;
        $defaultIndex = $this->index;

        if ($this->hasIntegerKeys($dataSet)) {
            throw new InvalidDataSetException();
        }

        if ($typeName) {
            $defaultType = $typeName;
        }

        if ($indexName) {
            $defaultIndex = $this->client->getIndex($indexName);
        }

        $type = $defaultIndex->getType($defaultType);
        $documents = $this->createDocuments($dataSet);
        $type->addDocuments($documents);
        $response = $type->getIndex()->refresh();

        return $response->isOk();
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer[] $searchDocumentTransfers
     *
     * @return bool
     */
    public function writeBulk(array $searchDocumentTransfers): bool
    {
        $documents = $this->mapTransferToDocuments($searchDocumentTransfers);
        $response = $this->client->addDocuments($documents);

        return $response->isOk();
    }

    /**
     * @param array $dataSet
     * @param string|null $typeName
     * @param string|null $indexName
     *
     * @throws \Spryker\Client\Search\Exception\InvalidDataSetException
     *
     * @return bool
     */
    public function delete(array $dataSet, $typeName = null, $indexName = null)
    {
        if ($this->hasIntegerKeys($dataSet)) {
            throw new InvalidDataSetException();
        }

        $defaultType = $this->type;
        $defaultIndex = $this->index;

        if ($typeName) {
            $defaultType = $typeName;
        }

        if ($indexName) {
            $defaultIndex = $this->client->getIndex($indexName);
        }

        $documents = [];
        foreach ($dataSet as $key => $value) {
            try {
                $documents[] = $defaultIndex->getType($defaultType)->getDocument($key);
            } catch (NotFoundException $e) {
                continue;
            }
        }

        if (!$documents) {
            return true;
        }

        $response = $defaultIndex->deleteDocuments($documents);
        $defaultIndex->flush();

        return $response->isOk();
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer[] $searchDocumentTransfers
     *
     * @return bool
     */
    public function deleteBulk(array $searchDocumentTransfers): bool
    {
        $documents = $this->mapTransferToDocuments($searchDocumentTransfers);
        $response = $this->client->deleteDocuments($documents);

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
     * @throws \Spryker\Client\Search\Exception\InvalidDataSetException
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
            $document->setId($key);
            $document->setData($data);
            $documents[] = $document;
        }

        return $documents;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer[] $searchDocumentTransfers
     *
     * @return \Elastica\Document[]
     */
    protected function mapTransferToDocuments(array $searchDocumentTransfers): array
    {
        $documentPrototype = new Document();
        $documents = [];

        foreach ($searchDocumentTransfers as $searchDocumentTransfer) {
            $document = clone $documentPrototype;
            $document->setId($searchDocumentTransfer->getId());
            $document->setData($searchDocumentTransfer->getData());
            $document->setType($searchDocumentTransfer->getType() ?: $this->type);
            $document->setIndex($searchDocumentTransfer->getIndex() ?: $this->index);
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
        $keys = array_keys($array);
        $integerKeys = array_filter($keys, 'is_int');

        return count($integerKeys) > 0;
    }
}
