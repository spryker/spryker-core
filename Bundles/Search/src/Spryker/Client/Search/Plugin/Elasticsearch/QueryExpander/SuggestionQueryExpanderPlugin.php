<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Aggregation\Terms;
use Elastica\Query;
use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;

/**
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class SuggestionQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{

    const AGGREGATION_NAME = 'suggestion';

    const SIZE = 10;

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
        $query = $searchQuery->getSearchQuery();
        $this->addAggregation($query);

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     *
     * @return void
     */
    protected function addAggregation(Query $query)
    {
        $termsAggregation = new Terms(static::AGGREGATION_NAME);
        $termsAggregation
            ->setField(PageIndexMap::SUGGESTION_TERMS)
            ->setSize(static::SIZE);

        $query->addAggregation($termsAggregation);
    }

}
