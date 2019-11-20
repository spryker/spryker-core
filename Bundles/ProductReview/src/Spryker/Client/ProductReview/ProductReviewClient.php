<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview;

use Generated\Shared\Transfer\ProductReviewRequestTransfer;
use Generated\Shared\Transfer\ProductReviewSearchRequestTransfer;
use Generated\Shared\Transfer\ProductReviewSummaryTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
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
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewWithProductReviewData(ProductViewTransfer $productViewTransfer): ProductViewTransfer
    {
        return $this->getFactory()
            ->createProductViewExpander($productViewTransfer->getIdProductAbstract())
            ->expandProductViewWithProductReviewData($productViewTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $ratingAggregation
     *
     * @return \Generated\Shared\Transfer\ProductReviewSummaryTransfer
     */
    public function calculateProductReviewSummary(array $ratingAggregation): ProductReviewSummaryTransfer
    {
        return $this->getFactory()
            ->createProductReviewSummaryCalculator()
            ->execute($ratingAggregation);
    }
}
