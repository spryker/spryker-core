<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPackagingUnitStorage\Expander;

use Generated\Shared\Transfer\ItemTransfer;
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

        $productPackagingUnitStorageTransfer = $this->productPackagingUnitStorageReader->findProductPackagingUnitById(
            (int)$itemTransfer->getProductConcrete()->getIdProductConcrete()
        );

        if ($productPackagingUnitStorageTransfer === null) {
            return $itemTransfer;
        }

        if ($productPackagingUnitStorageTransfer->getIdLeadProduct() !== $productPackagingUnitStorageTransfer->getIdProduct()) {
            $quantityProductMeasurementSalesUnitTransfer = $this->findDefaultProductMeasurementSalesUnitTransfer(
                (int)$productPackagingUnitStorageTransfer->getIdProduct()
            );

            $itemTransfer->setQuantitySalesUnit($quantityProductMeasurementSalesUnitTransfer);
        }

        $amountProductMeasurementSalesUnitTransfer = $this->findDefaultProductMeasurementSalesUnitTransfer(
            (int)$productPackagingUnitStorageTransfer->getIdLeadProduct()
        );

        if ($amountProductMeasurementSalesUnitTransfer !== null) {
            $itemTransfer->setAmountSalesUnit($amountProductMeasurementSalesUnitTransfer);
        }

        $itemTransfer->setAmount(
            $productPackagingUnitStorageTransfer->getDefaultAmount()->multiply($itemTransfer->getQuantity())
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
            ->requireProductConcrete()
            ->getProductConcrete()
                ->requireIdProductConcrete();
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
