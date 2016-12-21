<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductForBundleTransfer;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Stock\ProductBundleStockWriter;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;

class ProductBundleWriter implements ProductBundleWriterInterface
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
     * @var \Spryker\Zed\ProductBundle\Business\ProductBundle\Stock\ProductBundleStockWriter
     */
    protected $productBundleStockWriter;

    /**
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductInterface $productFacade
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface $productBundleQueryContainer
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundle\Stock\ProductBundleStockWriter $productBundleStockWriter
     */
    public function __construct(
        ProductBundleToProductInterface $productFacade,
        ProductBundleQueryContainerInterface $productBundleQueryContainer,
        ProductBundleStockWriter $productBundleStockWriter
    ) {
        $this->productFacade = $productFacade;
        $this->productBundleQueryContainer = $productBundleQueryContainer;
        $this->productBundleStockWriter = $productBundleStockWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function saveBundledProducts(ProductConcreteTransfer $productConcreteTransfer)
    {
        if ($productConcreteTransfer->getProductBundle() === null) {
            return $productConcreteTransfer;
        }

        $productBundleTransfer = $productConcreteTransfer->getProductBundle();
        $bundledProducts = $productConcreteTransfer->getProductBundle()->getBundledProducts();

        if ($bundledProducts->count() == 0){
            return $productConcreteTransfer;
        }

        $productConcreteTransfer->requireIdProductConcrete();

        foreach ($bundledProducts as $productForBundleTransfer) {
            $this->createProductBundleEntity($productForBundleTransfer, $productConcreteTransfer->getIdProductConcrete());
        }

        $productsToRemove = $productBundleTransfer->getBundlesToRemove();

        $this->removeBundledProducts($productsToRemove, $productConcreteTransfer->getIdProductConcrete());

        $this->productBundleStockWriter->updateStock($productConcreteTransfer);

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductForBundleTransfer $productForBundleTransfer
     * @param int $idBundledProduct
     *
     * @return void
     */
    protected function createProductBundleEntity(ProductForBundleTransfer $productForBundleTransfer, $idBundledProduct)
    {
        $productBundleEntity = $this->productBundleQueryContainer
            ->queryBundleProduct($idBundledProduct)
            ->filterByFkBundledProduct($productForBundleTransfer->getIdProductConcrete())
            ->findOneOrCreate();

        $productBundleEntity->setQuantity($productForBundleTransfer->getQuantity());
        $productBundleEntity->save();

        $productForBundleTransfer->setIdProductBundle($productBundleEntity->getIdProductBundle());
    }

    /**
     * @param array $productsToRemove
     * @param int $idProductBundle
     *
     * @return void
     */
    protected function removeBundledProducts(array $productsToRemove, $idProductBundle)
    {
        foreach ($productsToRemove as $idBundledProduct) {
            $productBundleEntity = $this->productBundleQueryContainer
                ->queryBundledProductByIdProduct($idBundledProduct)
                ->filterByFkProduct($idProductBundle)
                ->findOne();

            if ($productBundleEntity === null) {
                continue;
            }

            $productBundleEntity->delete();
        }
    }

}
