<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReview\Business\Model;

use Exception;
use Generated\Shared\Transfer\ProductReviewTransfer;
use Spryker\Zed\ProductReview\Persistence\ProductReviewQueryContainerInterface;

class ProductReviewEntityReader implements ProductReviewEntityReaderInterface
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
     * @throws \Exception
     *
     * @return \Orm\Zed\ProductReview\Persistence\SpyProductReview
     */
    public function getProductReviewEntity(ProductReviewTransfer $productReviewTransfer)
    {
        $this->assertProductReviewForRead($productReviewTransfer);

        $productReviewEntity = $this->productReviewQueryContainer
            ->queryProductReviewById($productReviewTransfer->getIdProductReview())
            ->findOne();

        if (!$productReviewEntity) {
            throw new Exception('Invalid product review'); // TODO: fix exception
        }

        return $productReviewEntity;
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

}
