<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp;

use Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

interface SearchHttpClientInterface
{
    /**
     * Specification:
     * - Runs the search query based on the search configuration provided by this client in the search query.
     * - Search query contains current store.
     * - The formatted search result will be an associative array
     * - The keys of array will be the names of the current formatters
     * - The values of array will be the results formatter by result formatters
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface> $resultFormatters
     * @param array<string, mixed> $requestParameters
     *
     * @return array<string, mixed>
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = []): array;

    /**
     * Specification:
     * - Runs the suggestion search query based on the search configuration provided by this client in the search query.
     * - The formatted search result will be an associative array
     * - The keys of array will be the names of the current formatters
     * - The values of array will be the results formatter by result formatters
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface> $resultFormatters
     * @param array<string, mixed> $requestParameters
     *
     * @return array<string, mixed>
     */
    public function suggestSearch(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = []): array;

    /**
     * Specification:
     * - Formats SearchHttp Concrete Products.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer $searchResult
     *
     * @return array<int, \Generated\Shared\Transfer\ProductConcretePageSearchTransfer>
     */
    public function formatProductConcreteCatalogHttpSearchResult(SuggestionsSearchHttpResponseTransfer $searchResult): array;

    /**
     * Specification:
     * - Validates whether the given search results have the format of an array.
     * - Returns the total count of search results if such a key exists within the given data.
     * - Returns NULL if any of the conditions stated above is false.
     *
     * @api
     *
     * @param mixed $searchResult
     *
     * @return int|null
     */
    public function findSearchResultTotalCount($searchResult): ?int;
}
