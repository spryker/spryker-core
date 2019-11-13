<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Writer;

use Elastica\Client;
use Elastica\Document;
use Elastica\Index;
use Generated\Shared\Transfer\SearchDocumentTransfer;
use Spryker\Client\SearchElasticsearch\SearchElasticsearchConfig;

class DocumentWriter implements DocumentWriterInterface
{
    /**
     * @var \Elastica\Client
     */
    protected $client;

    /**
     * @var \Spryker\Client\SearchElasticsearch\SearchElasticsearchConfig
     */
    protected $config;

    /**
     * @param \Elastica\Client $client
     * @param \Spryker\Client\SearchElasticsearch\SearchElasticsearchConfig $config
     */
    public function __construct(Client $client, SearchElasticsearchConfig $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return bool
     */
    public function writeDocument(SearchDocumentTransfer $searchDocumentTransfer): bool
    {
        $document = $this->mapSearchDocumentTransferToElasticaDocument($searchDocumentTransfer);
        $index = $this->client->getIndex($document->getIndex());
        $index->addDocuments([$document]);
        $response = $index->refresh();

        return $response->isOk();
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer[] $searchDocumentTransfers
     *
     * @return bool
     */
    public function writeDocuments(array $searchDocumentTransfers): bool
    {
        $documents = $this->mapSearchDocumentTransfersToElasticaDocuments($searchDocumentTransfers);
        $this->client->addDocuments($documents);
        $response = $this->client->refreshAll();

        return $response->isOk();
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer[] $searchDocumentTransfers
     *
     * @return \Elastica\Document[]
     */
    protected function mapSearchDocumentTransfersToElasticaDocuments(array $searchDocumentTransfers): array
    {
        $documents = [];

        foreach ($searchDocumentTransfers as $searchDocumentTransfer) {
            $documents[] = $this->mapSearchDocumentTransferToElasticaDocument($searchDocumentTransfer);
        }

        return $documents;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return \Elastica\Document
     */
    protected function mapSearchDocumentTransferToElasticaDocument(SearchDocumentTransfer $searchDocumentTransfer): Document
    {
        $indexName = $this->getIndexNameFromSearchDocumentTransfer($searchDocumentTransfer);
        $document = new Document();
        $document->setId($searchDocumentTransfer->getId())
            ->setData($searchDocumentTransfer->getData())
            ->setType($this->config->getDefaultMappingType())
            ->setIndex($indexName);

        return $document;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return bool
     */
    public function deleteDocument(SearchDocumentTransfer $searchDocumentTransfer): bool
    {
        $indexName = $this->getIndexNameFromSearchDocumentTransfer($searchDocumentTransfer);
        $index = $this->client->getIndex($indexName);
        $document = $this->getDocumentFromIndex($searchDocumentTransfer->getId(), $index);

        $index->deleteDocuments([$document]);
        $response = $index->flush();

        return $response->isOk();
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer[] $searchDocumentTransfers
     *
     * @return bool
     */
    public function deleteDocuments(array $searchDocumentTransfers): bool
    {
        $documents = $this->mapSearchDocumentTransfersToElasticaDocuments($searchDocumentTransfers);
        $this->client->deleteDocuments($documents);
        $response = $this->client->refreshAll();

        return $response->isOk();
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return string
     */
    protected function getIndexNameFromSearchDocumentTransfer(SearchDocumentTransfer $searchDocumentTransfer): string
    {
        $this->validateSearchDocumentTransferHasIndexName($searchDocumentTransfer);

        return $searchDocumentTransfer->getSearchContext()->getElasticsearchContext()->getIndexName();
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return void
     */
    protected function validateSearchDocumentTransferHasIndexName(SearchDocumentTransfer $searchDocumentTransfer): void
    {
        $searchDocumentTransfer->requireSearchContext()
            ->getSearchContext()
            ->requireElasticsearchContext()
            ->getElasticsearchContext();
    }

    /**
     * @param string $documentId
     * @param \Elastica\Index $index
     *
     * @return \Elastica\Document
     */
    protected function getDocumentFromIndex(string $documentId, Index $index): Document
    {
        return $index->getType($this->config->getDefaultMappingType())->getDocument($documentId);
    }
}
