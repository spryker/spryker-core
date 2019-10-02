<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;
use Spryker\Client\Search\Dependency\Plugin\SortConfigBuilderInterface;

/**
 * @deprecated Use `\Spryker\Client\SearchElasticsearch\Plugin\QueryExpander\SortedQueryExpanderPlugin` instead.
 *
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class SortedQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = [])
    {
        $searchConfig = $this->getFactory()->getSearchConfig();
        $sortConfig = $searchConfig->getSortConfigBuilder();
        $this->addSortingToQuery($searchQuery->getSearchQuery(), $sortConfig, $requestParameters);

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     * @param \Spryker\Client\Search\Dependency\Plugin\SortConfigBuilderInterface $sortConfig
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

        $nestedSortField = $sortConfigTransfer->getFieldName() . '.' . $sortConfigTransfer->getName();
        $query->addSort(
            [
                $nestedSortField => [
                    'order' => $sortConfig->getSortDirection($sortParamName),
                    'mode' => 'min',
                ],
            ]
        );
    }
}
