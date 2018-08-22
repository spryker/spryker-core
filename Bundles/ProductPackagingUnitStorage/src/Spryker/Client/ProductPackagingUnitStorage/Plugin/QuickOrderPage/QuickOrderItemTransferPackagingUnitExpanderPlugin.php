<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPackagingUnitStorage\Plugin\QuickOrderPage;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer;
use Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Zed\QuickOrderExtension\Dependency\Plugin\QuickOrderItemTransferExpanderPluginInterface;

/**
 * @method \Spryker\Client\ProductPackagingUnitStorage\ProductPackagingUnitStorageClientInterface getClient()
 * @method \Spryker\Client\ProductPackagingUnitStorage\ProductPackagingUnitStorageFactory getFactory()
 */
class QuickOrderItemTransferPackagingUnitExpanderPlugin extends AbstractPlugin implements QuickOrderItemTransferExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function expand(ItemTransfer $itemTransfer): ItemTransfer
    {
        if (!$this->validateItemTransfer($itemTransfer)) {
            return $itemTransfer;
        }

        $productAbstractPackagingStorageTransfer = $this->getClient()
            ->findProductAbstractPackagingById((int)$itemTransfer->getIdProductAbstract());

        if ($productAbstractPackagingStorageTransfer === null) {
            return $itemTransfer;
        }

        $productConcretePackagingStorageTransfer = $this->extractProductConcretePackagingStorageTransfer($productAbstractPackagingStorageTransfer, $itemTransfer->getProductConcrete());

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
        $itemTransfer->setAmount(
            $productConcretePackagingStorageTransfer->getDefaultAmount() * $itemTransfer->getQuantity()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function validateItemTransfer(ItemTransfer $itemTransfer)
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
    protected function extractProductConcretePackagingStorageTransfer(ProductAbstractPackagingStorageTransfer $productAbstractPackagingStorageTransfer, ProductConcreteTransfer $productConcreteTransfer)
    {
        if ($productAbstractPackagingStorageTransfer->getTypes() === null) {
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
    protected function getDefaultProductMeasurementSalesUnitTransfer(int $idProduct)
    {
        $productMeausurementSalesUnitTransfers = $this->getFactory()
            ->getProductMeasurementUnitStorageClient()
            ->findProductMeasurementSalesUnitByIdProduct($idProduct);

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
