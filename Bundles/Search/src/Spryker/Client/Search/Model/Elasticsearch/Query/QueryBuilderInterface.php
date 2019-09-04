<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Query;

/**
 * @deprecated Use `\Spryker\Client\SearchElasticsearch\Query\QueryBuilderInterface` instead.
 */
interface QueryBuilderInterface
{
    /**
     * @param string $fieldName
     * @param string|null $minValue
     * @param string|null $maxValue
     * @param string $greaterParam
     * @param string $lessParam
     *
     * @return \Elastica\Query\Range
     */
    public function createRangeQuery($fieldName, $minValue, $maxValue, $greaterParam = 'gte', $lessParam = 'lte');

    /**
     * @param string $fieldName
     *
     * @return \Elastica\Query\Nested
     */
    public function createNestedQuery($fieldName);

    /**
     * @param string $field
     * @param string $value
     *
     * @return \Elastica\Query\Term
     */
    public function createTermQuery($field, $value);

    /**
     * @param string $field
     * @param array $values
     *
     * @return \Elastica\Query\Terms
     */
    public function createTermsQuery($field, array $values);

    /**
     * @return \Elastica\Query\BoolQuery
     */
    public function createBoolQuery();

    /**
     * @return \Elastica\Query\Match
     */
    public function createMatchQuery();

    /**
     * @return \Elastica\Query\MatchAll
     */
    public function createMatchAllQuery();
}
