<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query;

use Generated\Shared\Transfer\SortConfigTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\ServicePointSearch\Builder\SortConfigBuilderInterface;

/**
 * @method \Spryker\Client\ServicePointSearch\ServicePointSearchConfig getConfig()
 * @method \Spryker\Client\ServicePointSearch\ServicePointSearchFactory getFactory()
 */
class SortedServicePointSearchQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Allows sorting of the service point search result by sorting configuration.
     * - Checks whether sorting is by search result score and if so, no further sorting is applied.
     * - Checks if sorting via request parameter `sort` is valid and if so, it is applied.
     * - Checks if sorting can been applied and if not, the default sorting defined by configuration is applied.
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
        if ($this->hasSortingByScore($searchQuery)) {
            return $searchQuery;
        }

        return $this->addSorting($searchQuery, $requestParameters);
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     *
     * @return bool
     */
    protected function hasSortingByScore(QueryInterface $searchQuery): bool
    {
        return (bool)$searchQuery->getSearchString();
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array<string, mixed> $requestParameters
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    protected function addSorting(QueryInterface $searchQuery, array $requestParameters): QueryInterface
    {
        $servicePointSearchSortConfigBuilder = $this->getFactory()->createServicePointSearchSortConfigBuilder();
        $sortConfigTransfer = $this->getSortConfigTransfer($servicePointSearchSortConfigBuilder, $requestParameters);
        $sortField = sprintf('%s.%s', $sortConfigTransfer->getFieldName(), $sortConfigTransfer->getName());
        $sortDirection = $servicePointSearchSortConfigBuilder->getSortDirection($sortConfigTransfer->getParameterNameOrFail());

        $searchQuery->getSearchQuery()->addSort([
            $sortField => [
                'order' => $sortDirection,
            ],
        ]);

        return $searchQuery;
    }

    /**
     * @param \Spryker\Client\ServicePointSearch\Builder\SortConfigBuilderInterface $servicePointSearchSortConfigBuilder
     * @param array<string, mixed> $requestParameters
     *
     * @return \Generated\Shared\Transfer\SortConfigTransfer
     */
    protected function getSortConfigTransfer(
        SortConfigBuilderInterface $servicePointSearchSortConfigBuilder,
        array $requestParameters
    ): SortConfigTransfer {
        $sortParameter = $servicePointSearchSortConfigBuilder->getActiveParamName($requestParameters);
        $sortConfigTransfer = $servicePointSearchSortConfigBuilder->getSortConfigTransfer($sortParameter);

        if ($sortConfigTransfer) {
            return $sortConfigTransfer;
        }

        return $this->getFactory()->getConfig()->getDefaultSortConfigTransfer();
    }
}
