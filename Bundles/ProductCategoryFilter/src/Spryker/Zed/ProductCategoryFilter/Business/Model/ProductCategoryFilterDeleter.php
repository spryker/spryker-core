<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilter\Business\Model;

use Generated\Shared\Transfer\ProductCategoryFilterTransfer;
use Spryker\Zed\ProductCategoryFilter\Persistence\ProductCategoryFilterQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class ProductCategoryFilterDeleter implements ProductCategoryFilterDeleterInterface
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
     * @param \Spryker\Zed\ProductCategoryFilter\Persistence\ProductCategoryFilterQueryContainerInterface $productCategoryFilterQueryContainer
     * @param \Spryker\Zed\ProductCategoryFilter\Business\Model\ProductCategoryFilterTouchInterface $productCategoryFilterTouch
     */
    public function __construct(ProductCategoryFilterQueryContainerInterface $productCategoryFilterQueryContainer, ProductCategoryFilterTouchInterface $productCategoryFilterTouch)
    {
        $this->productCategoryFilterQueryContainer = $productCategoryFilterQueryContainer;
        $this->productCategoryFilterTouch = $productCategoryFilterTouch;
    }

    /**
     * @param int $categoryId
     *
     * @return void
     */
    public function deleteProductCategoryFilterByCategoryId($categoryId)
    {
        $this->handleDatabaseTransaction(function () use ($categoryId) {
            $this->executeDeleteProductCategoryFilterTransaction($categoryId);
        });
    }

    /**
     * @param int $categoryId
     *
     * @return void
     */
    protected function executeDeleteProductCategoryFilterTransaction($categoryId)
    {
        $productCategoryFilterEntity = $this->deleteProductCategoryFilterEntity($categoryId);
        $this->productCategoryFilterTouch->touchProductCategoryFilterDeleted(
            (new ProductCategoryFilterTransfer())->fromArray($productCategoryFilterEntity->toArray(), true)
        );
    }

    /**
     * @param int $categoryId
     *
     * @return \Orm\Zed\ProductCategoryFilter\Persistence\SpyProductCategoryFilter
     */
    protected function deleteProductCategoryFilterEntity($categoryId)
    {
        $productCategoryFilterEntity = $this->getProductCategoryFilterEntityByCategoryId($categoryId);
        $productCategoryFilterEntity->delete();

        return $productCategoryFilterEntity;
    }

    /**
     * @param int $categoryId
     *
     * @return \Orm\Zed\ProductCategoryFilter\Persistence\SpyProductCategoryFilter|null
     */
    protected function getProductCategoryFilterEntityByCategoryId($categoryId)
    {
        return $this->productCategoryFilterQueryContainer
            ->queryProductCategoryFilterByCategoryId($categoryId)
            ->findOne();
    }
}
