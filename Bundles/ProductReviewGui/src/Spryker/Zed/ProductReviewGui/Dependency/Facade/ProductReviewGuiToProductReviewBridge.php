<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewGui\Dependency\Facade;

use Generated\Shared\Transfer\ProductReviewTransfer;

class ProductReviewGuiToProductReviewBridge implements ProductReviewGuiToProductReviewInterface
{
    /**
     * @var \Spryker\Zed\ProductReview\Business\ProductReviewFacadeInterface
     */
    protected $productReviewFacade;

    /**
     * @param \Spryker\Zed\ProductReview\Business\ProductReviewFacadeInterface $productReviewFacade
     */
    public function __construct($productReviewFacade)
    {
        $this->productReviewFacade = $productReviewFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductReviewTransfer|null
     */
    public function findProductReview(ProductReviewTransfer $productReviewTransfer)
    {
        return $this->productReviewFacade->findProductReview($productReviewTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductReviewTransfer
     */
    public function updateProductReviewStatus(ProductReviewTransfer $productReviewTransfer)
    {
        return $this->productReviewFacade->updateProductReviewStatus($productReviewTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return void
     */
    public function deleteProductReview(ProductReviewTransfer $productReviewTransfer)
    {
        $this->productReviewFacade->deleteProductReview($productReviewTransfer);
    }
}
