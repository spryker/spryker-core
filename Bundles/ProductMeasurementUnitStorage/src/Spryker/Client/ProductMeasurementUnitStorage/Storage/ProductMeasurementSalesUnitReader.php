<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductMeasurementUnitStorage\Storage;

use ArrayObject;
use Generated\Shared\Transfer\ProductConcreteMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\ProductConcreteProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;

class ProductMeasurementSalesUnitReader implements ProductMeasurementSalesUnitReaderInterface
{
    /**
     * @var \Spryker\Client\ProductMeasurementUnitStorage\Storage\ProductConcreteMeasurementUnitStorageReaderInterface
     */
    protected $productConcreteMeasurementUnitStorageReader;

    /**
     * @var \Spryker\Client\ProductMeasurementUnitStorage\Storage\ProductMeasurementUnitReaderInterface
     */
    protected $productMeasurementUnitReader;

    /**
     * @param \Spryker\Client\ProductMeasurementUnitStorage\Storage\ProductConcreteMeasurementUnitStorageReaderInterface $productConcreteMeasurementUnitStorageReader
     * @param \Spryker\Client\ProductMeasurementUnitStorage\Storage\ProductMeasurementUnitReaderInterface $productMeasurementUnitReader
     */
    public function __construct(
        ProductConcreteMeasurementUnitStorageReaderInterface $productConcreteMeasurementUnitStorageReader,
        ProductMeasurementUnitReaderInterface $productMeasurementUnitReader
    ) {
        $this->productConcreteMeasurementUnitStorageReader = $productConcreteMeasurementUnitStorageReader;
        $this->productMeasurementUnitReader = $productMeasurementUnitReader;
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]|null
     */
    public function findProductMeasurementSalesUnitByIdProduct(int $idProduct): ?array
    {
        $productConcreteMeasurementUnitStorageTransfer = $this->productConcreteMeasurementUnitStorageReader
            ->findProductConcreteMeasurementUnitStorage($idProduct);

        if ($productConcreteMeasurementUnitStorageTransfer === null) {
            return null;
        }

        return $this->getProductMeasurementSalesUnits([$productConcreteMeasurementUnitStorageTransfer]);
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\ProductConcreteProductMeasurementSalesUnitTransfer[]
     */
    public function getProductMeasurementSalesUnitsByProductConcreteIds(array $productConcreteIds): array
    {
        if (!$productConcreteIds) {
            return [];
        }

        $productConcreteMeasurementUnitStorageTransfers = $this->productConcreteMeasurementUnitStorageReader
            ->getProductConcreteMeasurementUnitStorageCollection($productConcreteIds);

        if (!$productConcreteMeasurementUnitStorageTransfers) {
            return [];
        }

        $productMeasurementSalesUnitTransfers = $this->getProductMeasurementSalesUnits($productConcreteMeasurementUnitStorageTransfers);
        $productConcreteProductMeasurementSalesUnitTransfers = [];
        foreach ($productMeasurementSalesUnitTransfers as $idProductConcrete => $productMeasurementSalesUnitTransfer) {
            $productConcreteProductMeasurementSalesUnitTransfers[] = (new ProductConcreteProductMeasurementSalesUnitTransfer())
                ->setIdProductConcrete($idProductConcrete)
                ->setProductMeasurementSalesUnits(new ArrayObject($productMeasurementSalesUnitTransfer));
        }

        return $productConcreteProductMeasurementSalesUnitTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer[] $productConcreteMeasurementUnitStorageTransfers
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]
     */
    protected function getProductMeasurementSalesUnits(array $productConcreteMeasurementUnitStorageTransfers): array
    {
        $productMeasurementSalesUnitTransfers = [];
        $productConcreteMeasurementSalesUnitTransfers = [];
        foreach ($productConcreteMeasurementUnitStorageTransfers as $productConcreteMeasurementUnitStorageTransfer) {
            foreach ($productConcreteMeasurementUnitStorageTransfer->getSalesUnits() as $productConcreteMeasurementSalesUnitTransfer) {
                $productConcreteMeasurementSalesUnitTransfers[] = $productConcreteMeasurementSalesUnitTransfer;
            }
        }

        $indexedProductMeasurementUnitTransfers = $this->getIndexedProductMeasurementUnitTransfers($productConcreteMeasurementSalesUnitTransfers);
        foreach ($productConcreteMeasurementSalesUnitTransfers as $productConcreteMeasurementSalesUnitTransfer) {
            $productMeasurementUnitTransfer = $indexedProductMeasurementUnitTransfers[$productConcreteMeasurementSalesUnitTransfer->getIdProductMeasurementUnit()];
            $productMeasurementSalesUnitTransfer = $this->mapProductMeasurementSalesUnitTransfer(
                $productConcreteMeasurementSalesUnitTransfer,
                new ProductMeasurementSalesUnitTransfer()
            );
            $productMeasurementSalesUnitTransfer->setProductMeasurementUnit($productMeasurementUnitTransfer);
            $productMeasurementSalesUnitTransfers[$productMeasurementSalesUnitTransfer->getFkProduct()][] = $productMeasurementSalesUnitTransfer;
        }

        return $productMeasurementSalesUnitTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteMeasurementSalesUnitTransfer[] $productConcreteMeasurementSalesUnitTransfers
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]
     */
    protected function getIndexedProductMeasurementUnitTransfers(array $productConcreteMeasurementSalesUnitTransfers): array
    {
        $productMeasurementUnitIds = [];
        foreach ($productConcreteMeasurementSalesUnitTransfers as $productConcreteMeasurementSalesUnitTransfer) {
            if ($productConcreteMeasurementSalesUnitTransfer->getIsDisplayed() !== true) {
                continue;
            }

            $productMeasurementUnitIds[] = $productConcreteMeasurementSalesUnitTransfer->getIdProductMeasurementUnit();
        }

        $productMeasurementUnitTransfers = $this->productMeasurementUnitReader->getProductMeasurementUnits($productMeasurementUnitIds);
        $indexedProductMeasurementUnitTransfers = [];
        foreach ($productMeasurementUnitTransfers as $productMeasurementUnitTransfer) {
            $indexedProductMeasurementUnitTransfers[$productMeasurementUnitTransfer->getIdProductMeasurementUnit()] = $productMeasurementUnitTransfer;
        }

        return $indexedProductMeasurementUnitTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteMeasurementSalesUnitTransfer $concreteMeasurementSalesUnitTransfer
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer $measurementSalesUnitTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    protected function mapProductMeasurementSalesUnitTransfer(
        ProductConcreteMeasurementSalesUnitTransfer $concreteMeasurementSalesUnitTransfer,
        ProductMeasurementSalesUnitTransfer $measurementSalesUnitTransfer
    ): ProductMeasurementSalesUnitTransfer {
        $measurementSalesUnitTransfer->fromArray(
            $concreteMeasurementSalesUnitTransfer->toArray(),
            true
        );

        return $measurementSalesUnitTransfer;
    }
}
