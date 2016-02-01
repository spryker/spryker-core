<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Catalog\Model\Builder;

use Elastica\Filter\BoolAnd;
use Elastica\Filter\Nested;
use Elastica\Filter\Range;
use Elastica\Filter\Term;
use Elastica\Filter\Terms;

interface FilterBuilderInterface
{

    /**
     * @param string $fieldName
     * @param float $minValue
     * @param float $maxValue
     * @param string $greaterParam
     * @param string $lessParam
     *
     * @return \Elastica\Filter\Range
     */
    public function createRangeFilter($fieldName, $minValue, $maxValue, $greaterParam = 'gte', $lessParam = 'lte');

    /**
     * @param string $fieldName
     *
     * @return \Elastica\Filter\Nested
     */
    public function createNestedFilter($fieldName);

    /**
     * @param string $field
     * @param string $value
     *
     * @return \Elastica\Filter\Term
     */
    public function createTermFilter($field, $value);

    /**
     * @param string $field
     * @param array $values
     *
     * @return \Elastica\Filter\Terms
     */
    public function createTermsFilter($field, array $values);

    /**
     * @return \Elastica\Filter\BoolAnd
     */
    public function createBoolAndFilter();

}
