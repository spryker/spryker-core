<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview\ProductViewExpander;

use Generated\Shared\Transfer\ProductReviewSearchRequestTransfer;
use Generated\Shared\Transfer\ProductReviewSummaryTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\RatingAggregationTransfer;
use Spryker\Client\ProductReview\Calculator\ProductReviewSummaryCalculatorInterface;
use Spryker\Client\ProductReview\Search\ProductReviewSearchReaderInterface;

class ProductViewExpander implements ProductViewExpanderInterface
{
    /**
     * @see \Spryker\Client\ProductReview\Plugin\Elasticsearch\ResultFormatter\RatingAggregationResultFormatterPlugin::NAME
     *
     * @var string
     */
    protected const KEY_RATING_AGGREGATION = 'ratingAggregation';

    /**
     * @see \Spryker\Client\ProductReview\Plugin\Elasticsearch\ResultFormatter\ProductRatingAggregationBulkResultFormatterPlugin::NAME
     *
     * @var string
     */
    protected const KEY_PRODUCT_BULK_AGGREGATION = 'productAggregation';

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
     * @param array<int, \Generated\Shared\Transfer\ProductViewTransfer> $productViewTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\ProductViewTransfer>
     */
    public function expandProductViewsWithProductReviewData(
        array $productViewTransfers
    ): array {
        $productsReviews = $this->productReviewSearchReader->searchProductReviews();

        if (!isset($productsReviews[static::KEY_PRODUCT_BULK_AGGREGATION])) {
            return $productViewTransfers;
        }

        foreach ($productsReviews[static::KEY_PRODUCT_BULK_AGGREGATION] as $idProductAbstract => $productReviews) {
            if (empty($productReviews[static::KEY_RATING_AGGREGATION])) {
                continue;
            }

            $filteredProductViewTransfers = $this->filterProductViewTransfersByIdProductAbstract($productViewTransfers, $idProductAbstract);
            if (!count($filteredProductViewTransfers)) {
                continue;
            }

            $productReviewSummaryTransfer = $this->productReviewSummaryCalculator->calculate(
                $this->createRatingAggregationTransfer($productReviews),
            );

            $this->expandProductViewTransfersWithRating(
                $filteredProductViewTransfers,
                $productReviewSummaryTransfer,
            );
        }

        return $productViewTransfers;
    }

    /**
     * @param array<int, \Generated\Shared\Transfer\ProductViewTransfer> $productViewTransfers
     * @param int $idProductAbstract
     *
     * @return array<int, \Generated\Shared\Transfer\ProductViewTransfer>
     */
    protected function filterProductViewTransfersByIdProductAbstract(array $productViewTransfers, int $idProductAbstract): array
    {
        /** @var array<int, \Generated\Shared\Transfer\ProductViewTransfer> $filteredProductViewTransfers */
        $filteredProductViewTransfers = array_filter($productViewTransfers, function (ProductViewTransfer $productViewTransfer) use ($idProductAbstract) {
            return $productViewTransfer->getIdProductAbstract() === $idProductAbstract;
        });

        if (count($filteredProductViewTransfers)) {
            return $filteredProductViewTransfers;
        }

        return isset($productViewTransfers[$idProductAbstract]) ? [$productViewTransfers[$idProductAbstract]] : [];
    }

    /**
     * @param array<int, \Generated\Shared\Transfer\ProductViewTransfer> $productViewTransfers
     * @param \Generated\Shared\Transfer\ProductReviewSummaryTransfer $productReviewSummaryTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\ProductViewTransfer>
     */
    protected function expandProductViewTransfersWithRating(
        array $productViewTransfers,
        ProductReviewSummaryTransfer $productReviewSummaryTransfer
    ): array {
        foreach ($productViewTransfers as $productViewTransfer) {
            $productViewTransfer->setRating($productReviewSummaryTransfer);
        }

        return $productViewTransfers;
    }
}
