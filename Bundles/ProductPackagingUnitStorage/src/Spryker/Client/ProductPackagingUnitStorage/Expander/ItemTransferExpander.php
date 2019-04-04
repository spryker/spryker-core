<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPackagingUnitStorage\Expander;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer;
use Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Spryker\Client\ProductPackagingUnitStorage\Dependency\Client\ProductPackagingUnitStorageToProductMeasurementUnitStorageClientInterface;
use Spryker\Client\ProductPackagingUnitStorage\Storage\ProductPackagingUnitStorageReaderInterface;

class ItemTransferExpander implements ItemTransferExpanderInterface
{
    /**
     * @var \Spryker\Client\ProductPackagingUnitStorage\Storage\ProductPackagingUnitStorageReaderInterface
     */
    protected $productPackagingUnitStorageReader;

    /**
     * @var \Spryker\Client\ProductPackagingUnitStorage\Dependency\Client\ProductPackagingUnitStorageToProductMeasurementUnitStorageClientInterface
     */
    protected $productMeasurementUnitStorageClient;

    /**
     * @param \Spryker\Client\ProductPackagingUnitStorage\Storage\ProductPackagingUnitStorageReaderInterface $productPackagingUnitStorageReader
     * @param \Spryker\Client\ProductPackagingUnitStorage\Dependency\Client\ProductPackagingUnitStorageToProductMeasurementUnitStorageClientInterface $productMeasurementUnitStorageClient
     */
    public function __construct(
        ProductPackagingUnitStorageReaderInterface $productPackagingUnitStorageReader,
        ProductPackagingUnitStorageToProductMeasurementUnitStorageClientInterface $productMeasurementUnitStorageClient
    ) {
        $this->productPackagingUnitStorageReader = $productPackagingUnitStorageReader;
        $this->productMeasurementUnitStorageClient = $productMeasurementUnitStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function expandWithDefaultPackagingUnit(ItemTransfer $itemTransfer): ItemTransfer
    {
        $this->assertItemTransfer($itemTransfer);

        $productAbstractPackagingStorageTransfer = $this->productPackagingUnitStorageReader
            ->findProductAbstractPackagingById((int)$itemTransfer->getIdProductAbstract());
        if ($productAbstractPackagingStorageTransfer === null) {
            return $itemTransfer;
        }

        $productConcretePackagingStorageTransfer = $this->selectProductConcretePackagingStorageTransfer(
            $productAbstractPackagingStorageTransfer,
            $itemTransfer
        );
        if ($productConcretePackagingStorageTransfer === null) {
            return $itemTransfer;
        }
        if (!$this->hasAmount($productConcretePackagingStorageTransfer, $productAbstractPackagingStorageTransfer)) {
            return $itemTransfer;
        }

        $quantityProductMeasurementSalesUnitTransfer = $this->findDefaultProductMeasurementSalesUnitTransfer(
            (int)$productConcretePackagingStorageTransfer->getIdProduct()
        );
        $amountProductMeasurementSalesUnitTransfer = $this->findDefaultProductMeasurementSalesUnitTransfer(
            (int)$productAbstractPackagingStorageTransfer->getLeadProduct()
        );
        if ($quantityProductMeasurementSalesUnitTransfer === null || $amountProductMeasurementSalesUnitTransfer === null) {
            return $itemTransfer;
        }

        $itemTransfer->setQuantitySalesUnit($quantityProductMeasurementSalesUnitTransfer);
        $itemTransfer->setAmountSalesUnit($amountProductMeasurementSalesUnitTransfer);
        $itemTransfer->setAmount(
            $productConcretePackagingStorageTransfer->getDefaultAmount() * $itemTransfer->getQuantity()
        );

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function assertItemTransfer(ItemTransfer $itemTransfer): void
    {
        $itemTransfer
            ->requireIdProductAbstract()
            ->requireProductConcrete();

        /** @var \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer */
        $productConcreteTransfer = $itemTransfer->getProductConcrete();
        $productConcreteTransfer
            ->requireIdProductConcrete();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer $productAbstractPackagingStorageTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer|null
     */
    protected function selectProductConcretePackagingStorageTransfer(
        ProductAbstractPackagingStorageTransfer $productAbstractPackagingStorageTransfer,
        ItemTransfer $itemTransfer
    ): ?ProductConcretePackagingStorageTransfer {
        /** @var \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer */
        $productConcreteTransfer = $itemTransfer->getProductConcrete();
        $idProduct = $productConcreteTransfer->getIdProductConcrete();

        foreach ($productAbstractPackagingStorageTransfer->getTypes() as $productConcretePackagingStorageTransfer) {
            if ($productConcretePackagingStorageTransfer->getIdProduct() === $idProduct) {
                return $productConcretePackagingStorageTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer $productConcretePackagingStorageTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer $productAbstractPackagingStorageTransfer
     *
     * @return bool
     */
    protected function hasAmount(
        ProductConcretePackagingStorageTransfer $productConcretePackagingStorageTransfer,
        ProductAbstractPackagingStorageTransfer $productAbstractPackagingStorageTransfer
    ): bool {
        if ($productConcretePackagingStorageTransfer->getHasLeadProduct() !== true) {
            return false;
        }

        if ($productConcretePackagingStorageTransfer->getIdProduct() === $productAbstractPackagingStorageTransfer->getLeadProduct()) {
            return false;
        }

        return true;
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer|null
     */
    protected function findDefaultProductMeasurementSalesUnitTransfer(int $idProduct): ?ProductMeasurementSalesUnitTransfer
    {
        $productMeasurementSalesUnitTransfers = $this->productMeasurementUnitStorageClient->findProductMeasurementSalesUnitByIdProduct($idProduct);
        if ($productMeasurementSalesUnitTransfers === null) {
            return null;
        }

        foreach ($productMeasurementSalesUnitTransfers as $productMeasurementSalesUnitTransfer) {
            if ($productMeasurementSalesUnitTransfer->getIsDefault() === true) {
                return $productMeasurementSalesUnitTransfer;
            }
        }

        return null;
    }
}
