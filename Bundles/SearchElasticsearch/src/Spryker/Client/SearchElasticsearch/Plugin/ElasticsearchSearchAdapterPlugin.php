<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Plugin;

use Generated\Shared\Transfer\SearchContextTransfer;
use Generated\Shared\Transfer\SearchDocumentTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchAdapterPluginInterface;

/**
 * @method \Spryker\Client\SearchElasticsearch\SearchElasticsearchClientInterface getClient()
 * @method \Spryker\Client\SearchElasticsearch\SearchElasticsearchFactory getFactory()()
 */
class ElasticsearchSearchAdapterPlugin extends AbstractPlugin implements SearchAdapterPluginInterface
{
    protected const NAME = 'elasticsearch';

    /**
     * {@inheritDoc}
     * - Performs search in Elasticsearch.
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface[] $resultFormatters
     * @param array $requestParameters
     *
     * @return array|\Elastica\ResultSet
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = [])
    {
        return $this->getClient()->search($searchQuery, $resultFormatters, $requestParameters);
    }

    /**
     * {@inheritDoc}
     * - Reads a single document from Elasticsearch.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return \Generated\Shared\Transfer\SearchDocumentTransfer
     */
    public function readDocument(SearchDocumentTransfer $searchDocumentTransfer): SearchDocumentTransfer
    {
        return $this->getClient()->readDocument($searchDocumentTransfer);
    }

    /**
     * {@inheritDoc}
     * - Writes a single document to Elasticsearch.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return bool
     */
    public function writeDocument(SearchDocumentTransfer $searchDocumentTransfer): bool
    {
        return $this->getClient()->writeDocument($searchDocumentTransfer);
    }

    /**
     * {@inheritDoc}
     * - Writes multiple documents to Elasticsearch.
     *
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer[] $searchContextTransfers
     *
     * @return bool
     */
    public function writeDocuments(array $searchContextTransfers): bool
    {
        return $this->getClient()->writeDocuments($searchContextTransfers);
    }

    /**
     * {@inheritDoc}
     * - Deletes a single document from Elasticsearch.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return bool
     */
    public function deleteDocument(SearchDocumentTransfer $searchDocumentTransfer): bool
    {
        return $this->getClient()->deleteDocument($searchDocumentTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer[] $searchDocumentTransfers
     *
     * @return bool
     */
    public function deleteDocuments(array $searchDocumentTransfers): bool
    {
        return $this->getClient()->deleteDocuments($searchDocumentTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return bool
     */
    public function isApplicable(SearchContextTransfer $searchContextTransfer): bool
    {
        return $this->getFactory()->createSourceIdentifierChecker()->isSupported($searchContextTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return static::NAME;
    }
}
