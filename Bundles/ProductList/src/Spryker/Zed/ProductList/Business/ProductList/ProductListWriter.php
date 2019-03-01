<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business\ProductList;

use Generated\Shared\Transfer\ProductListResponseTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductList\Business\KeyGenerator\ProductListKeyGeneratorInterface;
use Spryker\Zed\ProductList\Persistence\ProductListEntityManagerInterface;

class ProductListWriter implements ProductListWriterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ProductList\Persistence\ProductListEntityManagerInterface
     */
    protected $productListEntityManager;

    /**
     * @var \Spryker\Zed\ProductList\Business\KeyGenerator\ProductListKeyGeneratorInterface
     */
    protected $productListKeyGenerator;

    /**
     * @var array|\Spryker\Zed\ProductList\Business\ProductList\ProductListPostSaverInterface[]
     */
    protected $productListPostSavers;

    /**
     * @var array|\Spryker\Zed\ProductListExtension\Dependency\Plugin\ProductListPreSaveInterface[]
     */
    protected $productListPreSavePlugins;

    /**
     * @param \Spryker\Zed\ProductList\Persistence\ProductListEntityManagerInterface $productListEntityManager
     * @param \Spryker\Zed\ProductList\Business\KeyGenerator\ProductListKeyGeneratorInterface $productListKeyGenerator
     * @param \Spryker\Zed\ProductList\Business\ProductList\ProductListPostSaverInterface[] $productListPostSavers
     * @param \Spryker\Zed\ProductListExtension\Dependency\Plugin\ProductListPreSaveInterface[] $productListPreSavePlugins
     */
    public function __construct(
        ProductListEntityManagerInterface $productListEntityManager,
        ProductListKeyGeneratorInterface $productListKeyGenerator,
        array $productListPostSavers = [],
        array $productListPreSavePlugins = []
    ) {
        $this->productListEntityManager = $productListEntityManager;
        $this->productListKeyGenerator = $productListKeyGenerator;
        $this->productListPostSavers = $productListPostSavers;
        $this->productListPreSavePlugins = $productListPreSavePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function saveProductList(ProductListTransfer $productListTransfer): ProductListTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($productListTransfer) {
            return $this->executeSaveProductListTransaction($productListTransfer)->getProductList();
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function saveProductListWithResponse(ProductListTransfer $productListTransfer): ProductListResponseTransfer
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
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function executeSaveProductListTransaction(
        ProductListTransfer $productListTransfer
    ): ProductListResponseTransfer {
        $productListResponseTransfer = new ProductListResponseTransfer();

        $productListTransfer->requireTitle();
        if (empty($productListTransfer->getKey())) {
            $productListTransfer->setKey($this->productListKeyGenerator->generateProductListKey($productListTransfer->getTitle()));
        }

        $productListResponseTransfer->setProductList($productListTransfer);

        foreach ($this->productListPreSavePlugins as $productListPreSavePlugin) {
            $productListResponseTransfer = $productListPreSavePlugin->preSave($productListResponseTransfer);
        }

        $productListTransfer = $this->productListEntityManager->saveProductList($productListResponseTransfer->getProductList());

        foreach ($this->productListPostSavers as $productListPostSaver) {
            $productListTransfer = $productListPostSaver->postSave($productListTransfer);
        }

        $productListResponseTransfer->setProductList($productListTransfer);

        return $productListResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return void
     */
    protected function executeDeleteProductListTransaction(
        ProductListTransfer $productListTransfer
    ): void {
        $this->productListEntityManager->deleteProductListProductRelations($productListTransfer);
        $this->productListEntityManager->deleteProductListCategoryRelations($productListTransfer);
        $this->productListEntityManager->deleteProductList($productListTransfer);
    }
}
