<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview\Aggregation;

use Elastica\Aggregation\AbstractAggregation;
use Elastica\Aggregation\Terms;
use Generated\Shared\Search\ProductReviewIndexMap;

class BatchRatingAggregation implements AggregationInterface
{
    public const PRODUCT_AGGREGATOIN_NAME = 'product-aggregation';
    public const REVIEW_AGGREGATION_NAME = 'rating-aggregation';

    /**
     * @return \Elastica\Aggregation\AbstractAggregation
     */
    public function createAggregation(): AbstractAggregation
    {
        $reviewAggregation = $this->createTermsAggregation(static::REVIEW_AGGREGATION_NAME);
        $reviewAggregation->setField(ProductReviewIndexMap::RATING);

        $productAggregation = $this->createTermsAggregation(static::PRODUCT_AGGREGATOIN_NAME);
        $productAggregation->setField(ProductReviewIndexMap::ID_PRODUCT_ABSTRACT);
        $productAggregation->addAggregation($reviewAggregation);

        return $productAggregation;
    }

    /**
     * @param string $name
     *
     * @return \Elastica\Aggregation\Terms
     */
    protected function createTermsAggregation(string $name): Terms
    {
        return new Terms($name);
    }
}
