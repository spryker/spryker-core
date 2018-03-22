<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Exists;
use Elastica\Query\Range;
use Generated\Shared\Search\PageIndexMap;
use InvalidArgumentException;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;

/**
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class IsActiveInDateRangeQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * @api
     *
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = [])
    {
        $this->addIsActiveInDateRangeFilterToQuery($searchQuery->getSearchQuery());

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     *
     * @return void
     */
    protected function addIsActiveInDateRangeFilterToQuery(Query $query)
    {
        $boolQuery = $this->getBoolQuery($query);

        $boolQuery->addMust($this->createActiveFromQuery());
        $boolQuery->addMust($this->createActiveToQuery());
    }

    /**
     * @param \Elastica\Query $query
     *
     * @throws \InvalidArgumentException
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function getBoolQuery(Query $query)
    {
        $boolQuery = $query->getQuery();
        if (!$boolQuery instanceof BoolQuery) {
            throw new InvalidArgumentException(sprintf(
                'Localized query expander available only with %s, got: %s',
                BoolQuery::class,
                get_class($boolQuery)
            ));
        }

        return $boolQuery;
    }

    /**
     * @return \Elastica\Query\BoolQuery
     */
    protected function createActiveFromQuery()
    {
        $rangeFromQuery = new Range();
        $rangeFromQuery->addField(
            PageIndexMap::ACTIVE_FROM,
            ['lte' => 'now']
        );

        $missingFrom = $this->getFactory()
            ->createQueryBuilder()
            ->createBoolQuery()
            ->addMustNot(new Exists(PageIndexMap::ACTIVE_FROM));

        $boolFromQuery = $this->getFactory()
            ->createQueryBuilder()
            ->createBoolQuery()
            ->addShould($rangeFromQuery)
            ->addShould($missingFrom);

        return $boolFromQuery;
    }

    /**
     * @return \Elastica\Query\BoolQuery
     */
    protected function createActiveToQuery()
    {
        $rangeToQuery = new Range();
        $rangeToQuery->addField(
            PageIndexMap::ACTIVE_TO,
            ['gte' => 'now']
        );

        $missingTo = $this->getFactory()
            ->createQueryBuilder()
            ->createBoolQuery()
            ->addMustNot(new Exists(PageIndexMap::ACTIVE_TO));

        $boolToQuery = $this->getFactory()
            ->createQueryBuilder()
            ->createBoolQuery()
            ->addShould($rangeToQuery)
            ->addShould($missingTo);

        return $boolToQuery;
    }
}
