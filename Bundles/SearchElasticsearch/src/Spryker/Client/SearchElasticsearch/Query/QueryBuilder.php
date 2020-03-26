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

class QueryBuilder implements QueryBuilderInterface
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
    public function createRangeQuery(string $fieldName, ?string $minValue, ?string $maxValue, string $greaterParam = 'gte', string $lessParam = 'lte'): Range
    {
        $arguments = [];

        if ($minValue !== null) {
            $arguments[$greaterParam] = $minValue;
        }

        if ($maxValue !== null) {
            $arguments[$lessParam] = $maxValue;
        }

        $rangeQuery = new Range();
        $rangeQuery->addField($fieldName, $arguments);

        return $rangeQuery;
    }

    /**
     * @param string $fieldName
     *
     * @return \Elastica\Query\Nested
     */
    public function createNestedQuery(string $fieldName): Nested
    {
        $nestedQuery = new Nested();

        return $nestedQuery->setPath($fieldName);
    }

    /**
     * @param string $field
     * @param string $value
     *
     * @return \Elastica\Query\Term
     */
    public function createTermQuery(string $field, string $value): Term
    {
        $termQuery = new Term();

        return $termQuery->setTerm($field, $value);
    }

    /**
     * @param string $field
     * @param array $values
     *
     * @return \Elastica\Query\Terms
     */
    public function createTermsQuery(string $field, array $values): Terms
    {
        $termQuery = new Terms();

        return $termQuery->setTerms($field, $values);
    }

    /**
     * @return \Elastica\Query\BoolQuery
     */
    public function createBoolQuery(): BoolQuery
    {
        return new BoolQuery();
    }

    /**
     * @return \Elastica\Query\Match
     */
    public function createMatchQuery(): Match
    {
        return new Match();
    }

    /**
     * @return \Elastica\Query\MatchAll
     */
    public function createMatchAllQuery(): MatchAll
    {
        return new MatchAll();
    }
}
