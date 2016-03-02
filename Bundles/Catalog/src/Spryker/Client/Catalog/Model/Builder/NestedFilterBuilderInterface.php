<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Model\Builder;

/**
 * Class FilterBuilder
 */
interface NestedFilterBuilderInterface
{

    /**
     * @param string $fieldName
     * @param string $nestedFieldName
     * @param string $nestedFieldValue
     *
     * @return \Elastica\Filter\Nested
     */
    public function createNestedTermFilter($fieldName, $nestedFieldName, $nestedFieldValue);

    /**
     * @param string $fieldName
     * @param string $nestedFieldName
     * @param array $nestedFieldValues
     *
     * @return \Elastica\Filter\Nested
     */
    public function createNestedTermsFilter($fieldName, $nestedFieldName, array $nestedFieldValues);

    /**
     * @param string $fieldName
     * @param string $nestedFieldName
     * @param string $minValue
     * @param string $maxValue
     * @param string $greaterParam
     * @param string $lessParam
     *
     * @return \Elastica\Filter\Nested
     */
    public function createNestedRangeFilter($fieldName, $nestedFieldName, $minValue, $maxValue, $greaterParam = 'gte', $lessParam = 'lte');

}
