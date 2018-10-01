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
use InvalidArgumentException;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\FacetConfigBuilderInterface;
use Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;

/**
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class FacetQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    public const AGGREGATION_FILTER_NAME = 'filter';
    public const AGGREGATION_GLOBAL_PREFIX = 'global-';

    /**
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = [])
    {
        $searchConfig = $this->getFactory()->getSearchConfig();
        $facetConfig = $searchConfig->getFacetConfigBuilder();
        $query = $searchQuery->getSearchQuery();

        $facetFilters = $this->getFacetFilters($facetConfig, $requestParameters);

        $this->addFacetAggregationToQuery($query, $facetConfig, $facetFilters, $requestParameters);
        $this->addFacetFiltersToQuery($query, $facetFilters);

        return $searchQuery;
    }

    /**
     * @param \Spryker\Client\Search\Dependency\Plugin\FacetConfigBuilderInterface $facetConfig
     * @param array $requestParameters
     *
     * @return \Elastica\Query\AbstractQuery[]
     */
    protected function getFacetFilters(FacetConfigBuilderInterface $facetConfig, array $requestParameters = [])
    {
        $facetFilters = [];
        $activeFacetConfigTransfers = $facetConfig->getActive($requestParameters);

        foreach ($activeFacetConfigTransfers as $facetConfigTransfer) {
            $filterValue = isset($requestParameters[$facetConfigTransfer->getParameterName()]) ? $requestParameters[$facetConfigTransfer->getParameterName()] : null;

            if ($facetConfigTransfer->getIsMultiValued() === true) {
                $query = $this->createMultiValuedFacetFilterQuery($facetConfigTransfer, $filterValue);
            } else {
                $query = $this->createFacetFilterQuery($facetConfigTransfer, $filterValue);
            }

            if ($query !== null) {
                $facetFilters[$facetConfigTransfer->getName()] = $query;
            }
        }

        return $facetFilters;
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param mixed $filterValue
     *
     * @return \Elastica\Query\AbstractQuery
     */
    protected function createMultiValuedFacetFilterQuery(FacetConfigTransfer $facetConfigTransfer, $filterValue)
    {
        $boolQuery = $this
            ->getFactory()
            ->createQueryBuilder()
            ->createBoolQuery();

        if (!is_array($filterValue)) {
            $filterValue = [$filterValue];
        }

        foreach ($filterValue as $value) {
            $query = $this->createFacetFilterQuery($facetConfigTransfer, $value);

            if ($query !== null) {
                $boolQuery->addShould($query);
            }
        }

        return $boolQuery;
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param string $filterValue
     *
     * @return \Elastica\Query\AbstractQuery|null
     */
    protected function createFacetFilterQuery(FacetConfigTransfer $facetConfigTransfer, $filterValue)
    {
        if (empty($filterValue)) {
            return null;
        }

        $valueTransformerPlugin = $this->getFactory()
            ->createFacetValueTransformerFactory()
            ->createTransformer($facetConfigTransfer);

        if ($valueTransformerPlugin) {
            $filterValue = $valueTransformerPlugin->transformFromDisplay($filterValue);
        }

        $query = $this
            ->getFactory()
            ->createQueryFactory()
            ->create($facetConfigTransfer, $filterValue);

        return $query;
    }

    /**
     * @param \Elastica\Query $query
     * @param \Spryker\Client\Search\Dependency\Plugin\FacetConfigBuilderInterface $facetConfig
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
            ->createFilterAggregation(self::AGGREGATION_FILTER_NAME)
            ->setFilter($aggregationFilterQuery)
            ->addAggregation($facetAggregation);

        $globalAggregation = $this
            ->getFactory()
            ->createAggregationBuilder()
            ->createGlobalAggregation(self::AGGREGATION_GLOBAL_PREFIX . $facetConfigTransfer->getName());

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
     * @throws \InvalidArgumentException
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function getBoolQuery(Query $query)
    {
        $boolQuery = $query->getQuery();
        if (!$boolQuery instanceof BoolQuery) {
            throw new InvalidArgumentException(sprintf('Facet filters available only with %s, got: %s', BoolQuery::class, get_class($boolQuery)));
        }

        return $boolQuery;
    }
}
