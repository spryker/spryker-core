<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReview\Business\Model\Touch;

use Generated\Shared\Transfer\ProductReviewTransfer;
use Orm\Zed\ProductReview\Persistence\Map\SpyProductReviewTableMap;
use Spryker\Shared\ProductReview\ProductReviewConfig;
use Spryker\Zed\ProductReview\Dependency\Facade\ProductReviewToProductInterface;
use Spryker\Zed\ProductReview\Dependency\Facade\ProductReviewToTouchInterface;

class ProductReviewTouch implements ProductReviewTouchInterface
{
    /**
     * @var \Spryker\Zed\ProductReview\Dependency\Facade\ProductReviewToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\ProductReview\Dependency\Facade\ProductReviewToProductInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductReview\Dependency\Facade\ProductReviewToTouchInterface $touchFacade
     * @param \Spryker\Zed\ProductReview\Dependency\Facade\ProductReviewToProductInterface $productFacade
     */
    public function __construct(ProductReviewToTouchInterface $touchFacade, ProductReviewToProductInterface $productFacade)
    {
        $this->touchFacade = $touchFacade;
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return void
     */
    public function touchProductReviewByStatus(ProductReviewTransfer $productReviewTransfer)
    {
        $this->assertProductReviewForTouchByStatus($productReviewTransfer);

        switch ($productReviewTransfer->getStatus()) {
            case SpyProductReviewTableMap::COL_STATUS_PENDING:
            case SpyProductReviewTableMap::COL_STATUS_REJECTED:
                $this->touchProductReviewDeleted($productReviewTransfer);

                break;
            case SpyProductReviewTableMap::COL_STATUS_APPROVED:
                $this->touchProductReviewActive($productReviewTransfer);

                break;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return void
     */
    public function touchProductReviewActive(ProductReviewTransfer $productReviewTransfer)
    {
        $this->assertProductReviewForTouch($productReviewTransfer);

        $this->touchFacade->touchActive(ProductReviewConfig::RESOURCE_TYPE_PRODUCT_REVIEW, $productReviewTransfer->getIdProductReview());
        $this->touchFacade->touchActive(ProductReviewConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT_REVIEW, $productReviewTransfer->getFkProductAbstract());
        $this->productFacade->touchProductAbstract($productReviewTransfer->getFkProductAbstract());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return void
     */
    public function touchProductReviewDeleted(ProductReviewTransfer $productReviewTransfer)
    {
        $this->assertProductReviewForTouch($productReviewTransfer);

        $this->touchFacade->touchDeleted(ProductReviewConfig::RESOURCE_TYPE_PRODUCT_REVIEW, $productReviewTransfer->getIdProductReview());
        $this->touchFacade->touchActive(ProductReviewConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT_REVIEW, $productReviewTransfer->getFkProductAbstract());
        $this->productFacade->touchProductAbstract($productReviewTransfer->getFkProductAbstract());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return void
     */
    protected function assertProductReviewForTouchByStatus(ProductReviewTransfer $productReviewTransfer)
    {
        $productReviewTransfer->requireStatus();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return void
     */
    protected function assertProductReviewForTouch(ProductReviewTransfer $productReviewTransfer)
    {
        $productReviewTransfer
            ->requireIdProductReview()
            ->requireFkProductAbstract();
    }
}
