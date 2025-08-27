<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Plugin\Search;

use Generated\Shared\Transfer\SearchConnectionResponseTransfer;
use Generated\Shared\Transfer\SearchContextTransfer;
use Generated\Shared\Transfer\SearchDocumentTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\ConnectionCheckerAdapterPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchAdapterPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchTypeIdentifierInterface;
use Spryker\Shared\SearchHttp\SearchHttpConfig;

/**
 * @method \Spryker\Client\SearchHttp\SearchHttpClientInterface getClient()
 * @method \Spryker\Client\SearchHttp\SearchHttpFactory getFactory()
 */
class SearchHttpSearchAdapterPlugin extends AbstractPlugin implements SearchAdapterPluginInterface, ConnectionCheckerAdapterPluginInterface
{
    /**
     * @var string
     */
    protected const NAME = 'search_http';

    /**
     * {@inheritDoc}
     * - Currently not supported by the plugin.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\SearchConnectionResponseTransfer
     */
    public function checkConnection(): SearchConnectionResponseTransfer
    {
        return (new SearchConnectionResponseTransfer())->setIsSuccessfull(true);
    }

    /**
     * {@inheritDoc}
     * - Performs search in ACP search app via HTTP API.
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface> $resultFormatters
     * @param array<string, mixed> $requestParameters
     *
     * @return \Elastica\ResultSet|array<string, mixed>
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = [])
    {
        if (
            $searchQuery instanceof SearchTypeIdentifierInterface &&
            in_array($searchQuery->getSearchType(), [SearchHttpConfig::TYPE_SUGGESTION_SEARCH_HTTP, SearchHttpConfig::TYPE_PRODUCT_CONCRETE_SEARCH_HTTP])
        ) {
            return $this->getClient()->suggestSearch($searchQuery, $resultFormatters, $requestParameters);
        }

        return $this->getClient()->search($searchQuery, $resultFormatters, $requestParameters);
    }

    /**
     * {@inheritDoc}
     * - Currently not supported by the plugin.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return \Generated\Shared\Transfer\SearchDocumentTransfer
     */
    public function readDocument(SearchDocumentTransfer $searchDocumentTransfer): SearchDocumentTransfer
    {
        return new SearchDocumentTransfer();
    }

    /**
     * {@inheritDoc}
     * - Currently not supported by the plugin.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return bool
     */
    public function deleteDocument(SearchDocumentTransfer $searchDocumentTransfer): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     * - Currently not supported by the plugin.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\SearchDocumentTransfer> $searchDocumentTransfers
     *
     * @return bool
     */
    public function deleteDocuments(array $searchDocumentTransfers): bool
    {
        return true;
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
        return $searchContextTransfer->getSearchHttpContext() &&
            $searchContextTransfer->getSearchHttpContext()->getIsApplicable();
    }

    /**
     * {@inheritDoc}
     * - Currently not supported by the plugin.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return bool
     */
    public function writeDocument(SearchDocumentTransfer $searchDocumentTransfer): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     * - Currently not supported by the plugin.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\SearchDocumentTransfer> $searchDocumentTransfers
     *
     * @return bool
     */
    public function writeDocuments(array $searchDocumentTransfers): bool
    {
        return true;
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
