<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReview\Business\Model\Touch;

use Generated\Shared\Transfer\ProductReviewTransfer;
use Spryker\Shared\ProductReview\ProductReviewConfig;
use Spryker\Zed\ProductReview\Dependency\Facade\ProductReviewToTouchInterface;

class ProductReviewTouch implements ProductReviewTouchInterface
{

    /**
     * @var \Spryker\Zed\ProductReview\Dependency\Facade\ProductReviewToTouchInterface
     */
    protected $touchFacade;

    /**
     * @param \Spryker\Zed\ProductReview\Dependency\Facade\ProductReviewToTouchInterface $touchFacade
     */
    public function __construct(ProductReviewToTouchInterface $touchFacade)
    {
        $this->touchFacade = $touchFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return bool
     */
    public function touchProductReviewActive(ProductReviewTransfer $productReviewTransfer)
    {
        // TODO: use ProductFacade::touchProductAbstract() here.
        $this->assertProductReviewForTouch($productReviewTransfer);

        return $this->touchFacade->touchActive(ProductReviewConfig::RESOURCE_TYPE_PRODUCT_REVIEW, $productReviewTransfer->getIdProductReview());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return bool
     */
    public function touchProductReviewDeleted(ProductReviewTransfer $productReviewTransfer)
    {
        // TODO: use ProductFacade::touchProductAbstract() here.
        $this->assertProductReviewForTouch($productReviewTransfer);

        return $this->touchFacade->touchDeleted(ProductReviewConfig::RESOURCE_TYPE_PRODUCT_REVIEW, $productReviewTransfer->getIdProductReview());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return void
     */
    protected function assertProductReviewForTouch(ProductReviewTransfer $productReviewTransfer)
    {
        $productReviewTransfer->requireIdProductReview();
    }

}
