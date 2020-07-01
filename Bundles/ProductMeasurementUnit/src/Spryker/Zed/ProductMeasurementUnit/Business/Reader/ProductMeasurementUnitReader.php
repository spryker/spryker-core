<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Reader;

use Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface;

class ProductMeasurementUnitReader implements ProductMeasurementUnitReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface
     */
    protected $productMeasurementUnitRepository;

    /**
     * @param \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface $productMeasurementUnitRepository
     */
    public function __construct(ProductMeasurementUnitRepositoryInterface $productMeasurementUnitRepository)
    {
        $this->productMeasurementUnitRepository = $productMeasurementUnitRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function filterProductsWithoutMeasurementUnit(array $productConcreteTransfers): array
    {
        if (!$productConcreteTransfers) {
            return [];
        }

        $productConcreteIds = $this->extractProductConcreteIdsFromProductConcreteTransfers($productConcreteTransfers);
        $productAbstractIds = $this->extractProductAbstractIdsFromProductConcreteTransfers($productConcreteTransfers);

        $productMeasurementSalesUnitCounts = $this->productMeasurementUnitRepository->getProductMeasurementSalesUnitCountByProductConcreteIds($productConcreteIds);
        $productMeasurementBaseUnitCounts = $this->productMeasurementUnitRepository->getProductMeasurementBaseUnitCountByProductAbstractIds($productAbstractIds);

        return $this->getEligibleProductConcreteTransfers(
            $productConcreteTransfers,
            $productMeasurementSalesUnitCounts,
            $productMeasurementBaseUnitCounts
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     * @param int[] $productMeasurementSalesUnitCounts
     * @param int[] $productMeasurementBaseUnitCounts
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    protected function getEligibleProductConcreteTransfers(
        array $productConcreteTransfers,
        array $productMeasurementSalesUnitCounts,
        array $productMeasurementBaseUnitCounts
    ): array {
        $eligibleProductConcreteTransfers = [];

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $productConcreteSalesUnitCount = $productMeasurementSalesUnitCounts[$productConcreteTransfer->getIdProductConcrete()] ?? null;
            $productAbstractBaseUnitCount = $productMeasurementBaseUnitCounts[$productConcreteTransfer->getFkProductAbstract()] ?? null;

            if (!$productConcreteSalesUnitCount && !$productAbstractBaseUnitCount) {
                $eligibleProductConcreteTransfers[] = $productConcreteTransfer;
            }
        }

        return $eligibleProductConcreteTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return int[]
     */
    protected function extractProductConcreteIdsFromProductConcreteTransfers(array $productConcreteTransfers): array
    {
        $productConcreteIds = [];

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $productConcreteIds[] = $productConcreteTransfer->requireIdProductConcrete()->getIdProductConcrete();
        }

        return $productConcreteIds;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return int[]
     */
    protected function extractProductAbstractIdsFromProductConcreteTransfers(array $productConcreteTransfers): array
    {
        $productAbstractIds = [];

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $productAbstractIds[] = $productConcreteTransfer->requireFkProductAbstract()->getFkProductAbstract();
        }

        return $productAbstractIds;
    }
}
