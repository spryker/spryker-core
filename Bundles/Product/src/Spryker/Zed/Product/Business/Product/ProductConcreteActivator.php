<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Business\Exception\ProductConcreteNotFoundException;
use Spryker\Zed\Product\Business\Product\Status\ProductAbstractStatusCheckerInterface;
use Spryker\Zed\Product\Business\Product\Touch\ProductConcreteTouchInterface;
use Spryker\Zed\Product\Business\Product\Url\ProductUrlManagerInterface;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductConcreteActivator implements ProductConcreteActivatorInterface
{
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
     * @param \Spryker\Zed\Product\Business\Product\Status\ProductAbstractStatusCheckerInterface $productAbstractStatusChecker
     * @param \Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface $productAbstractManager
     * @param \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface $productConcreteManager
     * @param \Spryker\Zed\Product\Business\Product\Url\ProductUrlManagerInterface $productUrlManager
     * @param \Spryker\Zed\Product\Business\Product\Touch\ProductConcreteTouchInterface $productConcreteTouch
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     */
    public function __construct(
        ProductAbstractStatusCheckerInterface $productAbstractStatusChecker,
        ProductAbstractManagerInterface $productAbstractManager,
        ProductConcreteManagerInterface $productConcreteManager,
        ProductUrlManagerInterface $productUrlManager,
        ProductConcreteTouchInterface $productConcreteTouch,
        ProductQueryContainerInterface $productQueryContainer
    ) {
        $this->productAbstractManager = $productAbstractManager;
        $this->productConcreteManager = $productConcreteManager;
        $this->productUrlManager = $productUrlManager;
        $this->productAbstractStatusChecker = $productAbstractStatusChecker;
        $this->productConcreteTouch = $productConcreteTouch;
        $this->productQueryContainer = $productQueryContainer;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function activateProductConcrete($idProductConcrete)
    {
        $this->productQueryContainer->getConnection()->beginTransaction();

        $productConcreteTransfer = $this->getProductConcreteTransfer($idProductConcrete);
        $this->updateIsActive($productConcreteTransfer, true);

        $productAbstractTransfer = $this->getProductAbstractTransfer($productConcreteTransfer);
        $this->productUrlManager->updateProductUrl($productAbstractTransfer);

        $this->productQueryContainer->getConnection()->commit();
    }

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function deactivateProductConcrete($idProductConcrete)
    {
        $this->productQueryContainer->getConnection()->beginTransaction();

        $productConcreteTransfer = $this->getProductConcreteTransfer($idProductConcrete);
        $this->updateIsActive($productConcreteTransfer, false);

        if ($this->productAbstractStatusChecker->isActive($productConcreteTransfer->getFkProductAbstract()) === false) {
            $productAbstractTransfer = $this->getProductAbstractTransfer($productConcreteTransfer);
            $this->productUrlManager->deleteProductUrl($productAbstractTransfer);
        }

        $this->productQueryContainer->getConnection()->commit();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param bool $isActive
     *
     * @return void
     */
    protected function updateIsActive(ProductConcreteTransfer $productConcreteTransfer, $isActive)
    {
        $productConcreteTransfer = $this->getProductConcreteTransfer($productConcreteTransfer->getIdProductConcrete());

        $productConcreteTransfer->setIsActive($isActive);
        $this->productConcreteManager->saveProductConcrete($productConcreteTransfer);

        $this->productConcreteTouch->touchProductConcrete($productConcreteTransfer->getIdProductConcrete());
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
