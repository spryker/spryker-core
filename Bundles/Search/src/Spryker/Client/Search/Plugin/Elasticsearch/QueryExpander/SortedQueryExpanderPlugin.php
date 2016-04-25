<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Model\Query\QueryInterface;
use Spryker\Client\Search\Plugin\Config\SearchConfigInterface;
use Spryker\Client\Search\Plugin\Config\SortConfigBuilderInterface;
use Spryker\Client\Search\Plugin\QueryExpanderPluginInterface;

/**
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class SortedQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
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
        $sortConfig = $searchConfig->getSortConfigBuilder();
        $this->addSortingToQuery($searchQuery->getSearchQuery(), $sortConfig, $requestParameters);

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     * @param \Spryker\Client\Search\Plugin\Config\SortConfigBuilderInterface $sortConfig
     * @param array $requestParameters
     *
     * @return void
     */
    protected function addSortingToQuery(Query $query, SortConfigBuilderInterface $sortConfig, array $requestParameters)
    {
        $sortParamName = $sortConfig->getActiveParamName($requestParameters);
        $sortConfigTransfer = $sortConfig->get($sortParamName);

        if ($sortConfigTransfer === null) {
            return;
        }

        $nestedSortField = $sortConfigTransfer->getFieldName() . '.' . $sortConfigTransfer->getParameterName();
        $query->setSort(
            [
                $nestedSortField => [
                    'order' => $sortConfig->getActiveSortDirection($requestParameters),
                    'mode' => 'min',
                ],
            ]
        );
    }

}
