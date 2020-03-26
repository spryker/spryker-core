<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Query;

use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Elastica\Query\MatchAll;
use Elastica\Query\Nested;
use Elastica\Query\Range;
use Elastica\Query\Term;
use Elastica\Query\Terms;

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
    public function createRangeQuery(string $fieldName, ?string $minValue, ?string $maxValue, string $greaterParam = 'gte', string $lessParam = 'lte'): Range;

    /**
     * @param string $fieldName
     *
     * @return \Elastica\Query\Nested
     */
    public function createNestedQuery(string $fieldName): Nested;

    /**
     * @param string $field
     * @param string $value
     *
     * @return \Elastica\Query\Term
     */
    public function createTermQuery(string $field, string $value): Term;

    /**
     * @param string $field
     * @param array $values
     *
     * @return \Elastica\Query\Terms
     */
    public function createTermsQuery(string $field, array $values): Terms;

    /**
     * @return \Elastica\Query\BoolQuery
     */
    public function createBoolQuery(): BoolQuery;

    /**
     * @return \Elastica\Query\Match
     */
    public function createMatchQuery(): Match;

    /**
     * @return \Elastica\Query\MatchAll
     */
    public function createMatchAllQuery(): MatchAll;
}
