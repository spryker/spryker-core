<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReview\Business;

use Generated\Shared\Transfer\AddReviewsTransfer;
use Generated\Shared\Transfer\ProductReviewTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductReview\Business\ProductReviewBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductReview\Persistence\ProductReviewRepositoryInterface getRepository()
 */
class ProductReviewFacade extends AbstractFacade implements ProductReviewFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductReviewTransfer
     */
    public function createProductReview(ProductReviewTransfer $productReviewTransfer)
    {
        return $this->getFactory()
            ->createProductReviewCreator()
            ->createProductReview($productReviewTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductReviewTransfer|null
     */
    public function findProductReview(ProductReviewTransfer $productReviewTransfer)
    {
        return $this->getFactory()
            ->createProductReviewReader()
            ->findProductReview($productReviewTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductReviewTransfer
     */
    public function updateProductReviewStatus(ProductReviewTransfer $productReviewTransfer)
    {
        return $this->getFactory()
            ->createProductReviewStatusUpdater()
            ->updateProductReviewStatus($productReviewTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return void
     */
    public function deleteProductReview(ProductReviewTransfer $productReviewTransfer)
    {
        $this->getFactory()
            ->createProductReviewDeleter()
            ->deleteProductReview($productReviewTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function expandProductConcretesWithRating(array $productConcreteTransfers): array
    {
        return $this->getFactory()
            ->createProductConcreteRatingExpander()
            ->expandProductConcretesWithRating($productConcreteTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AddReviewsTransfer $addReviewsTransfer
     *
     * @return void
     */
    public function handleAddReviews(AddReviewsTransfer $addReviewsTransfer): void
    {
        $this->getFactory()->createProductReviewMessageHandler()->handleAddReviews($addReviewsTransfer);
    }
}
