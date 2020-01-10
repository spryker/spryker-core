<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Status;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleReaderInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductFacadeInterface;

class ProductBundleStatusUpdater implements ProductBundleStatusUpdaterInterface
{
    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleReaderInterface
     */
    protected $productBundleReader;

    /**
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleReaderInterface $productBundleReader
     */
    public function __construct(
        ProductBundleToProductFacadeInterface $productFacade,
        ProductBundleReaderInterface $productBundleReader
    ) {
        $this->productFacade = $productFacade;
        $this->productBundleReader = $productBundleReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function expandProductConcreteStatusWithBundledProducts(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        if ($productConcreteTransfer->getProductBundle() === null) {
            return $productConcreteTransfer;
        }

        return $productConcreteTransfer->setIsActive($this->getProductConcreteStatus($productConcreteTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function deactivateProductBundlesByProductConcrete(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        $bundledProducts = $this->productBundleReader
            ->getBundledProductByIdProduct($productConcreteTransfer->getIdProductConcrete());

        if (!$bundledProducts) {
            return $productConcreteTransfer;
        }

        foreach ($bundledProducts as $bundledProduct) {
            $productForBundleTransfers = $this->productBundleReader
                ->getBundleItemsByIdProduct($bundledProduct->getIdProductBundle());

            $this->deactivateProductBundles($productForBundleTransfers);
        }

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductForBundleTransfer[] $productForBundleTransfers
     *
     * @return void
     */
    protected function deactivateProductBundles(array $productForBundleTransfers): void
    {
        foreach ($productForBundleTransfers as $productForBundleTransfer) {
            $idProductConcrete = $productForBundleTransfer->getIdProductBundle();
            if (!$productForBundleTransfer->getIsActive()) {
                $this->productFacade->deactivateProductConcrete($idProductConcrete);

                return;
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return bool
     */
    protected function getProductConcreteStatus(ProductConcreteTransfer $productConcreteTransfer): bool
    {
        $productForBundleTransfer = $this->productBundleReader
            ->findBundledProductsByIdProductConcrete($productConcreteTransfer->getIdProductConcrete());
        foreach ($productForBundleTransfer as $bundledProductTransfer) {
            if (!$bundledProductTransfer->getIsActive()) {
                $productConcreteTransfer->setIsActive(false);

                return false;
            }
        }

        return true;
    }
}
