<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview\Plugin\Elasticsearch\QueryExpander;

use Elastica\Aggregation\Terms;
use Generated\Shared\Search\ProductReviewIndexMap;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

/**
 * @method \Spryker\Client\ProductReview\ProductReviewFactory getFactory()
 */
class BatchProductRatingAggregationQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    protected const PRODUCT_AGGREGATOIN_NAME = 'product-aggregation';
    protected const REVIEW_AGGREGATION_NAME = 'rating-aggregation';

    /**
     * {@inheritDoc}
     * - Expands base query by aggregations product and rating
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = [])
    {
        $reviewAggregation = new Terms(static::REVIEW_AGGREGATION_NAME);
        $reviewAggregation->setField(ProductReviewIndexMap::RATING);

        $productAggregation = new Terms(static::PRODUCT_AGGREGATOIN_NAME);
        $productAggregation->setField(ProductReviewIndexMap::ID_PRODUCT_ABSTRACT);
        $productAggregation->addAggregation($reviewAggregation);
        $searchQuery->getSearchQuery()->addAggregation($productAggregation);

        return $searchQuery;
    }
}
