<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle;

use Generated\Shared\Transfer\BundledProductTransfer;
use Generated\Shared\Transfer\ProductBundleTransfer;
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
        $this->productBundleQueryContainer->getConnection()->beginTransaction();

        $idProductAbstract = $this->productFacade->addProduct($productBundleTransfer->getProductAbstract(), []);
        $this->createBundledProducts($productBundleTransfer, $idProductAbstract);

        $this->productBundleQueryContainer->getConnection()->commit();
    }

    /**
     * @param int $idProductAbstract
     * @param BundledProductTransfer $bundledProductTransfer
     *
     * @return void
     */
    protected function createBundleEntity($idProductAbstract, BundledProductTransfer $bundledProductTransfer)
    {
        $productBundleEntity = new SpyProductBundle();
        $productBundleEntity->setFkBundledProduct($idProductAbstract);
        $productBundleEntity->setFkProduct($bundledProductTransfer->getIdProduct());
        $productBundleEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductBundleTransfer $productBundleTransfer
     * @param int $idProductAbstract
     *
     * @return void
     */
    protected function createBundledProducts(ProductBundleTransfer $productBundleTransfer, $idProductAbstract)
    {
        foreach ($productBundleTransfer->getBundledProducts() as $bundledProductTransfer) {
            $this->createBundleEntity($idProductAbstract, $bundledProductTransfer);
        }
    }
}
