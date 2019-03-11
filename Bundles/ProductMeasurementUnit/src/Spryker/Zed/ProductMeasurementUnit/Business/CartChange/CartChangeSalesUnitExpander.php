<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\CartChange;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Spryker\Zed\ProductMeasurementUnit\Dependency\Facade\ProductMeasurementUnitToStoreFacadeInterface;
use Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface;

class CartChangeSalesUnitExpander implements CartChangeSalesUnitExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface
     */
    protected $productMeasurementUnitRepository;

    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Dependency\Facade\ProductMeasurementUnitToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface $productMeasurementUnitRepository
     * @param \Spryker\Zed\ProductMeasurementUnit\Dependency\Facade\ProductMeasurementUnitToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        ProductMeasurementUnitRepositoryInterface $productMeasurementUnitRepository,
        ProductMeasurementUnitToStoreFacadeInterface $storeFacade
    ) {
        $this->productMeasurementUnitRepository = $productMeasurementUnitRepository;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItemsWithDefaultQuantitySalesUnit(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        $productConcreteSkus = $this->getProductConcreteSkus($cartChangeTransfer);

        if (!$productConcreteSkus) {
            return $cartChangeTransfer;
        }

        $storeTransfer = $this->storeFacade->getCurrentStore();
        $indexedProductMeasurementSalesUnitIds = $this->productMeasurementUnitRepository->findIndexedStoreAwareDefaultProductMeasurementSalesUnitIds(
            $productConcreteSkus,
            $storeTransfer->getIdStore()
        );

        if ($indexedProductMeasurementSalesUnitIds) {
            return $this->addProductMeasurementSalesUnitToItems($cartChangeTransfer, $indexedProductMeasurementSalesUnitIds);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return string[]
     */
    protected function getProductConcreteSkus(CartChangeTransfer $cartChangeTransfer): array
    {
        $productConcreteSkus = [];
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getQuantitySalesUnit()) {
                $productConcreteSkus[] = $itemTransfer->getSku();
            }
        }

        return $productConcreteSkus;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param int[] $indexedProductMeasurementSalesUnitIds
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function addProductMeasurementSalesUnitToItems(CartChangeTransfer $cartChangeTransfer, array $indexedProductMeasurementSalesUnitIds): CartChangeTransfer
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getQuantitySalesUnit() || !isset($indexedProductMeasurementSalesUnitIds[$itemTransfer->getSku()])) {
                continue;
            }

            $productMeasurementSalesUnitTransfer = (new ProductMeasurementSalesUnitTransfer())
                ->setIdProductMeasurementSalesUnit($indexedProductMeasurementSalesUnitIds[$itemTransfer->getSku()]);
            $itemTransfer->setQuantitySalesUnit($productMeasurementSalesUnitTransfer);
        }

        return $cartChangeTransfer;
    }
}
