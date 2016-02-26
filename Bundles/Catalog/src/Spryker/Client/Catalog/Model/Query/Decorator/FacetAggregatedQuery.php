<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Model\Query\Decorator;

use Elastica\Query;
use Spryker\Client\Catalog\Model\Builder\FacetAggregationBuilderInterface;
use Spryker\Client\Catalog\Model\FacetConfig;
use Spryker\Client\Search\Model\Query\Decorator\AbstractQueryDecorator;
use Spryker\Client\Search\Model\Query\QueryInterface;

class FacetAggregatedQuery extends AbstractQueryDecorator
{

    /**
     * @var \Spryker\Client\Catalog\Model\Builder\FacetAggregationBuilderInterface
     */
    protected $facetAggregationBuilder;

    /**
     * @var \Spryker\Client\Catalog\Model\FacetConfig
     */
    protected $facetConfig;

    /**
     * @param \Spryker\Client\Search\Model\Query\QueryInterface $searchQuery
     * @param \Spryker\Client\Catalog\Model\Builder\FacetAggregationBuilderInterface $facetAggregationBuilder
     * @param \Spryker\Client\Catalog\Model\FacetConfig $facetConfig
     */
    public function __construct(QueryInterface $searchQuery, FacetAggregationBuilderInterface $facetAggregationBuilder, FacetConfig $facetConfig)
    {
        parent::__construct($searchQuery);

        $this->facetAggregationBuilder = $facetAggregationBuilder;
        $this->facetConfig = $facetConfig;
    }

    /**
     * @return \Elastica\Query
     */
    public function getSearchQuery()
    {
        return $this->addFacetAggregationToQuery($this->searchQuery->getSearchQuery());
    }

    /**
     * @param \Elastica\Query $query
     *
     * @return \Elastica\Query
     */
    protected function addFacetAggregationToQuery(Query $query)
    {
        $stringFacetField = $this->facetConfig->getStringFacetField();
        $floatFacetField = $this->facetConfig->getFloatFacetField();
        $integerFacetField = $this->facetConfig->getIntegerFacetField();

        $query->addAggregation($this->facetAggregationBuilder->createStringFacetAggregation($stringFacetField));
        $query->addAggregation($this->facetAggregationBuilder->createNumberFacetAggregation($integerFacetField));
        $query->addAggregation($this->facetAggregationBuilder->createNumberFacetAggregation($floatFacetField));

        return $query;
    }

}
