<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Query;

use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Elastica\Query\MatchAll;
use Elastica\Query\Nested;
use Elastica\Query\Range;
use Elastica\Query\Term;
use Elastica\Query\Terms;

/**
 * @deprecated Use `\Spryker\Client\SearchElasticsearch\Query\QueryBuilder` instead.
 */
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
    public function createRangeQuery($fieldName, $minValue, $maxValue, $greaterParam = 'gte', $lessParam = 'lte')
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
    public function createNestedQuery($fieldName)
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
    public function createTermQuery($field, $value)
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
    public function createTermsQuery($field, array $values)
    {
        $termQuery = new Terms();

        return $termQuery->setTerms($field, $values);
    }

    /**
     * @return \Elastica\Query\BoolQuery
     */
    public function createBoolQuery()
    {
        return new BoolQuery();
    }

    /**
     * @return \Elastica\Query\Match
     */
    public function createMatchQuery()
    {
        return new Match();
    }

    /**
     * @return \Elastica\Query\MatchAll
     */
    public function createMatchAllQuery()
    {
        return new MatchAll();
    }
}
