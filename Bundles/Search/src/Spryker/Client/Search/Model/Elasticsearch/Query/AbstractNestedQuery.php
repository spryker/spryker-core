<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Query;

abstract class AbstractNestedQuery implements NestedQueryInterface
{
    public const FACET_NAME_SUFFIX = '.facet-name';
    public const FACET_VALUE_SUFFIX = '.facet-value';

    /**
     * @var \Spryker\Client\Search\Model\Elasticsearch\Query\QueryBuilderInterface
     */
    protected $queryBuilder;

    /**
     * @param \Spryker\Client\Search\Model\Elasticsearch\Query\QueryBuilderInterface $queryBuilder
     */
    public function __construct(QueryBuilderInterface $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
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
