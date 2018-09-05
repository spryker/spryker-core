<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsPageSearch\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query;
use Spryker\Client\CmsPageSearch\Config\SortConfigBuilderInterface;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;

/**
 * @method \Spryker\Client\CmsPageSearch\CmsPageSearchFactory getFactory()
 */
class SortedCmsPageQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = []): QueryInterface
    {
        $sortConfig = $this->getFactory()->getCmsPageSortConfig();
        $this->addSortingToQuery($searchQuery->getSearchQuery(), $sortConfig, $requestParameters);

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     * @param \Spryker\Client\CmsPageSearch\Config\SortConfigBuilderInterface $sortConfig
     * @param array $requestParameters
     *
     * @return void
     */
    protected function addSortingToQuery(Query $query, SortConfigBuilderInterface $sortConfig, array $requestParameters): void
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
