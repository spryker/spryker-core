<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Plugin\QueryExpander;

use Elastica\Query;
use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

/**
 * @method \Spryker\Client\SearchElasticsearch\SearchElasticsearchFactory getFactory()
 */
class SuggestionByTypeQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    public const AGGREGATION_NAME = 'suggestion-by-type';
    public const NESTED_AGGREGATION_NAME = 'top-hits';

    public const SIZE = 10;

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = []): QueryInterface
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
    protected function addAggregation(Query $query): void
    {
        $topHits = $this->getFactory()
            ->createAggregationBuilder()
            ->createTopHitsAggregation(static::NESTED_AGGREGATION_NAME)
            ->setSize(static::SIZE);

        $termsAggregation = $this->getFactory()
            ->createAggregationBuilder()
            ->createTermsAggregation(static::AGGREGATION_NAME)
            ->setField(PageIndexMap::TYPE)
            ->addAggregation($topHits);

        $query->addAggregation($termsAggregation);
    }
}
