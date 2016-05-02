<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Aggregation\AbstractAggregation;
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
    
    const AGGREGATION_FILTER_NAME = 'filter';
    const AGGREGATION_GLOBAL_PREFIX = 'global-';

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

        $this->addFacetAggregationToQuery($query, $facetConfig, $facetFilters, $requestParameters);
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
            ->createQueryFactory()
            ->create($facetConfigTransfer, $filterValue);

        return $query;
    }

    /**
     * @param \Elastica\Query $query
     * @param \Spryker\Client\Search\Plugin\Config\FacetConfigBuilderInterface $facetConfig
     * @param \Elastica\Query\AbstractQuery[] $facetFilters
     * @param array $requestParameters
     *
     * @return void
     */
    protected function addFacetAggregationToQuery(Query $query, FacetConfigBuilderInterface $facetConfig, array $facetFilters, array $requestParameters)
    {
        $boolQuery = $this->getBoolQuery($query);

        $activeFilters = $facetConfig->getActiveParamNames($requestParameters);

        foreach ($facetConfig->getAll() as $facetConfigTransfer) {
            $facetAggregation = $this
                ->getFactory()
                ->createFacetAggregationFactory()
                ->create($facetConfigTransfer)
                ->createAggregation();

            $query->addAggregation($facetAggregation);

            if (in_array($facetConfigTransfer->getName(), $activeFilters)) {
                $globalAgg = $this->createGlobalAggregation($facetFilters, $facetConfigTransfer, $boolQuery, $facetAggregation);

                $query->addAggregation($globalAgg);
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
        $boolQuery = $this->getBoolQuery($query);

        foreach ($facetFilters as $facetFilter) {
            $boolQuery->addFilter($facetFilter);
        }
    }

    /**
     * @param \Elastica\Query\AbstractQuery[] $facetFilters
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param \Elastica\Query\BoolQuery $boolQuery
     * @param \Elastica\Aggregation\AbstractAggregation $facetAggregation
     *
     * @return \Elastica\Aggregation\AbstractAggregation
     */
    protected function createGlobalAggregation(array $facetFilters, FacetConfigTransfer $facetConfigTransfer, BoolQuery $boolQuery, AbstractAggregation $facetAggregation)
    {
        $aggregationFilterQuery = $this->getGlobalAggregationFilters($facetConfigTransfer, $boolQuery, $facetFilters);

        $filterAggregation = $this
            ->getFactory()
            ->createAggregationBuilder()
            ->createFilterAggregation(self::AGGREGATION_FILTER_NAME);

        $filterAggregation
            ->setFilter($aggregationFilterQuery)
            ->addAggregation($facetAggregation);

        $globalAggregation = $this
            ->getFactory()
            ->createAggregationBuilder()
            ->createGlobalAggregation(self::AGGREGATION_GLOBAL_PREFIX . $facetAggregation->getName());

        $globalAggregation
            ->addAggregation($filterAggregation);

        return $globalAggregation;
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param \Elastica\Query\BoolQuery $boolQuery
     * @param \Elastica\Query\AbstractQuery[] $facetFilters
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function getGlobalAggregationFilters(FacetConfigTransfer $facetConfigTransfer, BoolQuery $boolQuery, array $facetFilters)
    {
        $aggregationFilterQuery = clone $boolQuery;

        // add filters for facet aggregation which is not related to the current facet so we can get the right counts
        foreach ($facetFilters as $name => $query) {
            if ($name !== $facetConfigTransfer->getName()) {
                $aggregationFilterQuery->addFilter($query);
            }
        }

        return $aggregationFilterQuery;
    }

    /**
     * @param \Elastica\Query $query
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function getBoolQuery(Query $query)
    {
        $boolQuery = $query->getQuery();
        if (!$boolQuery instanceof BoolQuery) {
            throw new \InvalidArgumentException(sprintf('Facet filters available only with %s, got: %s', BoolQuery::class, get_class($boolQuery)));
        }

        return $boolQuery;
    }

}
