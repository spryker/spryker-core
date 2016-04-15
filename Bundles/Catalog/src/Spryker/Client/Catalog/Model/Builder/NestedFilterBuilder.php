<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Model\Builder;

class NestedFilterBuilder implements NestedFilterBuilderInterface
{

    /**
     * @var \Spryker\Client\Catalog\Model\Builder\FilterBuilderInterface
     */
    protected $filterBuilder;

    /**
     * @param \Spryker\Client\Catalog\Model\Builder\FilterBuilderInterface $filterBuilder
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
     * @return \Elastica\Filter\Nested
     */
    public function createNestedTermFilter($fieldName, $nestedFieldName, $nestedFieldValue)
    {
        return $this->bindMultipleNestedFilter($fieldName, [
                $this->filterBuilder->createTermFilter($fieldName . '.facet-name', $nestedFieldName),
                $this->filterBuilder->createTermFilter($fieldName . '.facet-value', $nestedFieldValue),
            ]);
    }

    /**
     * @param string $fieldName
     * @param string $nestedFieldName
     * @param array $nestedFieldValues
     *
     * @return \Elastica\Filter\Nested
     */
    public function createNestedTermsFilter($fieldName, $nestedFieldName, array $nestedFieldValues)
    {
        return $this->bindMultipleNestedFilter($fieldName, [
                $this->filterBuilder->createTermFilter($fieldName . '.facet-name', $nestedFieldName),
                $this->filterBuilder->createTermsFilter($fieldName . '.facet-value', $nestedFieldValues),
            ]);
    }

    /**
     * @param string $fieldName
     * @param string $nestedFieldName
     * @param float $minValue
     * @param float $maxValue
     * @param string $greaterParam
     * @param string $lessParam
     *
     * @return \Elastica\Filter\Nested
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
            ]);
    }

    /**
     * @param string $fieldName
     * @param array $filters
     *
     * @return \Elastica\Filter\Nested
     */
    protected function bindMultipleNestedFilter($fieldName, array $filters)
    {
        return $this->filterBuilder->createNestedFilter($fieldName)->setFilter(
            $this->filterBuilder->createBoolAndFilter()->setFilters($filters)
        );
    }

}
