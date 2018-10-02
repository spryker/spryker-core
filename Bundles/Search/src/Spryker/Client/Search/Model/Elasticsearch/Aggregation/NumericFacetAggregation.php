<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Aggregation;

use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\Search\SearchConfig;

class NumericFacetAggregation extends AbstractFacetAggregation
{
    public const STATS_SUFFIX = '-stats';

    /**
     * @var \Generated\Shared\Transfer\FacetConfigTransfer
     */
    protected $facetConfigTransfer;

    /**
     * @var \Spryker\Client\Search\Model\Elasticsearch\Aggregation\AggregationBuilderInterface
     */
    protected $aggregationBuilder;

    /**
     * @var \Spryker\Client\Search\SearchConfig
     */
    protected $searchConfig;

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param \Spryker\Client\Search\Model\Elasticsearch\Aggregation\AggregationBuilderInterface $aggregationBuilder
     * @param \Spryker\Client\Search\SearchConfig $searchConfig
     */
    public function __construct(FacetConfigTransfer $facetConfigTransfer, AggregationBuilderInterface $aggregationBuilder, SearchConfig $searchConfig)
    {
        $this->facetConfigTransfer = $facetConfigTransfer;
        $this->aggregationBuilder = $aggregationBuilder;
        $this->searchConfig = $searchConfig;
    }

    /**
     * @return \Elastica\Aggregation\AbstractAggregation
     */
    public function createAggregation()
    {
        $fieldName = $this->facetConfigTransfer->getFieldName();
        $nestedFieldName = $this->getNestedFieldName($this->facetConfigTransfer);

        $prefixedFieldName = $this->addNestedFieldPrefix($fieldName, static::FACET_VALUE);

        $facetValueStats = $this
            ->aggregationBuilder
            ->createStatsAggregation($nestedFieldName . static::STATS_SUFFIX)
            ->setField($prefixedFieldName);

        $facetValueStats = $this->applyAggregationParams($facetValueStats, $this->facetConfigTransfer);

        if ($this->facetConfigTransfer->getAggregationParams()) {
            $facetNameAgg = $this
                ->createStandaloneFacetNameAggregation(
                    $fieldName,
                    $this->facetConfigTransfer->getName()
                );
        } else {
            $facetNameAgg = $this->createFacetNameAggregation($fieldName, $this->getFacetNameAggregationSize());
        }

        $facetNameAgg->addAggregation($facetValueStats);

        return $this->createNestedFacetAggregation($nestedFieldName, $facetNameAgg, $fieldName);
    }

    /**
     * @return int
     */
    protected function getFacetNameAggregationSize(): int
    {
        return $this->searchConfig->getFacetNameAggregationSize();
    }
}
