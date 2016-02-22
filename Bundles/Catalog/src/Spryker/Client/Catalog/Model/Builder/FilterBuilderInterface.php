<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Model\Builder;

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
