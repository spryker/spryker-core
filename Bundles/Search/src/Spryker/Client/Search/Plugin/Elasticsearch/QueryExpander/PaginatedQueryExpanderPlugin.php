<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Model\Query\QueryInterface;
use Spryker\Client\Search\Plugin\QueryExpanderPluginInterface;

/**
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class PaginatedQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{

    /**
     * @param \Spryker\Client\Search\Model\Query\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\Search\Model\Query\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = [])
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
    protected function addPaginationToQuery(Query $query, array $requestParameters)
    {
        $currentPage = $this->getCurrentPage($requestParameters);
        $itemsPerPage = $this->getItemsPerPage($requestParameters);

        $query->setFrom(($currentPage - 1) * $itemsPerPage);
        $query->setSize($itemsPerPage);
    }

    /**
     * @param array $requestParameters
     *
     * @return int
     * TODO: add constants
     * TODO: move these methods outside somehow
     */
    protected function getCurrentPage(array $requestParameters)
    {
        return isset($requestParameters['page']) ? max((int)$requestParameters['page'], 1) : 1;
    }

    /**
     * @param array $requestParameters
     *
     * @return int
     */
    protected function getItemsPerPage(array $requestParameters)
    {
        return isset($requestParameters['ipp']) ? max((int)$requestParameters['ipp'], 10) : 10;
    }

}
