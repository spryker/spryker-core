<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPackagingUnitStorage\ItemTransferPackagingUnitExpander;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer;
use Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Spryker\Client\ProductPackagingUnitStorage\Dependency\Client\ProductPackagingUnitStorageToProductMeasurementUnitStorageClientInterface;

class ItemTransferPackagingUnitExpander implements ItemTransferPackagingUnitExpanderInterface
{
    /**
     * @var \Spryker\Client\ProductPackagingUnitStorage\Dependency\Client\ProductPackagingUnitStorageToProductMeasurementUnitStorageClientInterface
     */
    protected $productMeasurementUnitStorageClient;

    /**
     * @param \Spryker\Client\ProductPackagingUnitStorage\Dependency\Client\ProductPackagingUnitStorageToProductMeasurementUnitStorageClientInterface $productMeasurementUnitStorageClient
     */
    public function __construct(ProductPackagingUnitStorageToProductMeasurementUnitStorageClientInterface $productMeasurementUnitStorageClient)
    {
        $this->productMeasurementUnitStorageClient = $productMeasurementUnitStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer|null $productAbstractPackagingStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function expand(ItemTransfer $itemTransfer, ?ProductAbstractPackagingStorageTransfer $productAbstractPackagingStorageTransfer): ItemTransfer
    {
        if ($productAbstractPackagingStorageTransfer === null || !$this->validateItemTransfer($itemTransfer)) {
            return $itemTransfer;
        }

        /** @var \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer */
        $productConcreteTransfer = $itemTransfer->getProductConcrete();
        $productConcretePackagingStorageTransfer = $this->extractProductConcretePackagingStorageTransfer($productAbstractPackagingStorageTransfer, $productConcreteTransfer);

        if ($productConcretePackagingStorageTransfer === null) {
            return $itemTransfer;
        }

        $this->recalculateAmountIfNeeded($itemTransfer, $productAbstractPackagingStorageTransfer, $productConcretePackagingStorageTransfer);

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer $productAbstractPackagingStorageTransfer
     * @param \Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer $productConcretePackagingStorageTransfer
     *
     * @return void
     */
    protected function recalculateAmountIfNeeded(
        ItemTransfer $itemTransfer,
        ProductAbstractPackagingStorageTransfer $productAbstractPackagingStorageTransfer,
        ProductConcretePackagingStorageTransfer $productConcretePackagingStorageTransfer
    ): void {
        $productMeausurementSalesUnitTransfer = $this->getDefaultProductMeasurementSalesUnitTransfer((int)$productConcretePackagingStorageTransfer->getIdProduct());

        if ($productConcretePackagingStorageTransfer->getHasLeadProduct() !== true
            || $productConcretePackagingStorageTransfer->getIdProduct() === $productAbstractPackagingStorageTransfer->getLeadProduct()
            || $productMeausurementSalesUnitTransfer === null) {
            return;
        }

        $itemTransfer->setAmountSalesUnit($productMeausurementSalesUnitTransfer);
        $itemTransfer->setQuantitySalesUnit($productMeausurementSalesUnitTransfer);
        $itemTransfer->setAmount(
            $productConcretePackagingStorageTransfer->getDefaultAmount() * $itemTransfer->getQuantity()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function validateItemTransfer(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getIdProductAbstract() !== null
            && $itemTransfer->getProductConcrete() !== null
            && $itemTransfer->getProductConcrete()->getIdProductConcrete() !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer $productAbstractPackagingStorageTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer|null
     */
    protected function extractProductConcretePackagingStorageTransfer(ProductAbstractPackagingStorageTransfer $productAbstractPackagingStorageTransfer, ProductConcreteTransfer $productConcreteTransfer): ?ProductConcretePackagingStorageTransfer
    {
        if (!$productAbstractPackagingStorageTransfer->getTypes()->count()) {
            return null;
        }

        foreach ($productAbstractPackagingStorageTransfer->getTypes() as $productConcretePackagingStorageTransfer) {
            if ($productConcretePackagingStorageTransfer->getIdProduct() === $productConcreteTransfer->getIdProductConcrete()) {
                return $productConcretePackagingStorageTransfer;
            }
        }

        return null;
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer|null
     */
    protected function getDefaultProductMeasurementSalesUnitTransfer(int $idProduct): ?ProductMeasurementSalesUnitTransfer
    {
        $productMeausurementSalesUnitTransfers = $this->productMeasurementUnitStorageClient->findProductMeasurementSalesUnitByIdProduct($idProduct);

        if ($productMeausurementSalesUnitTransfers === null) {
            return null;
        }

        foreach ($productMeausurementSalesUnitTransfers as $productMeausurementSalesUnitTransfer) {
            if ($productMeausurementSalesUnitTransfer->getIsDefault() === true) {
                return $productMeausurementSalesUnitTransfer;
            }
        }

        return null;
    }
}
