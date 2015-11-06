<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Catalog\Service\Model\Builder;

use Elastica\Filter\BoolAnd;
use Elastica\Filter\Nested;
use Elastica\Filter\Term;
use Elastica\Filter\Terms;
use Elastica\Filter\Range;

/**
 * Class FilterBuilder
 */
class FilterBuilder implements FilterBuilderInterface
{

    /**
     * @param string $fieldName
     * @param float $minValue
     * @param float $maxValue
     * @param string $greaterParam
     * @param string $lessParam
     *
     * @return Range
     */
    public function createRangeFilter($fieldName, $minValue, $maxValue, $greaterParam = 'gte', $lessParam = 'lte')
    {
        $rangeFilter = new Range();
        $rangeFilter->addField(
            $fieldName,
            [
                $greaterParam => $minValue,
                $lessParam => $maxValue,
            ]
        );

        return $rangeFilter;
    }

    /**
     * @param string $fieldName
     *
     * @return Nested
     */
    public function createNestedFilter($fieldName)
    {
        $nestedFilter = new Nested();

        return $nestedFilter->setPath($fieldName);
    }

    /**
     * @param string $field
     * @param string $value
     *
     * @return Term
     */
    public function createTermFilter($field, $value)
    {
        $termFilter = new Term();

        return $termFilter->setTerm($field, $value);
    }

    /**
     * @param string $field
     * @param array $values
     *
     * @return Terms
     */
    public function createTermsFilter($field, array $values)
    {
        $termFilter = new Terms();

        return $termFilter->setTerms($field, $values);
    }

    /**
     * @return BoolAnd
     */
    public function createBoolAndFilter()
    {
        return new BoolAnd();
    }

}
