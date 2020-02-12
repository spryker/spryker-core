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
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductReview\ProductReviewFactory getFactory()
 */
class ProductReviewClient extends AbstractClient implements ProductReviewClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductReviewRequestTransfer $productReviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductReviewResponseTransfer
     */
    public function submitCustomerReview(ProductReviewRequestTransfer $productReviewRequestTransfer)
    {
        return $this->getFactory()
            ->createProductReviewStub()
            ->submitCustomerReview($productReviewRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductReviewSearchRequestTransfer $productReviewSearchRequestTransfer
     *
     * @return array
     */
    public function findProductReviewsInSearch(ProductReviewSearchRequestTransfer $productReviewSearchRequestTransfer)
    {
        return $this->getFactory()
            ->createProductReviewSearchReader($productReviewSearchRequestTransfer)
            ->findProductReviews($productReviewSearchRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\BulkProductReviewSearchRequestTransfer $bulkProductReviewSearchRequestTransfer
     *
     * @return array
     */
    public function getBulkProductReviewsFromSearch(BulkProductReviewSearchRequestTransfer $bulkProductReviewSearchRequestTransfer): array
    {
        $searchQuery = $this->getFactory()->createBulkProductReviewsQueryPlugin($bulkProductReviewSearchRequestTransfer);
        $resultFormatters = $this->getFactory()->getProductReviewsSearchResultFormatterPlugins();

        return $this->getFactory()
            ->getSearchClient()
            ->search($searchQuery, $resultFormatters, $bulkProductReviewSearchRequestTransfer->getFilter()->toArray());
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractReviewTransfer|null
     */
    public function findProductAbstractReviewInStorage($idProductAbstract, $localeName)
    {
        return $this->getFactory()
            ->createProductAbstractReviewStorageReader()
            ->findProductAbstractReview($idProductAbstract, $localeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return int
     */
    public function getMaximumRating()
    {
        return $this->getFactory()->getProductReviewConfig()->getMaximumRating();
    }

    /**
     * {@inheritDoc}
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
    ): ProductViewTransfer {
        return $this->getFactory()
            ->createProductViewExpander($productReviewSearchRequestTransfer)
            ->expandProductViewWithProductReviewData($productViewTransfer, $productReviewSearchRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RatingAggregationTransfer $ratingAggregationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductReviewSummaryTransfer
     */
    public function calculateProductReviewSummary(RatingAggregationTransfer $ratingAggregationTransfer): ProductReviewSummaryTransfer
    {
        return $this->getFactory()
            ->createProductReviewSummaryCalculator()
            ->calculate($ratingAggregationTransfer);
    }
}
