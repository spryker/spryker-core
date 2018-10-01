<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Facade;

class ProductMeasurementUnitStorageToProductMeasurementUnitFacadeBridge implements ProductMeasurementUnitStorageToProductMeasurementUnitFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Business\ProductMeasurementUnitFacadeInterface
     */
    protected $productMeasurementUnitFacade;

    /**
     * @param \Spryker\Zed\ProductMeasurementUnit\Business\ProductMeasurementUnitFacadeInterface $productMeasurementUnitFacade
     */
    public function __construct($productMeasurementUnitFacade)
    {
        $this->productMeasurementUnitFacade = $productMeasurementUnitFacade;
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]
     */
    public function getSalesUnitsByIdProduct(int $idProduct): array
    {
        return $this->productMeasurementUnitFacade->getSalesUnitsByIdProduct($idProduct);
    }

    /**
     * @param int[] $salesUnitsIds
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]
     */
    public function getSalesUnitsByIds(array $salesUnitsIds): array
    {
        return $this->productMeasurementUnitFacade->getSalesUnitsByIds($salesUnitsIds);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]
     */
    public function getSalesUnits(): array
    {
        return $this->productMeasurementUnitFacade->getSalesUnits();
    }

    /**
     * @param int[] $productMeasurementUnitIds
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]
     */
    public function findProductMeasurementUnitTransfers(array $productMeasurementUnitIds): array
    {
        return $this->productMeasurementUnitFacade->findProductMeasurementUnitTransfers($productMeasurementUnitIds);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]
     */
    public function findAllProductMeasurementUnitTransfers(): array
    {
        return $this->productMeasurementUnitFacade->findAllProductMeasurementUnitTransfers();
    }
}
