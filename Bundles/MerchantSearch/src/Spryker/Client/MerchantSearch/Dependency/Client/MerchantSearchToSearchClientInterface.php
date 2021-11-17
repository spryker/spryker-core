<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantSearch\Dependency\Client;

use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

interface MerchantSearchToSearchClientInterface
{
    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface> $resultFormatters
     * @param array<mixed> $requestParameters
     *
     * @return \Elastica\ResultSet|mixed|array Deprecated: Only `mixed` will be supported with the next major.
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = []);

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array<\Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface> $searchQueryExpanders
     * @param array<mixed> $requestParameters
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $searchQueryExpanders, array $requestParameters = []);
}
