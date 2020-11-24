<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantSearch\Plugin\Elasticsearch\Query;

use Elastica\Query;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

/**
 * @method \Spryker\Client\MerchantSearch\MerchantSearchFactory getFactory()
 */
class PaginatedMerchantSearchQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    protected const PARAMETER_OFFSET = 'offset';
    protected const PARAMETER_LIMIT = 'limit';

    /**
     * {@inheritDoc}
     *  - Allows to fetch merchant results by page.
     *
     * @api
     *
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = []): QueryInterface
    {
        $this->addPaginationToQuery($searchQuery->getSearchQuery(), $requestParameters);

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     * @param array $requestParameters
     *
     * @return void
     */
    protected function addPaginationToQuery(Query $query, array $requestParameters): void
    {
        if (isset($requestParameters[static::PARAMETER_OFFSET]) && isset($requestParameters[static::PARAMETER_LIMIT])) {
            $query->setFrom($requestParameters[static::PARAMETER_OFFSET]);
            $query->setSize($requestParameters[static::PARAMETER_LIMIT]);

            return;
        }

        $merchantSearchPaginationConfigBuilder = $this->getFactory()->createMerchantSearchPaginationConfigBuilder();
        $currentPage = $merchantSearchPaginationConfigBuilder->getCurrentPage($requestParameters);
        $itemsPerPage = $merchantSearchPaginationConfigBuilder->getCurrentItemsPerPage($requestParameters);

        $query->setFrom(($currentPage - 1) * $itemsPerPage);
        $query->setSize($itemsPerPage);
    }
}
