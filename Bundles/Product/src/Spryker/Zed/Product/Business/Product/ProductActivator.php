<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Business\Exception\ProductConcreteNotFoundException;

class ProductActivator implements ProductActivatorInterface
{

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface
     */
    protected $productAbstractManager;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface
     */
    protected $productConcreteManager;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductUrlManagerInterface
     */
    protected $productUrlManager;

    /**
     * @param \Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface $productAbstractManager
     * @param \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface $productConcreteManager
     * @param \Spryker\Zed\Product\Business\Product\ProductUrlManagerInterface $productUrlManager
     */
    public function __construct(
        ProductAbstractManagerInterface $productAbstractManager,
        ProductConcreteManagerInterface $productConcreteManager,
        ProductUrlManagerInterface $productUrlManager
    ) {
        $this->productAbstractManager = $productAbstractManager;
        $this->productConcreteManager = $productConcreteManager;
        $this->productUrlManager = $productUrlManager;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function activateProductConcrete($idProductConcrete)
    {
        $productConcrete = $this->productConcreteManager->getProductConcreteById($idProductConcrete);
        $this->assertProductConcrete($idProductConcrete, $productConcrete);

        $productAbstract = $this->productAbstractManager->getProductAbstractById(
            $productConcrete->getFkProductAbstract()
        );
        $this->assertProductAbstract($idProductConcrete, $productAbstract);

        $productConcrete->setIsActive(true);

        $this->productConcreteManager->saveProductConcrete($productConcrete);
        $this->productConcreteManager->touchProductActive($productConcrete->getIdProductConcrete());
        $this->productAbstractManager->touchProductActive($productConcrete->getFkProductAbstract());

        $this->productUrlManager->updateProductUrl($productAbstract);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function deActivateProductConcrete($idProductConcrete)
    {
        $productConcrete = $this->productConcreteManager->getProductConcreteById($idProductConcrete);
        $this->assertProductConcrete($idProductConcrete, $productConcrete);

        $productAbstract = $this->productAbstractManager->getProductAbstractById(
            $productConcrete->getFkProductAbstract()
        );
        $this->assertProductAbstract($idProductConcrete, $productAbstract);

        $productConcrete->setIsActive(false);

        $this->productConcreteManager->saveProductConcrete($productConcrete);
        $this->productConcreteManager->touchProductInactive($productConcrete->getIdProductConcrete());
        $this->productAbstractManager->touchProductInactive($productConcrete->getFkProductAbstract());

        $this->productUrlManager->deleteProductUrl($productAbstract);
    }

    /**
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstract
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductConcreteNotFoundException
     *
     * @return void
     */
    protected function assertProductAbstract($idProductAbstract, ProductAbstractTransfer $productAbstract = null)
    {
        if (!$productAbstract) {
            throw new ProductConcreteNotFoundException(sprintf(
                'Product abstract [%s] does not exist',
                $idProductAbstract
            ));
        }
    }

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcrete
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductConcreteNotFoundException
     *
     * @return void
     */
    protected function assertProductConcrete($idProductConcrete, ProductConcreteTransfer $productConcrete = null)
    {
        if (!$productConcrete) {
            throw new ProductConcreteNotFoundException(sprintf(
                'Could not activate product concrete [%s]',
                $idProductConcrete
            ));
        }
    }

}
