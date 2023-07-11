<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReviewSearch\Builder;

class ProductReviewKeyBuilder implements ProductReviewKeyBuilderInterface
{
    /**
     * @uses \Spryker\Shared\ProductReview\ProductReviewConfig::RESOURCE_TYPE_PRODUCT_REVIEW
     *
     * @var string
     */
    protected const RESOURCE_TYPE_PRODUCT_REVIEW = 'product_review';

    /**
     * @param string $idProductReview
     *
     * @return string
     */
    public function buildKey(string $idProductReview): string
    {
        return sprintf(
            '%s:%s',
            static::RESOURCE_TYPE_PRODUCT_REVIEW,
            $idProductReview,
        );
    }
}
