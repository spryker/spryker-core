<?php

namespace SprykerFeature\Sdk\Catalog\Model\Builder;

use Elastica\Aggregation\AbstractAggregation;

/**
 * Class FacetAggregation
 * @package SprykerFeature\Sdk\Catalog\Model
 */
interface FacetAggregationBuilderInterface
{
    /**
     * @param $fieldName
     * @return AbstractAggregation
     */
    public function createNumberFacetAggregation($fieldName);

    /**
     * @param $fieldName
     * @return AbstractAggregation
     */
    public function createStringFacetAggregation($fieldName);
}