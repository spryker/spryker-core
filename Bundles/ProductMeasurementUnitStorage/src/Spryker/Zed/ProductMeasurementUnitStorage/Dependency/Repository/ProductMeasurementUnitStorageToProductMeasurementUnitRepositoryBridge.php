<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Repository;

class ProductMeasurementUnitStorageToProductMeasurementUnitRepositoryBridge implements ProductMeasurementUnitStorageToProductMeasurementUnitRepositoryInterface
{
    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface
     */
    protected $productMeasurementUnitRepository;

    /**
     * @param \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface $productMeasurementUnitRepository
     */
    public function __construct($productMeasurementUnitRepository)
    {
        $this->productMeasurementUnitRepository = $productMeasurementUnitRepository;
    }

    /**
     * @param int[] $productMeasurementUnitIds
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer[]
     */
    public function getProductMeasurementUnitEntities(array $productMeasurementUnitIds)
    {
        return $this->productMeasurementUnitRepository->getProductMeasurementUnitEntities($productMeasurementUnitIds);
    }

    /**
     * @return string[]
     */
    public function getProductMeasurementUnitCodeMap()
    {
        return $this->productMeasurementUnitRepository->getProductMeasurementUnitCodeMap();
    }
}
