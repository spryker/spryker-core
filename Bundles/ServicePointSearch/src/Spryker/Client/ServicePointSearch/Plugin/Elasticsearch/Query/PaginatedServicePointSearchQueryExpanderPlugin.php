<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query;

use Elastica\Query;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

/**
 * @method \Spryker\Client\ServicePointSearch\ServicePointSearchFactory getFactory()
 */
class PaginatedServicePointSearchQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * @var string
     */
    protected const PARAMETER_OFFSET = 'offset';

    /**
     * @var string
     */
    protected const PARAMETER_LIMIT = 'limit';

    /**
     * {@inheritDoc}
     * - Allows to fetch service point results by page.
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array<string, mixed> $requestParameters
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = []): QueryInterface
    {
        $this->addPaginationToQuery($searchQuery->getSearchQuery(), $requestParameters);

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     * @param array<string, mixed> $requestParameters
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

        $servicePointSearchPaginationConfigBuilder = $this->getFactory()->createServicePointSearchPaginationConfigBuilder();
        $currentPage = $servicePointSearchPaginationConfigBuilder->getCurrentPage($requestParameters);
        $itemsPerPage = $servicePointSearchPaginationConfigBuilder->getCurrentItemsPerPage($requestParameters);

        $query->setFrom(($currentPage - 1) * $itemsPerPage);
        $query->setSize($itemsPerPage);
    }
}
