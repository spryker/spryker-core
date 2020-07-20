<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Writer;

use Elastica\Client;
use Elastica\Document;
use Generated\Shared\Transfer\SearchDocumentTransfer;

class MappingTypeAwareDocumentWriter implements DocumentWriterInterface
{
    /**
     * @var \Elastica\Client
     */
    protected $elasticaClient;

    /**
     * @param \Elastica\Client $client
     */
    public function __construct(Client $client)
    {
        $this->elasticaClient = $client;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return bool
     */
    public function writeDocument(SearchDocumentTransfer $searchDocumentTransfer): bool
    {
        $document = $this->createElasticaDocument($searchDocumentTransfer);
        $index = $this->elasticaClient->getIndex($document->getIndex());
        $response = $index->getType($document->getType())->addDocument($document);
        $index->refresh();

        return $response->isOk();
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer[] $searchDocumentTransfers
     *
     * @return bool
     */
    public function writeDocuments(array $searchDocumentTransfers): bool
    {
        $documents = $this->createElasticaDocuments($searchDocumentTransfers);
        $this->elasticaClient->addDocuments($documents);
        $response = $this->elasticaClient->refreshAll();

        return $response->isOk();
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer[] $searchDocumentTransfers
     *
     * @return \Elastica\Document[]
     */
    protected function createElasticaDocuments(array $searchDocumentTransfers): array
    {
        $documents = [];

        foreach ($searchDocumentTransfers as $searchDocumentTransfer) {
            $documents[] = $this->createElasticaDocument($searchDocumentTransfer);
        }

        return $documents;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return bool
     */
    public function deleteDocument(SearchDocumentTransfer $searchDocumentTransfer): bool
    {
        $indexName = $this->getIndexName($searchDocumentTransfer);
        $typeName = $this->getTypeName($searchDocumentTransfer);
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
        $documents = $this->createElasticaDocuments($searchDocumentTransfers);
        $this->elasticaClient->deleteDocuments($documents);
        $response = $this->elasticaClient->refreshAll();

        return $response->isOk();
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return string
     */
    protected function getIndexName(SearchDocumentTransfer $searchDocumentTransfer): string
    {
        return $searchDocumentTransfer->requireSearchContext()
            ->getSearchContext()
            ->requireElasticsearchContext()
            ->getElasticsearchContext()
            ->requireIndexName()
            ->getIndexName();
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return string
     */
    protected function getTypeName(SearchDocumentTransfer $searchDocumentTransfer): string
    {
        return $searchDocumentTransfer->requireSearchContext()
            ->getSearchContext()
            ->requireElasticsearchContext()
            ->getElasticsearchContext()
            ->requireTypeName()
            ->getTypeName();
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return \Elastica\Document
     */
    protected function createElasticaDocument(SearchDocumentTransfer $searchDocumentTransfer): Document
    {
        return new Document(
            $searchDocumentTransfer->getId(),
            $searchDocumentTransfer->getData(),
            $typeName = $this->getTypeName($searchDocumentTransfer),
            $indexName = $this->getIndexName($searchDocumentTransfer)
        );
    }
}
