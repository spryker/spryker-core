<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearchGui\Dependency\Client;

use Generated\Shared\Transfer\SearchDocumentTransfer;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

class SearchElasticsearchGuiToSearchElasticsearchClientBridge implements SearchElasticsearchGuiToSearchElasticsearchClientInterface
{
    /**
     * @var \Spryker\Client\SearchElasticsearch\SearchElasticsearchClientInterface
     */
    protected $searchElasticsearchClient;

    /**
     * @param \Spryker\Client\SearchElasticsearch\SearchElasticsearchClientInterface $searchElasticsearchClient
     */
    public function __construct($searchElasticsearchClient)
    {
        $this->searchElasticsearchClient = $searchElasticsearchClient;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface[] $resultFormatters
     * @param array $requestParameters
     *
     * @return array|\Elastica\ResultSet
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = [])
    {
        return $this->searchElasticsearchClient->search($searchQuery, $resultFormatters, $requestParameters);
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return \Generated\Shared\Transfer\SearchDocumentTransfer
     */
    public function readDocument(SearchDocumentTransfer $searchDocumentTransfer): SearchDocumentTransfer
    {
        return $this->searchElasticsearchClient->readDocument($searchDocumentTransfer);
    }
}
