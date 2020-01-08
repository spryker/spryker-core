<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Status;

use Generated\Shared\Transfer\ProductBundleCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductFacadeInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface;

class ProductBundleStatusUpdater implements ProductBundleStatusUpdaterInterface
{
    /**
     * @var \Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface
     */
    protected $productBundleRepository;

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface $productBundleRepository
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductFacadeInterface $productFacade
     */
    public function __construct(
        ProductBundleRepositoryInterface $productBundleRepository,
        ProductBundleToProductFacadeInterface $productFacade
    ) {
        $this->productBundleRepository = $productBundleRepository;
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function updateBundleStatus(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        $productBundleCriteriaFilterTransfer = (new ProductBundleCriteriaFilterTransfer())
            ->setIdBundledProduct($productConcreteTransfer->getIdProductConcrete());

        $productBundleCollectionTransfer = $this->productBundleRepository
            ->getProductBundleCollectionByCriteriaFilter($productBundleCriteriaFilterTransfer);

        $productBundleTransfers = $productBundleCollectionTransfer->getProductBundles();
        if (!$productBundleTransfers->count()) {
            return $productConcreteTransfer;
        }

        foreach ($productBundleTransfers as $productBundleTransfer) {
            $productForBundleTransfers = $this->productBundleRepository
                ->getBundledProductsByIdProductConcrete($productBundleTransfer->getIdProductConcreteBundle());

            $this->updateBundleProductIsActive($productForBundleTransfers);
        }

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductForBundleTransfer[] $productForBundleTransfers
     *
     * @return void
     */
    protected function updateBundleProductIsActive(array $productForBundleTransfers): void
    {
        $isActive = true;
        foreach ($productForBundleTransfers as $productForBundleTransfer) {
            if (!$productForBundleTransfer->getIsActive()) {
                $isActive = false;
            }

            $productConcreteTransfer = $this->productFacade->findProductConcreteById($productForBundleTransfer->getIdProductBundle());
            if ($productConcreteTransfer->getIsActive() !== $isActive) {
                $this->productFacade->saveProductConcrete($productConcreteTransfer->setIsActive($isActive));
            }
        }
    }
}
