<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business\Model;

use Generated\Shared\Transfer\ProductListCategoryRelationTransfer;
use Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductList\Persistence\ProductListEntityManagerInterface;

class ProductListWriter implements ProductListWriterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ProductList\Persistence\ProductListEntityManagerInterface
     */
    protected $productListEntityManager;

    /**
     * @var \Spryker\Zed\ProductList\Business\Model\ProductListCategoryRelationWriterInterface
     */
    protected $productListCategoryRelationWriter;

    /**
     * @var \Spryker\Zed\ProductList\Business\Model\ProductListProductConcreteRelationWriterInterface
     */
    protected $productListProductConcreteRelationWriter;

    /**
     * @param \Spryker\Zed\ProductList\Persistence\ProductListEntityManagerInterface $productListEntityManager
     * @param \Spryker\Zed\ProductList\Business\Model\ProductListCategoryRelationWriterInterface $productListCategoryRelationWriter
     * @param \Spryker\Zed\ProductList\Business\Model\ProductListProductConcreteRelationWriterInterface $productListProductConcreteRelationWriter
     */
    public function __construct(
        ProductListEntityManagerInterface $productListEntityManager,
        ProductListCategoryRelationWriterInterface $productListCategoryRelationWriter,
        ProductListProductConcreteRelationWriterInterface $productListProductConcreteRelationWriter
    ) {
        $this->productListEntityManager = $productListEntityManager;
        $this->productListCategoryRelationWriter = $productListCategoryRelationWriter;
        $this->productListProductConcreteRelationWriter = $productListProductConcreteRelationWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function saveProductList(ProductListTransfer $productListTransfer): ProductListTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($productListTransfer) {
            return $this->executeSaveProductListTransaction($productListTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return void
     */
    public function deleteProductList(ProductListTransfer $productListTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($productListTransfer) {
            $this->executeDeleteProductListTransaction($productListTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    protected function executeSaveProductListTransaction(
        ProductListTransfer $productListTransfer
    ): ProductListTransfer {
        $productListCategoryRelationTransfer = $productListTransfer->getProductListCategoryRelation();
        $productListProductConcreteRelationTransfer = $productListTransfer->getProductListProductConcreteRelation();
        $productListTransfer = $this->generateKey($productListTransfer);

        $productListTransfer = $this->productListEntityManager->saveProductList($productListTransfer);

        $this->saveProductListProductConcreteRelation(
            $productListTransfer,
            $productListProductConcreteRelationTransfer
        );

        $this->saveProductListCategoryRelation(
            $productListTransfer,
            $productListCategoryRelationTransfer
        );

        return $productListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return void
     */
    protected function executeDeleteProductListTransaction(
        ProductListTransfer $productListTransfer
    ): void {
        $this->productListEntityManager->deleteProductList($productListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    protected function generateKey(ProductListTransfer $productListTransfer): ProductListTransfer
    {
        if ($productListTransfer->getIdProductList()) {
            return $productListTransfer;
        }
        if ($productListTransfer->getKey()) {
            return $productListTransfer;
        }

        $key = uniqid('spy-product-list-');
        $productListTransfer->setKey(md5($key));

        return $productListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     * @param \Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer|null $productListProductConcreteRelationTransfer
     *
     * @return void
     */
    protected function saveProductListProductConcreteRelation(
        ProductListTransfer $productListTransfer,
        ?ProductListProductConcreteRelationTransfer $productListProductConcreteRelationTransfer
    ): void {
        if (!$productListProductConcreteRelationTransfer) {
            return;
        }

        $productListProductConcreteRelationTransfer->setIdProductList($productListTransfer->getIdProductList());
        $this->productListProductConcreteRelationWriter->saveProductListProductConcreteRelation($productListProductConcreteRelationTransfer);
        $productListTransfer->setProductListProductConcreteRelation($productListProductConcreteRelationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     * @param \Generated\Shared\Transfer\ProductListCategoryRelationTransfer|null $productListCategoryRelationTransfer
     *
     * @return void
     */
    protected function saveProductListCategoryRelation(
        ProductListTransfer $productListTransfer,
        ?ProductListCategoryRelationTransfer $productListCategoryRelationTransfer
    ): void {
        if (!$productListCategoryRelationTransfer) {
            return;
        }

        $productListCategoryRelationTransfer->setIdProductList($productListTransfer->getIdProductList());
        $this->productListCategoryRelationWriter->saveProductListCategoryRelation($productListCategoryRelationTransfer);
        $productListTransfer->setProductListCategoryRelation($productListCategoryRelationTransfer);
    }
}
