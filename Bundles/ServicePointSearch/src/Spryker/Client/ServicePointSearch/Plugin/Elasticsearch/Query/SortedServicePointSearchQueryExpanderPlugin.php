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
class SortedServicePointSearchQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds possibility to sort service point search result. Options are provided by service point sort config builder.
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
        $this->addSortingToQuery($searchQuery->getSearchQuery(), $requestParameters);

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     * @param array<string, mixed> $requestParameters
     *
     * @return void
     */
    protected function addSortingToQuery(Query $query, array $requestParameters): void
    {
        $sortConfig = $this->getFactory()->createServicePointSearchSortConfigBuilder();

        $sortParamName = $sortConfig->getActiveParamName($requestParameters);
        $sortConfigTransfer = $sortConfig->getSortConfigTransfer($sortParamName);

        if (!$sortConfigTransfer) {
            return;
        }

        $nestedSortField = $sortConfigTransfer->getFieldName() . '.' . $sortConfigTransfer->getName();
        $query->addSort(
            [
                $nestedSortField => [
                    'order' => $sortConfig->getSortDirection($sortParamName),
                ],
            ],
        );
    }
}
