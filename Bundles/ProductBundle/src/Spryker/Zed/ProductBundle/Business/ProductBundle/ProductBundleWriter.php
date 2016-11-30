<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle;

use Generated\Shared\Transfer\BundledProductTransfer;
use Generated\Shared\Transfer\ProductBundleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductForBundleTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Orm\Zed\ProductBundle\Persistence\SpyProductBundle;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;

class ProductBundleWriter
{

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface
     */
    protected $productBundleQueryContainer;

    /**
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductInterface $productFacade
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface $productBundleQueryContainer
     */
    public function __construct(
        ProductBundleToProductInterface $productFacade,
        ProductBundleQueryContainerInterface $productBundleQueryContainer
    ) {
        $this->productFacade = $productFacade;
        $this->productBundleQueryContainer = $productBundleQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductBundleTransfer $productBundleTransfer
     */
    public function createProductBundle(ProductBundleTransfer $productBundleTransfer)
    {
        $productBundleTransfer->requireProductAbstract()->requireProductsToBeAssigned();

        $this->productBundleQueryContainer->getConnection()->beginTransaction();

        $productAbstractTransfer = $productBundleTransfer->getProductAbstract();

        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setSku($productAbstractTransfer->getSku());
        $productConcreteTransfer->setPrice($productAbstractTransfer->getPrice());
        $productConcreteTransfer->setLocalizedAttributes($productAbstractTransfer->getLocalizedAttributes());

        $stockProductTransfer = new StockProductTransfer();
        $stockProductTransfer->setIdStockProduct(1);
        $stockProductTransfer->setQuantity(5);
        $stockProductTransfer->setSku($productConcreteTransfer->getSku());
        $stockProductTransfer->setStockType('Warehouse1');

        $productConcreteTransfer->addStock($stockProductTransfer);

        $idProductAbstract = $this->productFacade->addProduct($productBundleTransfer->getProductAbstract(), [$productConcreteTransfer]);
        $this->createBundledProducts($productBundleTransfer, $productConcreteTransfer->getIdProductConcrete());

        $this->productFacade->touchProductAbstract($idProductAbstract);

        $this->productFacade->activateProductConcrete($productConcreteTransfer->getIdProductConcrete());

        $this->productBundleQueryContainer->getConnection()->commit();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductForBundleTransfer $productForBundleTransfer
     * @param int $idBundledProduct
     *
     * @return void
     */
    protected function createBundleEntity(ProductForBundleTransfer $productForBundleTransfer, $idBundledProduct)
    {
        $productBundleEntity = new SpyProductBundle();
        $productBundleEntity->setFkBundledProduct($productForBundleTransfer->getIdProductConcrete());
        $productBundleEntity->setFkProduct($idBundledProduct);
        $productBundleEntity->setQuantity($productForBundleTransfer->getQuantity());
        $productBundleEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductBundleTransfer $productBundleTransfer
     * @param int $idBundledProduct
     *
     * @return void
     */
    protected function createBundledProducts(ProductBundleTransfer $productBundleTransfer, $idBundledProduct)
    {
        foreach ($productBundleTransfer->getProductsToBeAssigned() as $productForBundleTransfer) {
            $this->createBundleEntity($productForBundleTransfer, $idBundledProduct);
        }
    }
}
