<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp;

use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

interface SearchHttpClientInterface
{
    /**
     * Specification:
     * - Runs the search query based on the search configuration provided by this client in the search query.
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
}
