<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReview\Business\Model;

use Generated\Shared\Transfer\ProductReviewTransfer;
use Orm\Zed\ProductReview\Persistence\Base\SpyProductReview;
use Spryker\Zed\ProductReview\Business\Model\Touch\ProductReviewTouchInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class ProductReviewStatusUpdater implements ProductReviewStatusUpdaterInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\ProductReview\Business\Model\ProductReviewEntityReaderInterface
     */
    protected $productReviewEntityReader;

    /**
     * @var \Spryker\Zed\ProductReview\Business\Model\Touch\ProductReviewTouchInterface
     */
    protected $productReviewTouch;

    /**
     * @param \Spryker\Zed\ProductReview\Business\Model\ProductReviewEntityReaderInterface $productReviewEntityReader
     * @param \Spryker\Zed\ProductReview\Business\Model\Touch\ProductReviewTouchInterface $productReviewTouch
     */
    public function __construct(ProductReviewEntityReaderInterface $productReviewEntityReader, ProductReviewTouchInterface $productReviewTouch)
    {
        $this->productReviewEntityReader = $productReviewEntityReader;
        $this->productReviewTouch = $productReviewTouch;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductReviewTransfer
     */
    public function updateProductReviewStatus(ProductReviewTransfer $productReviewTransfer)
    {
        $this->assertProductReviewTransfer($productReviewTransfer);

        return $this->handleDatabaseTransaction(function () use ($productReviewTransfer) {
            return $this->executeUpdateProductReviewStatusTransaction($productReviewTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductReviewTransfer
     */
    protected function executeUpdateProductReviewStatusTransaction(ProductReviewTransfer $productReviewTransfer)
    {
        $productReviewEntity = $this->productReviewEntityReader->getProductReviewEntity($productReviewTransfer);

        $this->mapTransferToEntity($productReviewTransfer, $productReviewEntity);
        $productReviewEntity->save();
        $this->mapEntityToTransfer($productReviewTransfer, $productReviewEntity);

        $this->touchProductReviewEntity($productReviewTransfer);

        return $productReviewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return void
     */
    protected function touchProductReviewEntity(ProductReviewTransfer $productReviewTransfer)
    {
        $this->productReviewTouch->touchProductReviewByStatus($productReviewTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     * @param \Orm\Zed\ProductReview\Persistence\Base\SpyProductReview $productReviewEntity
     *
     * @return \Orm\Zed\ProductReview\Persistence\Base\SpyProductReview
     */
    protected function mapTransferToEntity(ProductReviewTransfer $productReviewTransfer, SpyProductReview $productReviewEntity)
    {
        /** @var string $status */
        $status = $productReviewTransfer->getStatus();

        $productReviewEntity->setStatus($status);

        return $productReviewEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     * @param \Orm\Zed\ProductReview\Persistence\Base\SpyProductReview $productReviewEntity
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
     * @return void
     */
    protected function assertProductReviewTransfer(ProductReviewTransfer $productReviewTransfer)
    {
        $productReviewTransfer->requireIdProductReview();
        $productReviewTransfer->requireStatus();
    }
}
