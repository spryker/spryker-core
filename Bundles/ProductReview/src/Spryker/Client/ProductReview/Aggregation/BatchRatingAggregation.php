<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview\Aggregation;

use Elastica\Aggregation\AbstractAggregation;
use Generated\Shared\Search\ProductReviewIndexMap;

class BatchRatingAggregation implements AggregationInterface
{
    public const PRODUCT_AGGREGATOIN_NAME = 'product-aggregation';
    public const REVIEW_AGGREGATION_NAME = 'rating-aggregation';

    /**
     * @var \Spryker\Client\ProductReview\Aggregation\AggregationBuilderInterface
     */
    protected $aggregationBuilder;

    /**
     * @param \Spryker\Client\ProductReview\Aggregation\AggregationBuilderInterface $aggregationBuilder
     */
    public function __construct(AggregationBuilderInterface $aggregationBuilder)
    {
        $this->aggregationBuilder = $aggregationBuilder;
    }

    /**
     * @return \Elastica\Aggregation\AbstractAggregation
     */
    public function createAggregation(): AbstractAggregation
    {
        $reviewAggregation = $this->aggregationBuilder->createTermsAggregation(static::REVIEW_AGGREGATION_NAME);
        $reviewAggregation->setField(ProductReviewIndexMap::RATING);

        $prodcutAggregation = $this->aggregationBuilder->createTermsAggregation(static::PRODUCT_AGGREGATOIN_NAME);
        $prodcutAggregation->setField(ProductReviewIndexMap::ID_PRODUCT_ABSTRACT);
        $prodcutAggregation->addAggregation($reviewAggregation);

        return $prodcutAggregation;
    }
}
