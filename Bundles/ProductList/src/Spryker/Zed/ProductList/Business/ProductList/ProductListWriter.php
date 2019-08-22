<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business\ProductList;

use ArrayObject;
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
     * @var \Spryker\Zed\ProductList\Business\ProductList\ProductListPostSaverInterface[]
     */
    protected $productListPostSavers;

    /**
     * @var \Spryker\Zed\ProductListExtension\Dependency\Plugin\ProductListPreCreatePluginInterface[]
     */
    protected $productListPreCreatePlugins;

    /**
     * @var \Spryker\Zed\ProductListExtension\Dependency\Plugin\ProductListPreUpdatePluginInterface[]
     */
    protected $productListPreUpdatePlugins;

    /**
     * @param \Spryker\Zed\ProductList\Persistence\ProductListEntityManagerInterface $productListEntityManager
     * @param \Spryker\Zed\ProductList\Business\KeyGenerator\ProductListKeyGeneratorInterface $productListKeyGenerator
     * @param \Spryker\Zed\ProductList\Business\ProductList\ProductListPostSaverInterface[] $productListPostSavers
     * @param \Spryker\Zed\ProductListExtension\Dependency\Plugin\ProductListPreCreatePluginInterface[] $productListPreCreatePlugins
     * @param \Spryker\Zed\ProductListExtension\Dependency\Plugin\ProductListPreUpdatePluginInterface[] $productListPreUpdatePlugins
     */
    public function __construct(
        ProductListEntityManagerInterface $productListEntityManager,
        ProductListKeyGeneratorInterface $productListKeyGenerator,
        array $productListPostSavers = [],
        array $productListPreCreatePlugins = [],
        array $productListPreUpdatePlugins = []
    ) {
        $this->productListEntityManager = $productListEntityManager;
        $this->productListKeyGenerator = $productListKeyGenerator;
        $this->productListPostSavers = $productListPostSavers;
        $this->productListPreCreatePlugins = $productListPreCreatePlugins;
        $this->productListPreUpdatePlugins = $productListPreUpdatePlugins;
    }

    /**
     * @deprecated Use createProductList() or updateProductList() instead.
     *
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

        $productListResponseTransfer = $this->executeProductListPreCreatePlugins($productListResponseTransfer);
        $productListTransfer = $this->productListEntityManager->createProductList($productListResponseTransfer->getProductList());
        $productListTransfer = $this->executeProductListPostSavePlugins($productListTransfer);

        return $productListResponseTransfer
            ->setProductList($productListTransfer)
            ->setIsSuccessful(true);
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

        $productListResponseTransfer = $this->executeProductListPreUpdatePlugins($productListResponseTransfer);
        $productListTransfer = $this->productListEntityManager->updateProductList($productListResponseTransfer->getProductList());
        $productListTransfer = $this->executeProductListPostSavePlugins($productListTransfer);

        return $productListResponseTransfer
            ->setProductList($productListTransfer)
            ->setIsSuccessful(true);
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

    /**
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function executeProductListPreCreatePlugins(ProductListResponseTransfer $productListResponseTransfer): ProductListResponseTransfer
    {
        foreach ($this->productListPreCreatePlugins as $productListPreCreatePlugin) {
            $resultProductListResponseTransfer = $productListPreCreatePlugin->execute($productListResponseTransfer->getProductList());
            $productListResponseTransfer = $this->mergeProductListResponseMessages($productListResponseTransfer, $resultProductListResponseTransfer);
            $productListResponseTransfer->setProductList($resultProductListResponseTransfer->getProductList());
        }

        return $productListResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function executeProductListPreUpdatePlugins(ProductListResponseTransfer $productListResponseTransfer): ProductListResponseTransfer
    {
        foreach ($this->productListPreUpdatePlugins as $productListPreUpdatePlugin) {
            $resultProductListResponseTransfer = $productListPreUpdatePlugin->execute($productListResponseTransfer->getProductList());
            $productListResponseTransfer = $this->mergeProductListResponseMessages($productListResponseTransfer, $resultProductListResponseTransfer);
            $productListResponseTransfer->setProductList($resultProductListResponseTransfer->getProductList());
        }

        return $productListResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $resultProductListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function mergeProductListResponseMessages(
        ProductListResponseTransfer $productListResponseTransfer,
        ProductListResponseTransfer $resultProductListResponseTransfer
    ): ProductListResponseTransfer {
        $messageTransfers = array_merge(
            $productListResponseTransfer->getMessages()->getArrayCopy(),
            $resultProductListResponseTransfer->getMessages()->getArrayCopy()
        );

        return $productListResponseTransfer
            ->setMessages(new ArrayObject($messageTransfers));
    }
}
