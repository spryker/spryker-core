<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview\Calculator;

use Generated\Shared\Transfer\ProductReviewSummaryTransfer;
use Generated\Shared\Transfer\RatingAggregationTransfer;
use Spryker\Client\ProductReview\ProductReviewConfig;

class ProductReviewSummaryCalculator implements ProductReviewSummaryCalculatorInterface
{
    public const MINIMUM_RATING = 1;
    public const RATING_PRECISION = 1;

    /**
     * @var \Spryker\Client\ProductReview\ProductReviewConfig
     */
    protected $productReviewConfig;

    /**
     * @param \Spryker\Client\ProductReview\ProductReviewConfig $productReviewConfig
     */
    public function __construct(ProductReviewConfig $productReviewConfig)
    {
        $this->productReviewConfig = $productReviewConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\RatingAggregationTransfer $ratingAggregationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductReviewSummaryTransfer
     */
    public function calculate(RatingAggregationTransfer $ratingAggregationTransfer): ProductReviewSummaryTransfer
    {
        $ratingAggregationTransfer->requireRatingAggregation();

        $ratingAggregation = $ratingAggregationTransfer->getRatingAggregation();
        $totalReview = $this->getTotalReview($ratingAggregation);

        return (new ProductReviewSummaryTransfer())
            ->setRatingAggregation($this->formatRatingAggregation($ratingAggregation))
            ->setMaximumRating($this->productReviewConfig->getMaximumRating())
            ->setAverageRating($this->getAverageRating($ratingAggregation, $totalReview))
            ->setTotalReview($totalReview);
    }

    /**
     * @param int[] $ratingAggregation
     * @param int $totalReview
     *
     * @return float
     */
    protected function getAverageRating(array $ratingAggregation, int $totalReview): float
    {
        if ($totalReview === 0) {
            return 0.0;
        }

        $totalRating = $this->getTotalRating($ratingAggregation);

        return round($totalRating / $totalReview, static::RATING_PRECISION);
    }

    /**
     * @param int[] $ratingAggregation
     *
     * @return int[]
     */
    protected function formatRatingAggregation(array $ratingAggregation): array
    {
        $ratingAggregation = $this->fillRatings($ratingAggregation);
        $ratingAggregation = $this->sortRatings($ratingAggregation);

        return $ratingAggregation;
    }

    /**
     * @param int[] $ratingAggregation
     *
     * @return int[]
     */
    protected function fillRatings(array $ratingAggregation): array
    {
        $maximumRating = $this->productReviewConfig->getMaximumRating();

        for ($rating = static::MINIMUM_RATING; $rating <= $maximumRating; $rating++) {
            $ratingAggregation[$rating] = array_key_exists($rating, $ratingAggregation) ? $ratingAggregation[$rating] : 0;
        }

        return $ratingAggregation;
    }

    /**
     * @param int[] $ratingAggregation
     *
     * @return int[]
     */
    protected function sortRatings(array $ratingAggregation): array
    {
        krsort($ratingAggregation);

        return $ratingAggregation;
    }

    /**
     * @param int[] $ratingAggregation
     *
     * @return int
     */
    protected function getTotalReview(array $ratingAggregation): int
    {
        return array_sum($ratingAggregation);
    }

    /**
     * @param int[] $ratingAggregation
     *
     * @return int
     */
    protected function getTotalRating(array $ratingAggregation): int
    {
        $totalRating = 0;

        foreach ($ratingAggregation as $rating => $reviewCount) {
            $totalRating += $reviewCount * $rating;
        }

        return $totalRating;
    }
}
