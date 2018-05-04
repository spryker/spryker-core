<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Aggregation;

use Generated\Shared\Transfer\FacetConfigTransfer;

class NumericFacetAggregation extends AbstractFacetAggregation
{
    const STATS_SUFFIX = '-stats';

    /**
     * @var \Generated\Shared\Transfer\FacetConfigTransfer
     */
    protected $facetConfigTransfer;

    /**
     * @var \Spryker\Client\Search\Model\Elasticsearch\Aggregation\AggregationBuilderInterface
     */
    protected $aggregationBuilder;

    /**
     * @var int
     */
    protected $facetNameAggregationSize;

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param \Spryker\Client\Search\Model\Elasticsearch\Aggregation\AggregationBuilderInterface $aggregationBuilder
     * @param int $facetNameAggregationSize
     */
    public function __construct(FacetConfigTransfer $facetConfigTransfer, AggregationBuilderInterface $aggregationBuilder, int $facetNameAggregationSize = 0)
    {
        $this->facetConfigTransfer = $facetConfigTransfer;
        $this->aggregationBuilder = $aggregationBuilder;
        $this->facetNameAggregationSize = $facetNameAggregationSize;
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
            $facetNameAgg = $this->createFacetNameAggregation($fieldName, $this->facetNameAggregationSize);
        }

        $facetNameAgg->addAggregation($facetValueStats);

        return $this->createNestedFacetAggregation($nestedFieldName, $facetNameAgg, $fieldName);
    }
}
