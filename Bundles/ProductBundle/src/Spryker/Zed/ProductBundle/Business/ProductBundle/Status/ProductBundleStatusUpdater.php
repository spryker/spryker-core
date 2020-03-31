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
     * @var \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface
     */
    protected $productBundleRepository;

    /**
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface $productBundleRepository
     */
    public function __construct(
        ProductBundleToProductFacadeInterface $productFacade,
        ProductBundleRepositoryInterface $productBundleRepository
    ) {
        $this->productFacade = $productFacade;
        $this->productBundleRepository = $productBundleRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function deactivateRelatedProductBundles(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        if ($productConcreteTransfer->getIsActive() === true) {
            return $productConcreteTransfer;
        }

        $productBundleCollectionTransfer = $this->productBundleRepository->getProductBundleCollectionByCriteriaFilter(
            (new ProductBundleCriteriaFilterTransfer())->setIdBundledProduct($productConcreteTransfer->getIdProductConcrete())
        );

        $productBundleTransfers = $productBundleCollectionTransfer->getProductBundles();
        if (!$productBundleTransfers->count()) {
            return $productConcreteTransfer;
        }

        $this->deactivateProductConcrete($productBundleTransfers->getArrayCopy());

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductBundleTransfer[] $productBundleTransfers
     *
     * @return void
     */
    protected function deactivateProductConcrete(array $productBundleTransfers): void
    {
        foreach ($productBundleTransfers as $productBundleTransfer) {
            $this->productFacade->deactivateProductConcrete($productBundleTransfer->getIdProductConcreteBundle());
        }
    }
}
