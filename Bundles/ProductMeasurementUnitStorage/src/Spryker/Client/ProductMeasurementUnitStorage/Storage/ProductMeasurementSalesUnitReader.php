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

        $productMeasurementSalesUnitTransfers = $this->getProductMeasurementSalesUnits(
            [$productConcreteMeasurementUnitStorageTransfer]
        );

        return $this->updateIsDefault(current($productMeasurementSalesUnitTransfers));
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

        $indexedProductMeasurementSalesUnitTransfers = $this->getProductMeasurementSalesUnits(
            $productConcreteMeasurementUnitStorageTransfers
        );
        $productConcreteProductMeasurementSalesUnitTransfers = [];
        foreach ($indexedProductMeasurementSalesUnitTransfers as $idProductConcrete => $productMeasurementSalesUnitTransfers) {
            $productMeasurementSalesUnitTransfers = $this->updateIsDefault($productMeasurementSalesUnitTransfers);
            $productConcreteProductMeasurementSalesUnitTransfers[] = (new ProductConcreteProductMeasurementSalesUnitTransfer())
                ->setIdProductConcrete($idProductConcrete)
                ->setProductMeasurementSalesUnits(new ArrayObject($productMeasurementSalesUnitTransfers));
        }

        return $productConcreteProductMeasurementSalesUnitTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer[] $productConcreteMeasurementUnitStorageTransfers
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[][]
     */
    protected function getProductMeasurementSalesUnits(array $productConcreteMeasurementUnitStorageTransfers): array
    {
        $productConcreteMeasurementSalesUnitTransfers = [];
        $productMeasurementUnitIds = [];
        foreach ($productConcreteMeasurementUnitStorageTransfers as $productConcreteMeasurementUnitStorageTransfer) {
            foreach ($productConcreteMeasurementUnitStorageTransfer->getSalesUnits() as $productConcreteMeasurementSalesUnitTransfer) {
                if ($productConcreteMeasurementSalesUnitTransfer->getIsDisplayed() !== true) {
                    continue;
                }

                $productConcreteMeasurementSalesUnitTransfers[] = $productConcreteMeasurementSalesUnitTransfer;
                $productMeasurementUnitIds[] = $productConcreteMeasurementSalesUnitTransfer->getIdProductMeasurementUnit();
            }
        }

        return $this->convertToProductMeasurementSalesUnitTransfers(
            $productConcreteMeasurementSalesUnitTransfers,
            $this->getIndexedProductMeasurementUnitTransfers($productMeasurementUnitIds)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteMeasurementSalesUnitTransfer[] $productConcreteMeasurementSalesUnitTransfers
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[] $indexedProductMeasurementUnitTransfers
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[][]
     */
    protected function convertToProductMeasurementSalesUnitTransfers(
        array $productConcreteMeasurementSalesUnitTransfers,
        array $indexedProductMeasurementUnitTransfers
    ): array {
        $productMeasurementSalesUnitTransfers = [];
        foreach ($productConcreteMeasurementSalesUnitTransfers as $productConcreteMeasurementSalesUnitTransfer) {
            $idProductMeasurementUnit = $productConcreteMeasurementSalesUnitTransfer->getIdProductMeasurementUnit();
            $productMeasurementUnitTransfer = $indexedProductMeasurementUnitTransfers[$idProductMeasurementUnit];
            $productMeasurementSalesUnitTransfer = $this->mapProductMeasurementSalesUnitTransfer(
                $productConcreteMeasurementSalesUnitTransfer,
                new ProductMeasurementSalesUnitTransfer()
            );
            $productMeasurementSalesUnitTransfer->setProductMeasurementUnit($productMeasurementUnitTransfer);
            $productMeasurementSalesUnitTransfers[$productMeasurementSalesUnitTransfer->getFkProduct()][]
                = $productMeasurementSalesUnitTransfer;
        }

        return $productMeasurementSalesUnitTransfers;
    }

    /**
     * @param int[] $productMeasurementUnitIds
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]
     */
    protected function getIndexedProductMeasurementUnitTransfers(array $productMeasurementUnitIds): array
    {
        $productMeasurementUnitTransfers = $this->productMeasurementUnitReader
            ->getProductMeasurementUnits($productMeasurementUnitIds);
        $indexedProductMeasurementUnitTransfers = [];
        foreach ($productMeasurementUnitTransfers as $productMeasurementUnitTransfer) {
            $indexedProductMeasurementUnitTransfers[$productMeasurementUnitTransfer->getIdProductMeasurementUnit()]
                = $productMeasurementUnitTransfer;
        }

        return $indexedProductMeasurementUnitTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[] $productMeasurementSalesUnitTransfers
     *
     * @return array
     */
    protected function updateIsDefault(array $productMeasurementSalesUnitTransfers): array
    {
        $isDefaultFound = false;
        foreach ($productMeasurementSalesUnitTransfers as $productMeasurementSalesUnitTransfer) {
            if ($productMeasurementSalesUnitTransfer->getIsDefault()) {
                $isDefaultFound = true;
            }
        }

        if (!$isDefaultFound) {
            $productMeasurementSalesUnitTransfers[0]->setIsDefault(true);
        }

        return $productMeasurementSalesUnitTransfers;
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
