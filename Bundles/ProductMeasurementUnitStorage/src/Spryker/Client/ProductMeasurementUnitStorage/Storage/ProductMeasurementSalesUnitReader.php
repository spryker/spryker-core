<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductMeasurementUnitStorage\Storage;

use ArrayObject;
use Generated\Shared\Transfer\ProductConcreteMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer;
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

        return $this->getProductMeasurementSalesUnits($productConcreteMeasurementUnitStorageTransfer);
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

        $productConcreteProductMeasurementSalesUnitTransfers = [];
        foreach ($productConcreteMeasurementUnitStorageTransfers as $idProductConcrete => $productConcreteMeasurementUnitStorageTransfer) {
            $productConcreteProductMeasurementSalesUnitTransfers[] = (new ProductConcreteProductMeasurementSalesUnitTransfer())
                ->setIdProductConcrete($idProductConcrete)
                ->setProductMeasurementSalesUnits(
                    new ArrayObject($this->getProductMeasurementSalesUnits($productConcreteMeasurementUnitStorageTransfer))
                );
        }

        return $productConcreteProductMeasurementSalesUnitTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer $productConcreteMeasurementUnitStorageTransfer
     *
     * @return array
     */
    protected function getProductMeasurementSalesUnits(ProductConcreteMeasurementUnitStorageTransfer $productConcreteMeasurementUnitStorageTransfer): array
    {
        $productMeasurementSalesUnits = [];
        $defaultFound = false;
        foreach ($productConcreteMeasurementUnitStorageTransfer->getSalesUnits() as $productConcreteMeasurementSalesUnitTransfer) {
            if ($productConcreteMeasurementSalesUnitTransfer->getIsDisplayed() !== true) {
                continue;
            }

            if ($productConcreteMeasurementSalesUnitTransfer->getIsDefault()) {
                $defaultFound = true;
            }

            $productMeasurementUnitTransfer = $this->productMeasurementUnitReader->findProductMeasurementUnit(
                $productConcreteMeasurementSalesUnitTransfer->getIdProductMeasurementUnit()
            );

            $productMeasurementSalesUnit = $this->mapProductMeasurementSalesUnitTransfer(
                $productConcreteMeasurementSalesUnitTransfer,
                new ProductMeasurementSalesUnitTransfer()
            );

            $productMeasurementSalesUnit->setProductMeasurementUnit($productMeasurementUnitTransfer);
            $productMeasurementSalesUnits[] = $productMeasurementSalesUnit;
        }

        if ($defaultFound !== true && count($productMeasurementSalesUnits)) {
            $productMeasurementSalesUnits[0]->setIsDefault(true);
        }

        return $productMeasurementSalesUnits;
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
