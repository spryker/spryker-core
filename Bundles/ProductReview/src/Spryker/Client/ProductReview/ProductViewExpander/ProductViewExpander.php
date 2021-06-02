<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview\ProductViewExpander;

use Generated\Shared\Transfer\ProductReviewSearchRequestTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\RatingAggregationTransfer;
use Spryker\Client\ProductReview\Calculator\ProductReviewSummaryCalculatorInterface;
use Spryker\Client\ProductReview\Search\ProductReviewSearchReaderInterface;

class ProductViewExpander implements ProductViewExpanderInterface
{
    /**
     * @see \Spryker\Client\ProductReview\Plugin\Elasticsearch\ResultFormatter\RatingAggregationResultFormatterPlugin::NAME
     */
    protected const KEY_RATING_AGGREGATION = 'ratingAggregation';
    /**
     * @see \Spryker\Client\ProductReview\Plugin\Elasticsearch\ResultFormatter\RatingAggregationBatchResultFormatterPlugin::NAME
     */
    protected const KEY_BATCH_RATING_AGGREGATION = 'productAggregation';

    /**
     * @var \Spryker\Client\ProductReview\Calculator\ProductReviewSummaryCalculatorInterface
     */
    protected $productReviewSummaryCalculator;

    /**
     * @var \Spryker\Client\ProductReview\Search\ProductReviewSearchReaderInterface
     */
    protected $productReviewSearchReader;

    /**
     * @param \Spryker\Client\ProductReview\Calculator\ProductReviewSummaryCalculatorInterface $productReviewSummaryCalculator
     * @param \Spryker\Client\ProductReview\Search\ProductReviewSearchReaderInterface $productReviewSearchReader
     */
    public function __construct(
        ProductReviewSummaryCalculatorInterface $productReviewSummaryCalculator,
        ProductReviewSearchReaderInterface $productReviewSearchReader
    ) {
        $this->productReviewSummaryCalculator = $productReviewSummaryCalculator;
        $this->productReviewSearchReader = $productReviewSearchReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param \Generated\Shared\Transfer\ProductReviewSearchRequestTransfer $productReviewSearchRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewWithProductReviewData(
        ProductViewTransfer $productViewTransfer,
        ProductReviewSearchRequestTransfer $productReviewSearchRequestTransfer
    ): ProductViewTransfer {
        $productReviews = $this->productReviewSearchReader->findProductReviews($productReviewSearchRequestTransfer);

        if (!isset($productReviews[static::KEY_RATING_AGGREGATION])) {
            return $productViewTransfer;
        }

        $productReviewSummaryTransfer = $this->productReviewSummaryCalculator
            ->calculate($this->createRatingAggregationTransfer($productReviews));

        $productViewTransfer->setRating($productReviewSummaryTransfer);

        return $productViewTransfer;
    }

    /**
     * @param array $productReviews
     *
     * @return \Generated\Shared\Transfer\RatingAggregationTransfer
     */
    protected function createRatingAggregationTransfer(array $productReviews): RatingAggregationTransfer
    {
        return (new RatingAggregationTransfer())
            ->setRatingAggregation($productReviews[static::KEY_RATING_AGGREGATION]);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $productViewTransfers
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function expandProductViewsWithProductReviewData(
        array $productViewTransfers
    ): array {
        $aggregationProductReviews = $this->productReviewSearchReader->searchProductReviews();

        if (!isset($aggregationProductReviews[static::KEY_BATCH_RATING_AGGREGATION])) {
            return $productViewTransfers;
        }

        foreach ($aggregationProductReviews[static::KEY_BATCH_RATING_AGGREGATION] as $productId => $productReviews) {
            if (empty($productReviews[static::KEY_RATING_AGGREGATION])) {
                continue;
            }

            $productReviewSummaryTransfer = $this->productReviewSummaryCalculator->calculate(
                $this->createRatingAggregationTransfer($productReviews)
            );

            foreach ($productViewTransfers as $productViewId => $productViewTransfer) {
                if ($productViewId === $productId) {
                    $productViewTransfer->setRating($productReviewSummaryTransfer);
                }
            }
        }

        return $productViewTransfers;
    }
}
