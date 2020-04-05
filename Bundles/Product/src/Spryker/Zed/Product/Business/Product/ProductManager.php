<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductManager implements ProductManagerInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface
     */
    protected $productAbstractManager;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface
     */
    protected $productConcreteManager;

    /**
     * @param \Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface $productAbstractManager
     * @param \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface $productConcreteManager
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     */
    public function __construct(
        ProductAbstractManagerInterface $productAbstractManager,
        ProductConcreteManagerInterface $productConcreteManager,
        ProductQueryContainerInterface $productQueryContainer
    ) {
        $this->productAbstractManager = $productAbstractManager;
        $this->productConcreteManager = $productConcreteManager;
        $this->productQueryContainer = $productQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteCollection
     *
     * @return int
     */
    public function addProduct(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteCollection)
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($productAbstractTransfer, $productConcreteCollection): int {
            return $this->executeAddProductTransaction($productAbstractTransfer, $productConcreteCollection);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteCollection
     *
     * @return int
     */
    public function saveProduct(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteCollection)
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($productAbstractTransfer, $productConcreteCollection): int {
            return $this->executeSaveProductTransaction($productAbstractTransfer, $productConcreteCollection);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array $productConcreteTransfers
     *
     * @return int
     */
    protected function executeAddProductTransaction(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteTransfers): int
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($productAbstractTransfer);
        $productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $productConcreteTransfer->setFkProductAbstract($idProductAbstract);
            $idProductConcrete = $this->productConcreteManager->createProductConcrete($productConcreteTransfer);
            $productConcreteTransfer->setIdProductConcrete($idProductConcrete);
        }

        return $idProductAbstract;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array $productConcreteTransfers
     *
     * @return int
     */
    protected function executeSaveProductTransaction(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteTransfers): int
    {
        $idProductAbstract = $this->productAbstractManager->saveProductAbstract($productAbstractTransfer);

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $productConcreteTransfer->setFkProductAbstract($idProductAbstract);

            $productConcreteEntity = $this->productConcreteManager->findProductEntityByAbstractAndConcrete(
                $productAbstractTransfer,
                $productConcreteTransfer
            );

            if ($productConcreteEntity) {
                $this->productConcreteManager->saveProductConcrete($productConcreteTransfer);
            } else {
                $idProductConcrete = $this->productConcreteManager->createProductConcrete($productConcreteTransfer);
                $productConcreteTransfer->setIdProductConcrete($idProductConcrete);
            }
        }

        return $idProductAbstract;
    }
}
