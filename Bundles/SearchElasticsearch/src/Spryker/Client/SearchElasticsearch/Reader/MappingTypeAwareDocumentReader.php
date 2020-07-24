<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Reader;

use Elastica\Client;
use Elastica\Document;
use Elastica\Index;
use Generated\Shared\Transfer\SearchDocumentTransfer;

/**
 * @deprecated Will be removed once the support of Elasticsearch 6 and lower is dropped.
 */
class MappingTypeAwareDocumentReader implements DocumentReaderInterface
{
    /**
     * @var \Elastica\Client
     */
    protected $elasticaClient;

    /**
     * @param \Elastica\Client $elasticaClient
     */
    public function __construct(Client $elasticaClient)
    {
        $this->elasticaClient = $elasticaClient;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return \Generated\Shared\Transfer\SearchDocumentTransfer
     */
    public function readDocument(SearchDocumentTransfer $searchDocumentTransfer): SearchDocumentTransfer
    {
        $indexName = $this->getIndexName($searchDocumentTransfer);
        $elasticaIndex = $this->elasticaClient->getIndex($indexName);
        $typeName = $this->getTypeName($searchDocumentTransfer, $elasticaIndex);

        $elasticaDocument = $elasticaIndex->getType($typeName)->getDocument($searchDocumentTransfer->getId());

        return $this->mapElasticaDocumentToSearchDocumentTransfer($elasticaDocument, $searchDocumentTransfer);
    }

    /**
     * @param \Elastica\Document $document
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return \Generated\Shared\Transfer\SearchDocumentTransfer
     */
    protected function mapElasticaDocumentToSearchDocumentTransfer(Document $document, SearchDocumentTransfer $searchDocumentTransfer): SearchDocumentTransfer
    {
        return $searchDocumentTransfer
            ->setId($document->getId())
            ->setData($document->getData());
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
     * @deprecated Will be removed after the final transition to the typeless Elasticsearch version.
     *
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     * @param \Elastica\Index $elasticaIndex
     *
     * @return string
     */
    protected function getTypeName(SearchDocumentTransfer $searchDocumentTransfer, Index $elasticaIndex): string
    {
        return $searchDocumentTransfer->requireSearchContext()
            ->getSearchContext()
            ->requireElasticsearchContext()
            ->getElasticsearchContext()
            ->getTypeName() ?? $this->readMappingTypeNameFromElasticsearch($elasticaIndex);
    }

    /**
     * @deprecated Will be removed after the migration to Elasticsearch 7.
     *
     * @param \Elastica\Index $elasticaIndex
     *
     * @return string
     */
    protected function readMappingTypeNameFromElasticsearch(Index $elasticaIndex): string
    {
        return key($elasticaIndex->getMapping());
    }
}
