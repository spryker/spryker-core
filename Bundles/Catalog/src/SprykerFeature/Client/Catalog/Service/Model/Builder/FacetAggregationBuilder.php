<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Catalog\Service\Model\Builder;

use Elastica\Aggregation\AbstractAggregation;
use Elastica\Aggregation\AbstractSimpleAggregation;
use Elastica\Aggregation\Max;
use Elastica\Aggregation\Min;
use Elastica\Aggregation\Nested;
use Elastica\Aggregation\Terms;

class FacetAggregationBuilder implements FacetAggregationBuilderInterface
{

    const FACET_VALUE = 'facet-value';
    const FACET_NAME = 'facet-name';

    /**
     * @param string $fieldName
     *
     * @return AbstractAggregation
     */
    public function createStringFacetAggregation($fieldName)
    {
        $facetValueAgg = (new Terms($fieldName . '-value'))
            ->setField($this->addNestedFieldPrefix($fieldName, self::FACET_VALUE));
        $facetNameAgg = $this->createFacetNameAggregation($fieldName);
        $facetNameAgg->addAggregation($facetValueAgg);

        return $this->createNestedFacetAggregation($fieldName, $facetNameAgg);
    }

    /**
     * @param string $fieldName
     *
     * @return AbstractAggregation
     */
    public function createNumberFacetAggregation($fieldName)
    {
        $prefixedFieldName = $this->addNestedFieldPrefix($fieldName, self::FACET_VALUE);
        $facetValueTerm = (new Terms($fieldName . '-value'))
            ->setField($prefixedFieldName);
        $facetValueMin = (new Min($fieldName . '-min'))
            ->setField($prefixedFieldName);
        $facetValueMax = (new Max($fieldName . '-max'))
            ->setField($prefixedFieldName);

        $facetNameAgg = $this->createFacetNameAggregation($fieldName);

        $facetNameAgg->addAggregation($facetValueTerm);
        $facetNameAgg->addAggregation($facetValueMin);
        $facetNameAgg->addAggregation($facetValueMax);

        return $this->createNestedFacetAggregation($fieldName, $facetNameAgg);
    }

    /**
     * @param string $fieldName
     * @param AbstractAggregation $aggregation
     *
     * @return AbstractAggregation
     */
    protected function createNestedFacetAggregation($fieldName, AbstractAggregation $aggregation)
    {
        return (new Nested($fieldName, $fieldName))
            ->addAggregation($aggregation);
    }

    /**
     * @param string $fieldName
     *
     * @return AbstractSimpleAggregation
     */
    protected function createFacetNameAggregation($fieldName)
    {
        return (new Terms($fieldName . '-name'))
            ->setField($this->addNestedFieldPrefix($fieldName, self::FACET_NAME));
    }

    /**
     * @param string $nestedFieldName
     * @param string $fieldName
     *
     * @return string
     */
    protected function addNestedFieldPrefix($nestedFieldName, $fieldName)
    {
        return $nestedFieldName . '.' . $fieldName;
    }

}
