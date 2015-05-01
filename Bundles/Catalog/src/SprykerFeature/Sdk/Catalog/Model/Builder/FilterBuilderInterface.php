<?php
/**
 * Created by PhpStorm.
 * User: trosenstock
 * Date: 20.01.15
 * Time: 16:26
 */
namespace SprykerFeature\Sdk\Catalog\Model\Builder;

use Elastica\Filter\BoolAnd;
use Elastica\Filter\Nested;
use Elastica\Filter\Range;
use Elastica\Filter\Term;
use Elastica\Filter\Terms;

interface FilterBuilderInterface
{
    /**
     * @param $fieldName
     * @param $minValue
     * @param $maxValue
     * @param string $greaterParam
     * @param string $lessParam
     * @return Range
     */
    public function createRangeFilter($fieldName, $minValue, $maxValue, $greaterParam = 'gte', $lessParam = 'lte');

    /**
     * @param $fieldName
     * @return Nested
     */
    public function createNestedFilter($fieldName);

    /**
     * @param $field
     * @param $value
     * @return Term
     */
    public function createTermFilter($field, $value);

    /**
     * @param $field
     * @param array $values
     * @return Terms
     */
    public function createTermsFilter($field, array $values);

    /**
     * @return BoolAnd
     */
    public function createBoolAndFilter();
}