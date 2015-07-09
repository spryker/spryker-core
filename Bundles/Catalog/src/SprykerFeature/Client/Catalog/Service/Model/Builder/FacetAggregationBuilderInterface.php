<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Catalog\Service\Model\Builder;

use Elastica\Aggregation\AbstractAggregation;

interface FacetAggregationBuilderInterface
{

    /**
     * @param string $fieldName
     *
     * @return AbstractAggregation
     */
    public function createNumberFacetAggregation($fieldName);

    /**
     * @param string $fieldName
     *
     * @return AbstractAggregation
     */
    public function createStringFacetAggregation($fieldName);

}
