<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview\ProductViewExpander;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductReview\Calculator\ProductReviewSummaryCalculatorInterface;
use Spryker\Client\ProductReview\Search\ProductReviewSearchReaderInterface;

class ProductViewExpander implements ProductViewExpanderInterface
{
    protected const KEY_RATING_AGGREGATION = 'ratingAggregation';

    /**
     * @var \Spryker\Client\ProductReview\Calculator\ProductReviewSummaryCalculatorInterface
     */
    protected $productReviewSummaryCalculator;

    /**
     * @var \Spryker\Client\ProductReview\Search\ProductReviewSearchReaderInterface
     */
    protected $productReviewSearchReader;

    /**
     * @var \Generated\Shared\Transfer\ProductReviewSearchRequestTransfer
     */
    protected $productReviewSearchRequestTransfer;

    /**
     * @param \Spryker\Client\ProductReview\Calculator\ProductReviewSummaryCalculatorInterface $productReviewSummaryCalculator
     * @param \Spryker\Client\ProductReview\Search\ProductReviewSearchReaderInterface $productReviewSearchReader
     * @param \Generated\Shared\Transfer\ProductReviewSearchRequestTransfer $productReviewSearchRequestTransfer
     */
    public function __construct(
        ProductReviewSummaryCalculatorInterface $productReviewSummaryCalculator,
        ProductReviewSearchReaderInterface $productReviewSearchReader,
        $productReviewSearchRequestTransfer
    ) {
        $this->productReviewSummaryCalculator = $productReviewSummaryCalculator;
        $this->productReviewSearchReader = $productReviewSearchReader;
        $this->productReviewSearchRequestTransfer = $productReviewSearchRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewWithProductReviewData(ProductViewTransfer $productViewTransfer): ProductViewTransfer
    {
        $productReviews = $this->productReviewSearchReader->findProductReviews($this->productReviewSearchRequestTransfer);

        if (!isset($productReviews[static::KEY_RATING_AGGREGATION])) {
            return $productViewTransfer;
        }

        $productReviewSummaryTransfer = $this->productReviewSummaryCalculator->execute($productReviews[static::KEY_RATING_AGGREGATION]);
        $productViewTransfer->setRating($productReviewSummaryTransfer);

        return $productViewTransfer;
    }
}
