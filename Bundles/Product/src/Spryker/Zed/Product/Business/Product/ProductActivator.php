<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use Spryker\Zed\Product\Business\Exception\ProductConcreteNotFoundException;

class ProductActivator
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
     * @throws \Spryker\Zed\Product\Business\Exception\ProductConcreteNotFoundException
     *
     * @return void
     */
    public function activateProductConcrete($idProductConcrete)
    {
        $productConcrete = $this->productConcreteManager->getProductConcreteById($idProductConcrete);
        $productAbstract = $this->productAbstractManager->getProductAbstractById(
            $productConcrete->getFkProductAbstract()
        );
        $this->assertProducts($productAbstract, $productConcrete, $idProductConcrete);

        $productConcrete->setIsActive(true);

        $this->productConcreteManager->saveProductConcrete($productConcrete);
        $this->productConcreteManager->touchProductActive($productConcrete->getFkProductAbstract());

        $productUrl = $this->productUrlManager->updateProductUrl($productAbstract);

        $this->productUrlManager->touchProductUrlActive($productAbstract);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function deActivateProductConcrete($idProductConcrete)
    {
        $productConcrete = $this->productConcreteManager->getProductConcreteById($idProductConcrete);
        $productAbstract = $this->productAbstractManager->getProductAbstractById(
            $productConcrete->getFkProductAbstract()
        );
        $this->assertProducts($productAbstract, $productConcrete, $idProductConcrete);

        $productConcrete->setIsActive(false);

        $this->productConcreteManager->saveProductConcrete($productConcrete);
        $this->productConcreteManager->touchProductInactive($productConcrete->getFkProductAbstract());

        $this->productUrlManager->updateProductUrl($productAbstract);
        $this->productUrlManager->touchProductUrlInactive($productAbstract);
    }

    /**
     * TODO move to ProductManager
     *
     * @param int $idProductAbstract
     *
     * @return bool
     */
    protected function isProductActive($idProductAbstract)
    {
        $productConcreteCollection = $this->productConcreteManager->getConcreteProductsByAbstractProductId(
            $idProductAbstract
        );

        foreach ($productConcreteCollection as $productConcreteTransfer) {
            if ($productConcreteTransfer->getIsActive()) {
                return true;
            }
        }

        return false;
    }

    protected function assertProducts($productAbstract, $productConcrete, $idProductConcrete)
    {
        if (!$productConcrete || !$productAbstract) {
            throw new ProductConcreteNotFoundException(sprintf(
                'Could not activate product concrete [%s]',
                $idProductConcrete
            ));
        }
    }

}
