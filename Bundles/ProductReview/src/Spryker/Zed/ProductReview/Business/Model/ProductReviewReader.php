<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReview\Business\Model;

use Generated\Shared\Transfer\ProductReviewTransfer;
use Orm\Zed\ProductReview\Persistence\SpyProductReview;
use Spryker\Zed\ProductReview\Persistence\ProductReviewQueryContainerInterface;

class ProductReviewReader implements ProductReviewReaderInterface
{

    /**
     * @var \Spryker\Zed\ProductReview\Persistence\ProductReviewQueryContainerInterface
     */
    protected $productReviewQueryContainer;

    /**
     * @param \Spryker\Zed\ProductReview\Persistence\ProductReviewQueryContainerInterface $productReviewQueryContainer
     */
    public function __construct(ProductReviewQueryContainerInterface $productReviewQueryContainer)
    {
        $this->productReviewQueryContainer = $productReviewQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductReviewTransfer|null
     */
    public function findProductReview(ProductReviewTransfer $productReviewTransfer)
    {
        $this->assertProductReviewForRead($productReviewTransfer);

        $productReviewEntity = $this->findProductReviewEntity($productReviewTransfer);

        if (!$productReviewEntity) {
            return null;
        }

        $productReviewTransfer = $this->mapEntityToTransfer($productReviewEntity);

        return $productReviewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return void
     */
    protected function assertProductReviewForRead(ProductReviewTransfer $productReviewTransfer)
    {
        $productReviewTransfer->requireIdProductReview();
    }

    /**
     * @param \Orm\Zed\ProductReview\Persistence\SpyProductReview $productReviewEntity
     *
     * @return \Generated\Shared\Transfer\ProductReviewTransfer
     */
    protected function mapEntityToTransfer(SpyProductReview $productReviewEntity)
    {
        $productReviewTransfer = new ProductReviewTransfer();
        $productReviewTransfer->fromArray($productReviewEntity->toArray(), true);

        return $productReviewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return \Orm\Zed\ProductReview\Persistence\SpyProductReview|null
     */
    protected function findProductReviewEntity(ProductReviewTransfer $productReviewTransfer)
    {
        return $this->productReviewQueryContainer
            ->queryProductReviewById($productReviewTransfer->getIdProductReview())
            ->findOne();
    }

}
