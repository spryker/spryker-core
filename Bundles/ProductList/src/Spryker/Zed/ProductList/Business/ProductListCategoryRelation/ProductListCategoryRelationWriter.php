<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business\ProductListCategoryRelation;

use Generated\Shared\Transfer\ProductListCategoryRelationTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductList\Persistence\ProductListEntityManagerInterface;

class ProductListCategoryRelationWriter implements ProductListCategoryRelationWriterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ProductList\Persistence\ProductListEntityManagerInterface
     */
    protected $productListEntityManager;

    /**
     * @var \Spryker\Zed\ProductList\Business\ProductListCategoryRelation\ProductListCategoryRelationReaderInterface
     */
    protected $productListCategoryRelationReader;

    /**
     * @param \Spryker\Zed\ProductList\Persistence\ProductListEntityManagerInterface $productListEntityManager
     * @param \Spryker\Zed\ProductList\Business\ProductListCategoryRelation\ProductListCategoryRelationReaderInterface $productListCategoryRelationReader
     */
    public function __construct(
        ProductListEntityManagerInterface $productListEntityManager,
        ProductListCategoryRelationReaderInterface $productListCategoryRelationReader
    ) {
        $this->productListEntityManager = $productListEntityManager;
        $this->productListCategoryRelationReader = $productListCategoryRelationReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListCategoryRelationTransfer $productListCategoryRelationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListCategoryRelationTransfer
     */
    public function saveProductListCategoryRelation(
        ProductListCategoryRelationTransfer $productListCategoryRelationTransfer
    ): ProductListCategoryRelationTransfer {
        return $this->getTransactionHandler()->handleTransaction(function () use ($productListCategoryRelationTransfer) {
            return $this->executeSaveProductListCategoryRelationTransaction($productListCategoryRelationTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListCategoryRelationTransfer $productListCategoryRelationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListCategoryRelationTransfer
     */
    protected function executeSaveProductListCategoryRelationTransaction(ProductListCategoryRelationTransfer $productListCategoryRelationTransfer): ProductListCategoryRelationTransfer
    {
        $productListCategoryRelationTransfer->requireIdProductList();
        $idProductList = $productListCategoryRelationTransfer->getIdProductList();

        $requestedCategoryIds = $this->getRequestedCategoryIds($productListCategoryRelationTransfer);
        $relatedCategoryIds = $this->getRelatedCategoryIds($productListCategoryRelationTransfer);

        $saveCategoryIds = array_diff($requestedCategoryIds, $relatedCategoryIds);
        $deleteCategoryIds = array_diff($relatedCategoryIds, $requestedCategoryIds);

        $this->productListEntityManager->addCategoryRelations($idProductList, $saveCategoryIds);
        $this->productListEntityManager->removeCategoryRelations($idProductList, $deleteCategoryIds);

        return $productListCategoryRelationTransfer->setCategoryIds(
            $this->getRelatedCategoryIds($productListCategoryRelationTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListCategoryRelationTransfer $productListCategoryRelationTransfer
     *
     * @return int[]
     */
    protected function getRelatedCategoryIds(
        ProductListCategoryRelationTransfer $productListCategoryRelationTransfer
    ): array {
        $currentProductListCategoryRelationTransfer = $this->productListCategoryRelationReader->getProductListCategoryRelation($productListCategoryRelationTransfer);

        if (!$currentProductListCategoryRelationTransfer->getCategoryIds()) {
            return [];
        }

        return $currentProductListCategoryRelationTransfer->getCategoryIds();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListCategoryRelationTransfer $productListCategoryRelationTransfer
     *
     * @return int[]
     */
    protected function getRequestedCategoryIds(
        ProductListCategoryRelationTransfer $productListCategoryRelationTransfer
    ): array {
        if (!$productListCategoryRelationTransfer->getCategoryIds()) {
            return [];
        }

        return $productListCategoryRelationTransfer->getCategoryIds();
    }
}
