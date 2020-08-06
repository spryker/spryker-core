<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Writer;

use Elastica\Client;
use Elastica\Document;
use Generated\Shared\Transfer\SearchDocumentTransfer;

class DocumentWriter implements DocumentWriterInterface
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
        $index = $this->elasticaClient->getIndex(
            $this->getIndexName($searchDocumentTransfer)
        );
        $document = new Document($searchDocumentTransfer->getId(), $searchDocumentTransfer->getData());
        $response = $index->addDocument($document);
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
        $response = $this->elasticaClient->addDocuments($documents);
        $this->elasticaClient->refreshAll();

        return $response->isOk();
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return bool
     */
    public function deleteDocument(SearchDocumentTransfer $searchDocumentTransfer): bool
    {
        $index = $this->elasticaClient->getIndex(
            $this->getIndexName($searchDocumentTransfer)
        );
        $response = $index->deleteById($searchDocumentTransfer->getId());
        $index->refresh();

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
        $response = $this->elasticaClient->deleteDocuments($documents);
        $this->elasticaClient->refreshAll();

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
            $documents[] = new Document(
                $searchDocumentTransfer->getId(),
                $searchDocumentTransfer->getData(),
                $this->getIndexName($searchDocumentTransfer)
            );
        }

        return $documents;
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
}
