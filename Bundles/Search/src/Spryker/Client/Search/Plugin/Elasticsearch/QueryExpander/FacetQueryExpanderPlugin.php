<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Model\Query\QueryInterface;
use Spryker\Client\Search\Plugin\Config\FacetConfigBuilderInterface;
use Spryker\Client\Search\Plugin\Config\SearchConfigInterface;
use Spryker\Client\Search\Plugin\QueryExpanderPluginInterface;

/**
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class FacetQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
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
        $facetConfig = $searchConfig->getFacetConfigBuilder();
        $query = $searchQuery->getSearchQuery();

        $facetFilters = $this->getFacetFilters($facetConfig, $requestParameters);

        $this->addFacetAggregationToQuery($query, $facetConfig, $facetFilters);
        $this->addFacetFiltersToQuery($query, $facetFilters);

        return $searchQuery;
    }

    /**
     * @param \Spryker\Client\Search\Plugin\Config\FacetConfigBuilderInterface $facetConfig
     * @param array $requestParameters
     *
     * @return \Elastica\Query\AbstractQuery[]
     */
    protected function getFacetFilters(FacetConfigBuilderInterface $facetConfig, array $requestParameters = [])
    {
        $facetFilters = [];
        $activeFacetConfigTransfers = $facetConfig->getActive($requestParameters);

        foreach ($activeFacetConfigTransfers as $facetConfigTransfer) {
            $query = $this->createFacetFilterQuery($facetConfigTransfer, $requestParameters);
            if ($query !== null) {
                $facetFilters[$facetConfigTransfer->getName()] = $query;
            }
        }

        return $facetFilters;
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param array $requestParameters
     *
     * @return \Elastica\Query\AbstractQuery|null
     */
    protected function createFacetFilterQuery(FacetConfigTransfer $facetConfigTransfer, array $requestParameters)
    {
        $filterValue = isset($requestParameters[$facetConfigTransfer->getParameterName()]) ? $requestParameters[$facetConfigTransfer->getParameterName()] : null;

        if (trim($filterValue) === '') {
            return null;
        }

        $query = $this
            ->getFactory()
            ->createNestedQueryFactory()
            ->create($facetConfigTransfer, $filterValue);

        return $query;
    }

    /**
     * @param \Elastica\Query $query
     * @param \Spryker\Client\Search\Plugin\Config\FacetConfigBuilderInterface $facetConfig
     * @param \Elastica\Query\AbstractQuery[] $facetFilters
     *
     * @return void
     */
    protected function addFacetAggregationToQuery(Query $query, FacetConfigBuilderInterface $facetConfig, array $facetFilters)
    {
        $boolQuery = $query->getQuery();
        if (!$boolQuery instanceof BoolQuery) {
            throw new \InvalidArgumentException(sprintf('Facet filters available only with %s, got: %s', BoolQuery::class, get_class($boolQuery)));
        }

        foreach ($facetConfig->getAll() as $facetConfigTransfer) {
            $aggregationFilterQuery = clone $boolQuery;

            // TODO: use this for mixed aggregation filtering or remove it if not needed.
            // TODO: We need one aggregation for facet fields whithout any magic to get the filtered facet numbers,
            // then we need to create one global aggregation per filtered item without the current item's filtering criteria,
            // then we need to merge these results in php level.
//            $this->setAggregationFilters($facetConfigTransfer, $aggregationFilterQuery, $facetFilters);

            $facetAggregation = $this
                ->getFactory()
                ->createFacetAggregationFactory()
                ->create($facetConfigTransfer, $aggregationFilterQuery)
                ->createAggregation();

            $query->addAggregation($facetAggregation);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param \Elastica\Query\BoolQuery $aggregationFilterQuery
     * @param \Elastica\Query\AbstractQuery[] $facetFilters
     *
     * @return void
     */
    protected function setAggregationFilters(FacetConfigTransfer $facetConfigTransfer, BoolQuery $aggregationFilterQuery, array $facetFilters)
    {
        // add filters for facet aggregation which is not related to the current facet
        foreach ($facetFilters as $name => $query) {
            if ($name !== $facetConfigTransfer->getName()) {
                $aggregationFilterQuery->addFilter($query);
            }
        }
    }

    /**
     * @param \Elastica\Query $query
     * @param \Elastica\Query\AbstractQuery[] $facetFilters
     *
     * @return void
     */
    protected function addFacetFiltersToQuery(Query $query, array $facetFilters)
    {
        $boolQuery = $query->getQuery();
        if (!$boolQuery instanceof BoolQuery) {
            throw new \InvalidArgumentException(sprintf('Facet filters available only with %s, got: %s', BoolQuery::class, get_class($boolQuery)));
        }

        foreach ($facetFilters as $facetFilter) {
            $boolQuery->addFilter($facetFilter);
        }
    }

}
