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
            return $this->executeSaveProductListTransaction($productListTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function createProductList(ProductListTransfer $productListTransfer): ProductListResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($productListTransfer) {
            return $this->executeCreateProductListTransaction($productListTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function updateProductList(ProductListTransfer $productListTransfer): ProductListResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($productListTransfer) {
            return $this->executeUpdateProductListTransaction($productListTransfer);
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
        if ($productListTransfer->getIdProductList()) {
            return $this->executeUpdateProductListTransaction($productListTransfer)->getProductList();
        }

        return $this->executeCreateProductListTransaction($productListTransfer)->getProductList();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function executeCreateProductListTransaction(
        ProductListTransfer $productListTransfer
    ): ProductListResponseTransfer {
        $productListTransfer->requireTitle();
        $productListTransfer->setKey($this->productListKeyGenerator->generateProductListKey($productListTransfer->getTitle()));
        $productListResponseTransfer = (new ProductListResponseTransfer())->setProductList($productListTransfer);

        $productListResponseTransfer = $this->executeProductListPreSavePlugins($productListResponseTransfer);
        $productListTransfer = $this->productListEntityManager->createProductList($productListResponseTransfer->getProductList());
        $productListTransfer = $this->executeProductListPostSavePlugins($productListTransfer);

        return $productListResponseTransfer
            ->setProductList($productListTransfer)
            ->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function executeUpdateProductListTransaction(
        ProductListTransfer $productListTransfer
    ): ProductListResponseTransfer {
        $productListResponseTransfer = (new ProductListResponseTransfer())->setProductList($productListTransfer);

        $productListResponseTransfer = $this->executeProductListPreSavePlugins($productListResponseTransfer);
        $productListTransfer = $this->productListEntityManager->updateProductList($productListResponseTransfer->getProductList());
        $productListTransfer = $this->executeProductListPostSavePlugins($productListTransfer);

        return $productListResponseTransfer
            ->setProductList($productListTransfer)
            ->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function executeProductListPreSavePlugins(ProductListResponseTransfer $productListResponseTransfer): ProductListResponseTransfer
    {
        foreach ($this->productListPreSavePlugins as $productListPreSavePlugin) {
            $productListResponseTransfer = $productListPreSavePlugin->preSave($productListResponseTransfer);
        }

        return $productListResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    protected function executeProductListPostSavePlugins(ProductListTransfer $productListTransfer): ProductListTransfer
    {
        foreach ($this->productListPostSavers as $productListPostSaver) {
            $productListTransfer = $productListPostSaver->postSave($productListTransfer);
        }

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
        $this->productListEntityManager->deleteProductListProductRelations($productListTransfer);
        $this->productListEntityManager->deleteProductListCategoryRelations($productListTransfer);
        $this->productListEntityManager->deleteProductList($productListTransfer);
    }
}
