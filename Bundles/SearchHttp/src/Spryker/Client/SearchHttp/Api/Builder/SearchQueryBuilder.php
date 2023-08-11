<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Api\Builder;

use Generated\Shared\Transfer\SearchQueryRangeFacetFilterTransfer;
use Generated\Shared\Transfer\SearchQueryTransfer;
use Generated\Shared\Transfer\SearchQueryValueFacetFilterTransfer;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStoreClientInterface;

class SearchQueryBuilder implements SearchQueryBuilderInterface
{
    /**
     * @var string
     */
    protected const FACET_TYPE_RANGE = 'range';

    /**
     * @var string
     */
    protected const FACET_TYPE_VALUES = 'values';

    /**
     * @var \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStoreClientInterface
     */
    protected SearchHttpToStoreClientInterface $storeClient;

    /**
     * @param \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStoreClientInterface $storeClient
     */
    public function __construct(SearchHttpToStoreClientInterface $storeClient)
    {
        $this->storeClient = $storeClient;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     *
     * @return array<string, mixed>
     */
    public function build(QueryInterface $searchQuery): array
    {
        /** @var \Generated\Shared\Transfer\SearchQueryTransfer $searchQueryTransfer */
        $searchQueryTransfer = $searchQuery->getSearchQuery();

        $query = $this->addStoreName([]);
        $query = $this->addQueryString($query, $searchQueryTransfer);
        $query = $this->addFacets($query, $searchQueryTransfer);
        $query = $this->addSorting($query, $searchQueryTransfer);

        return $this->addPagination($query, $searchQueryTransfer);
    }

    /**
     * @param array<string, mixed> $query
     *
     * @return array<string, mixed>
     */
    protected function addStoreName(array $query): array
    {
        $query['store'] = $this->storeClient->getCurrentStore()->getName();

        return $query;
    }

    /**
     * @param array<string, mixed> $query
     * @param \Generated\Shared\Transfer\SearchQueryTransfer $searchQueryTransfer
     *
     * @return array<string, mixed>
     */
    protected function addQueryString(array $query, SearchQueryTransfer $searchQueryTransfer): array
    {
        if ($searchQueryTransfer->getQueryString()) {
            $query['query'] = $searchQueryTransfer->getQueryString();
        }

        return $query;
    }

    /**
     * @param array<string, mixed> $query
     * @param \Generated\Shared\Transfer\SearchQueryTransfer $searchQueryTransfer
     *
     * @return array<string, mixed>
     */
    protected function addFacets(array $query, SearchQueryTransfer $searchQueryTransfer): array
    {
        if ($searchQueryTransfer->getSearchQueryFacetFilters()) {
            foreach ($searchQueryTransfer->getSearchQueryFacetFilters() as $searchQueryFacetFilter) {
                $query = $this->addFacet($query, $searchQueryFacetFilter);
            }
        }

        return $query;
    }

    /**
     * @param array<string, mixed> $query
     * @param object $searchQueryFacetFilter
     *
     * @return array<string, mixed>
     */
    protected function addFacet(array $query, object $searchQueryFacetFilter)
    {
        return match (true) {
            $searchQueryFacetFilter instanceof SearchQueryRangeFacetFilterTransfer => $this->addRangeFacet($query, $searchQueryFacetFilter),
            $searchQueryFacetFilter instanceof SearchQueryValueFacetFilterTransfer => $this->addValueFacet($query, $searchQueryFacetFilter),
            default => $query,
        };
    }

    /**
     * @param array<string, mixed> $query
     * @param \Generated\Shared\Transfer\SearchQueryRangeFacetFilterTransfer $searchQueryFacetFilter
     *
     * @return array<string, mixed>
     */
    protected function addRangeFacet(array $query, SearchQueryRangeFacetFilterTransfer $searchQueryFacetFilter): array
    {
        $query['facets'][$searchQueryFacetFilter->getFieldName()] = [
            'type' => static::FACET_TYPE_RANGE,
            'values' => [
                'from' => $searchQueryFacetFilter->getFrom(),
                'to' => $searchQueryFacetFilter->getTo(),
            ],
        ];

        return $query;
    }

    /**
     * @param array<string, mixed> $query
     * @param \Generated\Shared\Transfer\SearchQueryValueFacetFilterTransfer $searchQueryFacetFilter
     *
     * @return array<string, mixed>
     */
    protected function addValueFacet(array $query, SearchQueryValueFacetFilterTransfer $searchQueryFacetFilter)
    {
        $query['facets'][$searchQueryFacetFilter->getFieldName()] = [
            'type' => static::FACET_TYPE_VALUES,
            'values' => $searchQueryFacetFilter->getValues(),
        ];

        return $query;
    }

    /**
     * @param array<string, mixed> $query
     * @param \Generated\Shared\Transfer\SearchQueryTransfer $searchQueryTransfer
     *
     * @return array<string, mixed>
     */
    protected function addSorting(array $query, SearchQueryTransfer $searchQueryTransfer): array
    {
        if ($searchQueryTransfer->getSort()) {
            $query['sorting'] = [
                'field' => $searchQueryTransfer->getSort()->getFieldName(),
                'direction' => $searchQueryTransfer->getSort()->getSortDirection(),
            ];
        }

        return $query;
    }

    /**
     * @param array<string, mixed> $query
     * @param \Generated\Shared\Transfer\SearchQueryTransfer $searchQueryTransfer
     *
     * @return array<string, mixed>
     */
    protected function addPagination(array $query, SearchQueryTransfer $searchQueryTransfer): array
    {
        if ($searchQueryTransfer->getPagination()) {
            $query['pagination'] = [
                'page' => $searchQueryTransfer->getPagination()->getPage(),
                'hitsPerPage' => $searchQueryTransfer->getPagination()->getItemsPerPage(),
            ];
        }

        return $query;
    }
}
