<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Status;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductFacadeInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface;

class ProductBundleStatus implements ProductBundleStatusInterface
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
     * @return void
     */
    public function updateBundleStatus(ProductConcreteTransfer $productConcreteTransfer): void
    {
        $productForBundleTransfers = $this->productBundleRepository
            ->getBundledProductsByIdProduct($productConcreteTransfer->getIdProductConcrete());

        if (!$productForBundleTransfers) {
            return;
        }

        foreach ($productForBundleTransfers as $productForBundleTransfer) {
            $bundledProducts = $this->productBundleRepository
                ->getBundledProductsByIdProductConcrete($productForBundleTransfer->getIdProductBundle());

            $this->updateBundleProductIsActive($bundledProducts);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductForBundleTransfer[] $bundledProducts
     *
     * @return void
     */
    protected function updateBundleProductIsActive(array $bundledProducts): void
    {
        $isActive = true;
        foreach ($bundledProducts as $bundledProduct) {
            if (!$bundledProduct->getIsActive()) {
                $isActive = false;
            }

            $productConcreteTransfer = $this->productFacade->findProductConcreteById($bundledProduct->getIdProductBundle());
            if ($productConcreteTransfer->getIsActive() !== $isActive) {
                $this->productFacade->saveProductConcrete($productConcreteTransfer->setIsActive($isActive));
            }
        }
    }
}
