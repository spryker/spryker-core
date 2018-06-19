<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilter\Business\Model;

use Generated\Shared\Transfer\ProductCategoryFilterTransfer;
use Spryker\Zed\ProductCategoryFilter\Persistence\ProductCategoryFilterQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class ProductCategoryFilterUpdater implements ProductCategoryFilterUpdaterInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\ProductCategoryFilter\Business\Model\ProductCategoryFilterTouchInterface
     */
    protected $productCategoryFilterTouch;

    /**
     * @var \Spryker\Zed\ProductCategoryFilter\Persistence\ProductCategoryFilterQueryContainerInterface
     */
    protected $productCategoryFilterQueryContainer;

    /**
     * @var \Spryker\Zed\ProductCategoryFilter\Business\Model\ProductCategoryFilterTransferGenerator
     */
    protected $productCategoryFilterTransferGenerator;

    /**
     * @param \Spryker\Zed\ProductCategoryFilter\Persistence\ProductCategoryFilterQueryContainerInterface $productCategoryFilterQueryContainer
     * @param \Spryker\Zed\ProductCategoryFilter\Business\Model\ProductCategoryFilterTouchInterface $productCategoryFilterTouch
     * @param \Spryker\Zed\ProductCategoryFilter\Business\Model\ProductCategoryFilterTransferGenerator $productCategoryFilterTransferGenerator
     */
    public function __construct(ProductCategoryFilterQueryContainerInterface $productCategoryFilterQueryContainer, ProductCategoryFilterTouchInterface $productCategoryFilterTouch, ProductCategoryFilterTransferGenerator $productCategoryFilterTransferGenerator)
    {
        $this->productCategoryFilterQueryContainer = $productCategoryFilterQueryContainer;
        $this->productCategoryFilterTouch = $productCategoryFilterTouch;
        $this->productCategoryFilterTransferGenerator = $productCategoryFilterTransferGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductCategoryFilterTransfer $productCategoryFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterTransfer
     */
    public function updateProductCategoryFilter(ProductCategoryFilterTransfer $productCategoryFilterTransfer)
    {
        return $this->handleDatabaseTransaction(function () use ($productCategoryFilterTransfer) {
            return $this->executeUpdateProductCategoryFilterTransaction($productCategoryFilterTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductCategoryFilterTransfer $productCategoryFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterTransfer
     */
    protected function executeUpdateProductCategoryFilterTransaction(ProductCategoryFilterTransfer $productCategoryFilterTransfer)
    {
        $productCategoryFilterTransfer = $this->productCategoryFilterTransferGenerator->generateTransferWithJsonFromTransfer($productCategoryFilterTransfer);
        $this->updateProductCategoryFilterEntity($productCategoryFilterTransfer);

        $this->productCategoryFilterTouch->touchProductCategoryFilterActive($productCategoryFilterTransfer);

        return $productCategoryFilterTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductCategoryFilterTransfer $productCategoryFilterTransfer
     *
     * @return void
     */
    protected function updateProductCategoryFilterEntity(ProductCategoryFilterTransfer $productCategoryFilterTransfer)
    {
        $productCategoryFilterEntity = $this->getProductCategoryFilterEntityByCategoryId($productCategoryFilterTransfer->getFkCategory());

        $productCategoryFilterEntity->fromArray($productCategoryFilterTransfer->modifiedToArray());
        $productCategoryFilterEntity->save();
    }

    /**
     * @param int $categoryId
     *
     * @return \Orm\Zed\ProductCategoryFilter\Persistence\SpyProductCategoryFilter
     */
    protected function getProductCategoryFilterEntityByCategoryId($categoryId)
    {
        return $this->productCategoryFilterQueryContainer
            ->queryProductCategoryFilterByCategoryId($categoryId)
            ->findOne();
    }
}
