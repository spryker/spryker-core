<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp;

use Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

/**
 * @method \Spryker\Client\SearchHttp\SearchHttpFactory getFactory()
 */
class SearchHttpClient extends AbstractClient implements SearchHttpClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface> $resultFormatters
     * @param array<string, mixed> $requestParameters
     *
     * @return array<string, mixed>
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = []): array
    {
        return $this->getFactory()->createSearchApiClient()->search($searchQuery, $resultFormatters, $requestParameters);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface> $resultFormatters
     * @param array<string, mixed> $requestParameters
     *
     * @return array<string, mixed>
     */
    public function suggestSearch(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = []): array
    {
        return $this->getFactory()->createSearchApiClient()->suggestSearch($searchQuery, $resultFormatters, $requestParameters);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer $searchResult
     *
     * @return array<int, \Generated\Shared\Transfer\ProductConcretePageSearchTransfer>
     */
    public function formatProductConcreteCatalogHttpSearchResult(SuggestionsSearchHttpResponseTransfer $searchResult): array
    {
        return $this->getFactory()
            ->createProductConcreteCatalogSearchHttpResultFormatter()
            ->formatResult($searchResult);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $searchResult
     *
     * @return int|null
     */
    public function findSearchResultTotalCount($searchResult): ?int
    {
        return $this->getFactory()
            ->createSearchResultCountProvider()
            ->findSearchResultTotalCount($searchResult);
    }
}
