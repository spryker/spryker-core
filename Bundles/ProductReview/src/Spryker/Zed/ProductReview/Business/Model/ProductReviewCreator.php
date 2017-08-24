<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReview\Business\Model;

use Generated\Shared\Transfer\ProductReviewTransfer;
use Orm\Zed\ProductReview\Persistence\Map\SpyProductReviewTableMap;
use Orm\Zed\ProductReview\Persistence\SpyProductReview;
use Spryker\Client\ProductReview\ProductReviewClientInterface;
use Spryker\Shared\ProductReview\Exception\RatingOutOfRangeException;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class ProductReviewCreator implements ProductReviewCreatorInterface
{

    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Client\ProductReview\ProductReviewClientInterface
     */
    protected $productReviewClient;

    /**
     * @param \Spryker\Client\ProductReview\ProductReviewClientInterface $productReviewClient
     */
    public function __construct(ProductReviewClientInterface $productReviewClient)
    {
        $this->productReviewClient = $productReviewClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductReviewTransfer
     */
    public function createProductReview(ProductReviewTransfer $productReviewTransfer)
    {
        $this->assertRatingRange($productReviewTransfer);

        return $this->handleDatabaseTransaction(function () use ($productReviewTransfer) {
            return $this->executeCreateProductReviewTransaction($productReviewTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductReviewTransfer
     */
    protected function executeCreateProductReviewTransaction($productReviewTransfer)
    {
        $productReviewEntity = $this->createProductReviewEntity($productReviewTransfer);

        $this->mapEntityToTransfer($productReviewTransfer, $productReviewEntity);

        return $productReviewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return \Orm\Zed\ProductReview\Persistence\SpyProductReview
     */
    protected function createProductReviewEntity(ProductReviewTransfer $productReviewTransfer)
    {
        $productReviewEntity = new SpyProductReview();
        $productReviewEntity->fromArray($productReviewTransfer->toArray());
        $productReviewEntity = $this->setInitialStatus($productReviewEntity);

        $productReviewEntity->save();

        return $productReviewEntity;
    }

    /**
     * @param \Orm\Zed\ProductReview\Persistence\SpyProductReview $productReviewEntity
     *
     * @return \Orm\Zed\ProductReview\Persistence\SpyProductReview
     */
    protected function setInitialStatus(SpyProductReview $productReviewEntity)
    {
        $productReviewEntity->setStatus(SpyProductReviewTableMap::COL_STATUS_PENDING);

        return $productReviewEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     * @param \Orm\Zed\ProductReview\Persistence\SpyProductReview $productReviewEntity
     *
     * @return \Generated\Shared\Transfer\ProductReviewTransfer
     */
    protected function mapEntityToTransfer(ProductReviewTransfer $productReviewTransfer, SpyProductReview $productReviewEntity)
    {
        $productReviewTransfer->fromArray($productReviewEntity->toArray(), true);

        return $productReviewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @throws \Spryker\Shared\ProductReview\Exception\RatingOutOfRangeException
     *
     * @return void
     */
    protected function assertRatingRange(ProductReviewTransfer $productReviewTransfer)
    {
        if ($productReviewTransfer->getRating() > $this->productReviewClient->getMaximumRating()) {
            throw new RatingOutOfRangeException(
                'Field Rating exceeds limit %d',
                $this->productReviewClient->getMaximumRating()
            );
        }
    }

}
