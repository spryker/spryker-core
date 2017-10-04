<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReview\Business\Model;

use Generated\Shared\Transfer\ProductReviewTransfer;
use Spryker\Zed\ProductReview\Business\Model\Touch\ProductReviewTouchInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class ProductReviewDeleter implements ProductReviewDeleterInterface
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
     * @return void
     */
    public function deleteProductReview(ProductReviewTransfer $productReviewTransfer)
    {
        $this->handleDatabaseTransaction(function () use ($productReviewTransfer) {
            $this->executeDeleteProductReviewTransaction($productReviewTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return void
     */
    protected function executeDeleteProductReviewTransaction(ProductReviewTransfer $productReviewTransfer)
    {
        $productReviewEntity = $this->productReviewEntityReader->getProductReviewEntity($productReviewTransfer);
        $productReviewTransfer->fromArray($productReviewEntity->toArray());

        $productReviewEntity->delete();

        $this->touchProductReview($productReviewTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return void
     */
    protected function touchProductReview(ProductReviewTransfer $productReviewTransfer)
    {
        $this->productReviewTouch->touchProductReviewDeleted($productReviewTransfer);
    }

}
