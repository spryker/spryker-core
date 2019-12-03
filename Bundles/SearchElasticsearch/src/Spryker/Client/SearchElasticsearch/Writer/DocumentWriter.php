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
    protected $elasticaClient;

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
        $this->elasticaClient = $client;
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
        $index = $this->elasticaClient->getIndex($document->getIndex());
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
        $this->elasticaClient->addDocuments($documents);
        $response = $this->elasticaClient->refreshAll();

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
        $indexName = $this->extractIndexNameFromSearchDocumentTransfer($searchDocumentTransfer);
        $typeName = $this->extractTypeNameFromSearchDocumentTransfer($searchDocumentTransfer);
        $document = new Document();
        $document->setId($searchDocumentTransfer->getId())
            ->setData($searchDocumentTransfer->getData())
            ->setType($typeName)
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
        $indexName = $this->extractIndexNameFromSearchDocumentTransfer($searchDocumentTransfer);
        $typeName = $this->extractTypeNameFromSearchDocumentTransfer($searchDocumentTransfer);
        $index = $this->elasticaClient->getIndex($indexName);
        $document = $index->getType($typeName)->getDocument($searchDocumentTransfer->getId());

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
        $this->elasticaClient->deleteDocuments($documents);
        $response = $this->elasticaClient->refreshAll();

        return $response->isOk();
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return string
     */
    protected function extractIndexNameFromSearchDocumentTransfer(SearchDocumentTransfer $searchDocumentTransfer): string
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
    }

    /**
     * Source identifier will be used as type name instead of _doc for the sake of compatibility with Elasticsearch 5.
     *
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return string
     */
    protected function extractTypeNameFromSearchDocumentTransfer(SearchDocumentTransfer $searchDocumentTransfer): string
    {
        $this->validateSearchDocumentTransferHasSourceIdentifier($searchDocumentTransfer);

        return $searchDocumentTransfer->getSearchContext()->getSourceIdentifier();
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return void
     */
    protected function validateSearchDocumentTransferHasSourceIdentifier(SearchDocumentTransfer $searchDocumentTransfer): void
    {
        $searchDocumentTransfer->requireSearchContext()
            ->getSearchContext()
            ->requireSourceIdentifier();
    }
}
