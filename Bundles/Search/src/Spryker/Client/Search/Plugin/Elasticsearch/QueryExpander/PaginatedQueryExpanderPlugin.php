<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Model\Query\QueryInterface;
use Spryker\Client\Search\Plugin\Config\PaginationConfigBuilderInterface;
use Spryker\Client\Search\Plugin\Config\SearchConfigInterface;
use Spryker\Client\Search\Plugin\QueryExpanderPluginInterface;

/**
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class PaginatedQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{

    /**
     * @param \Spryker\Client\Search\Model\Query\QueryInterface $searchQuery
     * @param \Spryker\Client\Search\Plugin\Config\SearchConfigInterface $searchConfig
     * @param array $requestParameters
     *
     * @return \Spryker\Client\Search\Model\Query\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, SearchConfigInterface $searchConfig, array $requestParameters = [])
    {
        $paginationConfig = $searchConfig->getPaginationConfigBuilder();
        $this->addPaginationToQuery($searchQuery->getSearchQuery(), $paginationConfig, $requestParameters);

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     * @param \Spryker\Client\Search\Plugin\Config\PaginationConfigBuilderInterface $paginationConfig
     * @param array $requestParameters
     *
     * @return void
     */
    protected function addPaginationToQuery(Query $query, PaginationConfigBuilderInterface $paginationConfig, array $requestParameters)
    {
        $currentPage = $paginationConfig->getCurrentPage($requestParameters);
        $itemsPerPage = $paginationConfig->get()->getItemsPerPage();

        $query->setFrom(($currentPage - 1) * $itemsPerPage);
        $query->setSize($itemsPerPage);
    }

}
