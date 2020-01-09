<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Status;

use ArrayObject;
use Generated\Shared\Transfer\ProductBundleCriteriaFilterTransfer;
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
    public function updateBundleStatus(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        if ($productConcreteTransfer->getProductBundle() !== null) {
            return $productConcreteTransfer->setIsActive($this->getProductConcreteStatus($productConcreteTransfer));
        }

        $productBundleCriteriaFilterTransfer = (new ProductBundleCriteriaFilterTransfer())
            ->setIdBundledProduct($productConcreteTransfer->getIdProductConcrete());

        $productBundleCollectionTransfer = $this->productBundleReader
            ->getProductBundleCollectionByCriteriaFilter($productBundleCriteriaFilterTransfer);

        $productBundleTransfers = $productBundleCollectionTransfer->getProductBundles();
        if (!$productBundleTransfers->count()) {
            return $productConcreteTransfer;
        }

        foreach ($productBundleTransfers as $productBundleTransfer) {
            $this->updateBundleProductIsActive($productBundleTransfer->getBundledProducts());
        }

        return $productConcreteTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductForBundleTransfer[] $productForBundleTransfers
     *
     * @return void
     */
    protected function updateBundleProductIsActive(ArrayObject $productForBundleTransfers): void
    {
        $isActive = true;
        foreach ($productForBundleTransfers as $productForBundleTransfer) {
            if (!$productForBundleTransfer->getIsActive()) {
                $isActive = false;
            }

            $this->saveProductConcrete($productForBundleTransfer->getIdProductBundle(), $isActive);
        }
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
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

    /**
     * @param int $idProductConcrete
     * @param bool $isActive
     *
     * @return void
     */
    protected function saveProductConcrete(int $idProductConcrete, bool $isActive): void
    {
        $productConcreteTransfer = $this->productFacade->findProductConcreteById($idProductConcrete);

        if ($productConcreteTransfer->getIsActive() !== $isActive) {
            $this->productFacade->saveProductConcrete($productConcreteTransfer->setIsActive($isActive));
        }
    }
}
