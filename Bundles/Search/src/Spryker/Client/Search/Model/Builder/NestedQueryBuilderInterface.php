<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Builder;

interface NestedQueryBuilderInterface
{

    /**
     * @param string $fieldName
     * @param string $nestedFieldName
     * @param string $nestedFieldValue
     *
     * @return \Elastica\Query\Nested
     */
    public function createNestedTermQuery($fieldName, $nestedFieldName, $nestedFieldValue);

    /**
     * @param string $fieldName
     * @param string $nestedFieldName
     * @param array $nestedFieldValues
     *
     * @return \Elastica\Query\Nested
     */
    public function createNestedTermsQuery($fieldName, $nestedFieldName, array $nestedFieldValues);

    /**
     * @param string $fieldName
     * @param string $nestedFieldName
     * @param string $minValue
     * @param string $maxValue
     * @param string $greaterParam
     * @param string $lessParam
     *
     * @return \Elastica\Query\Nested
     */
    public function createNestedRangeQuery($fieldName, $nestedFieldName, $minValue, $maxValue, $greaterParam = 'gte', $lessParam = 'lte');

}
