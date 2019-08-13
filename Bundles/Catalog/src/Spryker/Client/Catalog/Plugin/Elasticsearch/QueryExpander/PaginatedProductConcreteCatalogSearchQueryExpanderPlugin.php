<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\PaginationConfigBuilderInterface;
use Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;

/**
 * @method \Spryker\Client\Catalog\CatalogFactory getFactory()
 */
class PaginatedProductConcreteCatalogSearchQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = [])
    {
        $paginationConfigBuilder = $this->getFactory()->getPaginationConfigBuilder();
        $this->addPaginationToQuery($searchQuery->getSearchQuery(), $paginationConfigBuilder, $requestParameters);

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     * @param \Spryker\Client\Search\Dependency\Plugin\PaginationConfigBuilderInterface $paginationConfigBuilder
     * @param array $requestParameters
     *
     * @return void
     */
    protected function addPaginationToQuery(
        Query $query,
        PaginationConfigBuilderInterface $paginationConfigBuilder,
        array $requestParameters
    ): void {
        $currentPage = $paginationConfigBuilder->getCurrentPage($requestParameters);
        $itemsPerPage = $paginationConfigBuilder->getCurrentItemsPerPage($requestParameters);

        $query->setFrom(($currentPage - 1) * $itemsPerPage);
        $query->setSize($itemsPerPage);
    }
}
