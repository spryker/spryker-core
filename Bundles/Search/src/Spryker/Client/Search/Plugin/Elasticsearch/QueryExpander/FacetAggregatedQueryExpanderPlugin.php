<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query;
use Generated\Shared\Search\Catalog\PageIndexMap;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Model\Query\QueryInterface;
use Spryker\Client\Search\Plugin\QueryExpanderPluginInterface;

/**
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class FacetAggregatedQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{

    /**
     * @param \Spryker\Client\Search\Model\Query\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\Search\Model\Query\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = [])
    {
        $this->addFacetAggregationToQuery($searchQuery->getSearchQuery());

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     *
     * @return void
     */
    protected function addFacetAggregationToQuery(Query $query)
    {
        $facetAggregationBuilder = $this->getFactory()->createFacetAggregationBuilder();

        // FIXME: PageIndexMap needs to be removed from Search bundle OR PageIndexMap partially needs to come from this bundle
        $query->addAggregation($facetAggregationBuilder->createStringFacetAggregation(PageIndexMap::STRING_FACET));
        $query->addAggregation($facetAggregationBuilder->createNumberFacetAggregation(PageIndexMap::INTEGER_FACET));
        $query->addAggregation($facetAggregationBuilder->createNumberFacetAggregation(PageIndexMap::FLOAT_FACET));
    }

}
