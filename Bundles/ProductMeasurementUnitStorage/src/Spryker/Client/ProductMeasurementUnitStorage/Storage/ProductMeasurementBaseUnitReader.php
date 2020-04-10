<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductMeasurementUnitStorage\Storage;

use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;

class ProductMeasurementBaseUnitReader implements ProductMeasurementBaseUnitReaderInterface
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
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer|null
     */
    public function findProductMeasurementBaseUnitByIdProduct(int $idProduct): ?ProductMeasurementUnitTransfer
    {
        $productConcreteMeasurementUnitStorageTransfer = $this->productConcreteMeasurementUnitStorageReader
            ->findProductConcreteMeasurementUnitStorage($idProduct);

        if ($productConcreteMeasurementUnitStorageTransfer === null) {
            return null;
        }

        return $this->productMeasurementUnitReader->findProductMeasurementUnit(
            $productConcreteMeasurementUnitStorageTransfer->getBaseUnit()->getIdProductMeasurementUnit()
        );
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]
     */
    public function getProductMeasurementBaseUnitsByProductConcreteIds(array $productConcreteIds): array
    {
        if (!$productConcreteIds) {
            return [];
        }

        $productConcreteMeasurementUnitStorageTransfers = $this->productConcreteMeasurementUnitStorageReader
            ->getProductConcreteMeasurementUnitStorageCollection($productConcreteIds);

        if (!$productConcreteMeasurementUnitStorageTransfers) {
            return [];
        }

        $productMeasurementUnitIds = [];
        foreach ($productConcreteMeasurementUnitStorageTransfers as $idProductConcrete => $productConcreteMeasurementUnitStorageTransfer) {
            $productMeasurementUnitIds[$idProductConcrete] = $productConcreteMeasurementUnitStorageTransfer->getBaseUnit()->getIdProductMeasurementUnit();
        }

        if (!$productMeasurementUnitIds) {
            return [];
        }

        $productMeasurementUnitTransfers = $this->productMeasurementUnitReader
            ->getProductMeasurementUnits($productMeasurementUnitIds);

        return $this->getIndexedProductMeasurementUnitTransfers($productMeasurementUnitTransfers, $productMeasurementUnitIds);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function expandProductConcreteTransferWithBaseMeasurementUnit(array $productConcreteTransfers): array
    {
        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $productConcreteTransfer->requireIdProductConcrete();

            $productMeasurementUnitTransfer = $this->findProductMeasurementBaseUnitByIdProduct($productConcreteTransfer->getIdProductConcrete());

            if ($productMeasurementUnitTransfer === null) {
                continue;
            }

            $productConcreteTransfer->setBaseMeasurementUnit($productMeasurementUnitTransfer);
        }

        return $productConcreteTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[] $productMeasurementUnitTransfers
     * @param array $productMeasurementUnitIds
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]
     */
    protected function getIndexedProductMeasurementUnitTransfers(
        array $productMeasurementUnitTransfers,
        array $productMeasurementUnitIds
    ): array {
        $indexedProductMeasurementUnitTransfers = [];
        foreach ($productMeasurementUnitTransfers as $productMeasurementUnitTransfer) {
            $idProductConcrete = array_search($productMeasurementUnitTransfer->getIdProductMeasurementUnit(), $productMeasurementUnitIds);
            $indexedProductMeasurementUnitTransfers[$idProductConcrete] = $productMeasurementUnitTransfer;
        }

        return $indexedProductMeasurementUnitTransfers;
    }
}
