<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview\Plugin\Elasticsearch\QueryExpander;

use Elastica\Aggregation\Terms;
use Elastica\Query;
use Generated\Shared\Search\ProductReviewIndexMap;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;

class BatchRatingAggregationQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    public const AGGREGATION_NAME = 'product-aggregation';
    public const SUB_AGGREGATION_NAME = 'rating-aggregation';

    /**
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = [])
    {
        $this->addRatingAggregation($searchQuery->getSearchQuery());

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     *
     * @return void
     */
    protected function addRatingAggregation(Query $query)
    {
        $subRatingAggregation = new Terms(static::SUB_AGGREGATION_NAME);
        $subRatingAggregation->setField(ProductReviewIndexMap::RATING);

        $prodcutAggregation = new Terms(static::AGGREGATION_NAME);
        $prodcutAggregation->setField(ProductReviewIndexMap::ID_PRODUCT_ABSTRACT);
        $prodcutAggregation->addAggregation($subRatingAggregation);

        $query->addAggregation($prodcutAggregation);
    }
}
