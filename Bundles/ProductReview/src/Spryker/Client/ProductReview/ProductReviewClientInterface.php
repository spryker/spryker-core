<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview;

use Generated\Shared\Transfer\BulkProductReviewSearchRequestTransfer;
use Generated\Shared\Transfer\ProductReviewRequestTransfer;
use Generated\Shared\Transfer\ProductReviewSearchRequestTransfer;
use Generated\Shared\Transfer\ProductReviewSummaryTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\RatingAggregationTransfer;

interface ProductReviewClientInterface
{
    /**
     * Specification:
     * - Stores provided product review in persistent storage with pending status.
     * - Returns the provided transfer object updated with the stored entity's data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductReviewRequestTransfer $productReviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductReviewResponseTransfer
     */
    public function submitCustomerReview(ProductReviewRequestTransfer $productReviewRequestTransfer);

    /**
     * Specification:
     * - Retrieves provided product abstract related product reviews from Search.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductReviewSearchRequestTransfer $productReviewSearchRequestTransfer
     *
     * @return array
     */
    public function findProductReviewsInSearch(ProductReviewSearchRequestTransfer $productReviewSearchRequestTransfer);

    /**
     * Specification:
     * - Retrieves provided products abstract related product reviews from Search.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\BulkProductReviewSearchRequestTransfer $bulkProductReviewSearchRequestTransfer
     *
     * @return array
     */
    public function getBulkProductReviewsFromSearch(BulkProductReviewSearchRequestTransfer $bulkProductReviewSearchRequestTransfer): array;

    /**
     * Specification:
     * - Retrieves provided product abstract and locale related review details from Storage.
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractReviewTransfer|null
     */
    public function findProductAbstractReviewInStorage($idProductAbstract, $localeName);

    /**
     * Specification:
     * - Retrieves the available maximum rating value
     *
     * @api
     *
     * @return int
     */
    public function getMaximumRating();

    /**
     * Specification:
     *  - Expands product view data with product review summary data (average rating).
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param \Generated\Shared\Transfer\ProductReviewSearchRequestTransfer $productReviewSearchRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewWithProductReviewData(
        ProductViewTransfer $productViewTransfer,
        ProductReviewSearchRequestTransfer $productReviewSearchRequestTransfer
    ): ProductViewTransfer;

    /**
     * Specification:
     * - Calculates the product review rating aggregation value.
     * - Calculates the product review average rating value.
     * - Calculates the product total review value.
     * - Provides the product review available maximum rating value.
     * - Requires `ProductReviewSummaryTransfer.ratingAggregation` property to calculate product review summary.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RatingAggregationTransfer $ratingAggregationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductReviewSummaryTransfer
     */
    public function calculateProductReviewSummary(RatingAggregationTransfer $ratingAggregationTransfer): ProductReviewSummaryTransfer;
}
