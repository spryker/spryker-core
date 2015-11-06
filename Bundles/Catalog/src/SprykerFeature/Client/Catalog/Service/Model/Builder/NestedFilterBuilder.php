<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Catalog\Service\Model\Builder;

use Elastica\Filter\Nested;

class NestedFilterBuilder implements NestedFilterBuilderInterface
{

    /**
     * @var FilterBuilderInterface
     */
    protected $filterBuilder;

    /**
     * @param FilterBuilderInterface $filterBuilder
     */
    public function __construct(FilterBuilderInterface $filterBuilder)
    {
        $this->filterBuilder = $filterBuilder;
    }

    /**
     * @param string $fieldName
     * @param string $nestedFieldName
     * @param string $nestedFieldValue
     *
     * @return Nested
     */
    public function createNestedTermFilter($fieldName, $nestedFieldName, $nestedFieldValue)
    {
        return $this->bindMultipleNestedFilter($fieldName, [
                $this->filterBuilder->createTermFilter($fieldName . '.facet-name', $nestedFieldName),
                $this->filterBuilder->createTermFilter($fieldName . '.facet-value', $nestedFieldValue),
            ]
        );
    }

    /**
     * @param string $fieldName
     * @param string $nestedFieldName
     * @param array $nestedFieldValues
     *
     * @return Nested
     */
    public function createNestedTermsFilter($fieldName, $nestedFieldName, array $nestedFieldValues)
    {
        return $this->bindMultipleNestedFilter($fieldName, [
                $this->filterBuilder->createTermFilter($fieldName . '.facet-name', $nestedFieldName),
                $this->filterBuilder->createTermsFilter($fieldName . '.facet-value', $nestedFieldValues),
            ]
        );
    }

    /**
     * @param string $fieldName
     * @param string $nestedFieldName
     * @param float $minValue
     * @param float $maxValue
     * @param string $greaterParam
     * @param string $lessParam
     *
     * @return Nested
     */
    public function createNestedRangeFilter(
        $fieldName,
        $nestedFieldName,
        $minValue,
        $maxValue,
        $greaterParam = 'gte',
        $lessParam = 'lte'
    ) {
        return $this->bindMultipleNestedFilter($fieldName, [
                $this->filterBuilder->createTermFilter($fieldName . '.facet-name', $nestedFieldName),
                $this->filterBuilder->createRangeFilter($fieldName . '.facet-value', $minValue, $maxValue, $greaterParam, $lessParam),
            ]
        );
    }

    /**
     * @param string $fieldName
     * @param array $filters
     *
     * @return Nested
     */
    protected function bindMultipleNestedFilter($fieldName, array $filters)
    {
        return $this->filterBuilder->createNestedFilter($fieldName)->setFilter(
            $this->filterBuilder->createBoolAndFilter()->setFilters($filters)
        );
    }

}
