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
use Spryker\Client\Search\Plugin\QueryExpanderPluginInterface;

/**
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class FacetQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{

    /**
     * @param \Spryker\Client\Search\Model\Query\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\Search\Model\Query\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = [])
    {
        $facetConfig = $this
            ->getFactory()
            ->getSearchConfig()
            ->getFacetConfigBuilder();

        $query = $searchQuery->getSearchQuery();

        $this->addFacetAggregationToQuery($query, $facetConfig);
        $this->addFacetFiltersToQuery($query, $facetConfig, $requestParameters);

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     * @param \Spryker\Client\Search\Plugin\Config\FacetConfigBuilderInterface $facetConfig
     *
     * @return void
     */
    protected function addFacetAggregationToQuery(Query $query, FacetConfigBuilderInterface $facetConfig)
    {
        foreach ($facetConfig->getAll() as $facetConfigTransfer) {
            $facetAggregation = $this
                ->getFactory()
                ->createFacetAggregationFactory()
                ->create($facetConfigTransfer)
                ->createAggregation();

            $query->addAggregation($facetAggregation);
        }
    }

    /**
     * @param \Elastica\Query $query
     * @param \Spryker\Client\Search\Plugin\Config\FacetConfigBuilderInterface $facetConfig
     * @param array $requestParameters
     *
     * @return void
     */
    protected function addFacetFiltersToQuery(Query $query, FacetConfigBuilderInterface $facetConfig, array $requestParameters)
    {
        $boolQuery = $query->getQuery();
        if (!$boolQuery instanceof BoolQuery) {
            throw new \InvalidArgumentException(sprintf('Facet filters available only with %s, got: %s', BoolQuery::class, get_class($boolQuery)));
        }

        $activeFacetConfigTransfers = $facetConfig->getActive($requestParameters);

        foreach ($activeFacetConfigTransfers as $facetConfigTransfer) {
            $this->addFacetFilter($boolQuery, $facetConfigTransfer, $requestParameters);
        }
    }

    /**
     * @param \Elastica\Query\BoolQuery $boolQuery
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param array $requestParameters
     *
     * @return void
     */
    protected function addFacetFilter(BoolQuery $boolQuery, FacetConfigTransfer $facetConfigTransfer, array $requestParameters)
    {
        $filterValue = isset($requestParameters[$facetConfigTransfer->getParameterName()]) ? $requestParameters[$facetConfigTransfer->getParameterName()] : null;

        if (trim($filterValue) === '') {
            return;
        }

        $query = $this
            ->getFactory()
            ->createNestedQueryFactory()
            ->create($facetConfigTransfer, $filterValue)
            ->createNestedQuery();

        $boolQuery->addFilter($query);
    }

}
