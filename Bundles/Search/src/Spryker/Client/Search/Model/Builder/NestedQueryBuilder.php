<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Builder;

class NestedQueryBuilder implements NestedQueryBuilderInterface
{

    /**
     * @var \Spryker\Client\Search\Model\Builder\QueryBuilderInterface
     */
    protected $queryBuilder;

    /**
     * @param \Spryker\Client\Search\Model\Builder\QueryBuilderInterface $queryBuilder
     */
    public function __construct(QueryBuilderInterface $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @param string $fieldName
     * @param string $nestedFieldName
     * @param string $nestedFieldValue
     *
     * @return \Elastica\Query\Nested
     */
    public function createNestedTermQuery($fieldName, $nestedFieldName, $nestedFieldValue)
    {
        return $this->bindMultipleNestedQuery($fieldName, [
            $this->queryBuilder->createTermQuery($fieldName . '.facet-name', $nestedFieldName),
            $this->queryBuilder->createTermQuery($fieldName . '.facet-value', $nestedFieldValue),
        ]);
    }

    /**
     * @param string $fieldName
     * @param string $nestedFieldName
     * @param array $nestedFieldValues
     *
     * @return \Elastica\Query\Nested
     */
    public function createNestedTermsQuery($fieldName, $nestedFieldName, array $nestedFieldValues)
    {
        return $this->bindMultipleNestedQuery($fieldName, [
            $this->queryBuilder->createTermQuery($fieldName . '.facet-name', $nestedFieldName),
            $this->queryBuilder->createTermsQuery($fieldName . '.facet-value', $nestedFieldValues),
        ]);
    }

    /**
     * @param string $fieldName
     * @param string $nestedFieldName
     * @param float $minValue
     * @param float $maxValue
     * @param string $greaterParam
     * @param string $lessParam
     *
     * @return \Elastica\Query\Nested
     */
    public function createNestedRangeQuery(
        $fieldName,
        $nestedFieldName,
        $minValue,
        $maxValue,
        $greaterParam = 'gte',
        $lessParam = 'lte'
    ) {
        return $this->bindMultipleNestedQuery($fieldName, [
            $this->queryBuilder->createTermQuery($fieldName . '.facet-name', $nestedFieldName),
            $this->queryBuilder->createRangeQuery($fieldName . '.facet-value', $minValue, $maxValue, $greaterParam, $lessParam),
        ]);
    }

    /**
     * @param string $fieldName
     * @param array $queries
     *
     * @return \Elastica\Query\Nested
     */
    protected function bindMultipleNestedQuery($fieldName, array $queries)
    {
        $boolQuery = $this->queryBuilder->createBoolQuery();
        foreach ($queries as $query) {
            $boolQuery->addFilter($query);
        }

        $nestedQuery = $this->queryBuilder
            ->createNestedQuery($fieldName)
            ->setQuery($boolQuery);

        return $nestedQuery;
    }

}
