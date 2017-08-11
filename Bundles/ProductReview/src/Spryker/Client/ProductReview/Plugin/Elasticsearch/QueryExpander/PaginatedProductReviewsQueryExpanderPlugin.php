<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\PaginationConfigBuilderInterface;
use Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;

/**
 * @method \Spryker\Client\ProductReview\ProductReviewFactory getFactory()
 */
class PaginatedProductReviewsQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{

    /**
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = [])
    {
        $paginationConfigBuilder = $this->getFactory()->getPaginationConfigBuilder();
        $this->addPaginationToQuery($searchQuery->getSearchQuery(), $paginationConfigBuilder, $requestParameters);

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     * @param \Spryker\Client\Search\Dependency\Plugin\PaginationConfigBuilderInterface $paginationConfig
     * @param array $requestParameters
     *
     * @return void
     */
    protected function addPaginationToQuery(Query $query, PaginationConfigBuilderInterface $paginationConfig, array $requestParameters)
    {
        $currentPage = $paginationConfig->getCurrentPage($requestParameters);
        $itemsPerPage = $paginationConfig->getCurrentItemsPerPage($requestParameters);

        $query->setFrom(($currentPage - 1) * $itemsPerPage);
        $query->setSize($itemsPerPage);
    }

}
