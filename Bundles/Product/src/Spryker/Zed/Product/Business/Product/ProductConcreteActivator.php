<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Product\Business\Exception\ProductConcreteNotFoundException;
use Spryker\Zed\Product\Business\Product\Status\ProductAbstractStatusCheckerInterface;
use Spryker\Zed\Product\Business\Product\Touch\ProductConcreteTouchInterface;
use Spryker\Zed\Product\Business\Product\Url\ProductUrlManagerInterface;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\Product\Persistence\ProductRepositoryInterface;

class ProductConcreteActivator implements ProductConcreteActivatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Product\Business\Product\Status\ProductAbstractStatusCheckerInterface
     */
    protected $productAbstractStatusChecker;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface
     */
    protected $productAbstractManager;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface
     */
    protected $productConcreteManager;

    /**
     * @var \Spryker\Zed\Product\Business\Product\Url\ProductUrlManagerInterface
     */
    protected $productUrlManager;

    /**
     * @var \Spryker\Zed\Product\Business\Product\Touch\ProductConcreteTouchInterface
     */
    protected $productConcreteTouch;

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @param \Spryker\Zed\Product\Business\Product\Status\ProductAbstractStatusCheckerInterface $productAbstractStatusChecker
     * @param \Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface $productAbstractManager
     * @param \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface $productConcreteManager
     * @param \Spryker\Zed\Product\Business\Product\Url\ProductUrlManagerInterface $productUrlManager
     * @param \Spryker\Zed\Product\Business\Product\Touch\ProductConcreteTouchInterface $productConcreteTouch
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\Product\Persistence\ProductRepositoryInterface $productRepository
     */
    public function __construct(
        ProductAbstractStatusCheckerInterface $productAbstractStatusChecker,
        ProductAbstractManagerInterface $productAbstractManager,
        ProductConcreteManagerInterface $productConcreteManager,
        ProductUrlManagerInterface $productUrlManager,
        ProductConcreteTouchInterface $productConcreteTouch,
        ProductQueryContainerInterface $productQueryContainer,
        ProductRepositoryInterface $productRepository
    ) {
        $this->productAbstractManager = $productAbstractManager;
        $this->productConcreteManager = $productConcreteManager;
        $this->productUrlManager = $productUrlManager;
        $this->productAbstractStatusChecker = $productAbstractStatusChecker;
        $this->productConcreteTouch = $productConcreteTouch;
        $this->productQueryContainer = $productQueryContainer;
        $this->productRepository = $productRepository;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function activateProductConcrete($idProductConcrete)
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($idProductConcrete): void {
            $this->executeActivateProductConcreteTransaction($idProductConcrete);
        });
    }

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function deactivateProductConcrete($idProductConcrete)
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($idProductConcrete) {
            $this->executeDeactivateProductConcreteTransaction($idProductConcrete);
        });
    }

    /**
     * @param string[] $productConcreteSkus
     *
     * @return void
     */
    public function deactivateProductConcretesByConcreteSkus(array $productConcreteSkus): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($productConcreteSkus) {
            $this->executeDeactivateProductConcretesTransactionByConcreteSkus($productConcreteSkus);
        });
    }

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    protected function executeActivateProductConcreteTransaction(int $idProductConcrete): void
    {
        $productConcreteTransfer = $this->getProductConcreteTransfer($idProductConcrete);
        $this->updateIsActive($productConcreteTransfer, true);

        $productAbstractTransfer = $this->getProductAbstractTransfer($productConcreteTransfer);
        $this->productUrlManager->updateProductUrl($productAbstractTransfer);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    protected function executeDeactivateProductConcreteTransaction(int $idProductConcrete): void
    {
        $productConcreteTransfer = $this->getProductConcreteTransfer($idProductConcrete);
        $this->updateIsActive($productConcreteTransfer, false);

        if ($this->productAbstractStatusChecker->isActive($productConcreteTransfer->getFkProductAbstract()) === false) {
            $productAbstractTransfer = $this->getProductAbstractTransfer($productConcreteTransfer);
            $this->productUrlManager->deleteProductUrl($productAbstractTransfer);
        }
    }

    /**
     * @param string[] $productConcreteSkus
     *
     * @return void
     */
    protected function executeDeactivateProductConcretesTransactionByConcreteSkus(array $productConcreteSkus): void
    {
        $productConcreteTransfers = $this->productConcreteManager->getProductConcretesByConcreteSkus($productConcreteSkus);
        $productAbstractIds = [];
        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $this->updateIsActive($productConcreteTransfer, false);
            $productAbstractIds[] = $productConcreteTransfer->getFkProductAbstract();
        }

        $notActiveProductAbstractIds = $this->productAbstractStatusChecker->filterActiveIds($productAbstractIds);
        $notActiveProductAbstractTransfers = $this->productRepository
            ->getRawProductAbstractsByProductAbstractIds($notActiveProductAbstractIds);
        foreach ($notActiveProductAbstractTransfers as $productAbstractTransfer) {
            $this->productUrlManager->deleteProductUrl($productAbstractTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param bool $isActive
     *
     * @return void
     */
    protected function updateIsActive(ProductConcreteTransfer $productConcreteTransfer, $isActive)
    {
        $productConcreteTransfer->setIsActive($isActive);
        $this->productConcreteManager->saveProductConcrete($productConcreteTransfer);

        $this->productConcreteTouch->touchProductConcreteByTransfer($productConcreteTransfer);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    protected function getProductConcreteTransfer($idProductConcrete)
    {
        $productConcreteTransfer = $this->productConcreteManager->findProductConcreteById($idProductConcrete);

        $this->assertProductConcrete($idProductConcrete, $productConcreteTransfer);

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|null
     */
    protected function getProductAbstractTransfer(ProductConcreteTransfer $productConcreteTransfer)
    {
        $productAbstractTransfer = $this->productAbstractManager->findProductAbstractById(
            $productConcreteTransfer->getFkProductAbstract()
        );

        $this->assertProductAbstract($productConcreteTransfer->getIdProductConcrete(), $productAbstractTransfer);

        return $productAbstractTransfer;
    }

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer|null $productConcrete
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductConcreteNotFoundException
     *
     * @return void
     */
    protected function assertProductConcrete($idProductConcrete, ?ProductConcreteTransfer $productConcrete = null)
    {
        if (!$productConcrete) {
            throw new ProductConcreteNotFoundException(sprintf(
                'Could not activate product concrete [%s]',
                $idProductConcrete
            ));
        }
    }

    /**
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer|null $productAbstract
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductConcreteNotFoundException
     *
     * @return void
     */
    protected function assertProductAbstract($idProductAbstract, ?ProductAbstractTransfer $productAbstract = null)
    {
        if (!$productAbstract) {
            throw new ProductConcreteNotFoundException(sprintf(
                'Product abstract [%s] does not exist',
                $idProductAbstract
            ));
        }
    }
}
