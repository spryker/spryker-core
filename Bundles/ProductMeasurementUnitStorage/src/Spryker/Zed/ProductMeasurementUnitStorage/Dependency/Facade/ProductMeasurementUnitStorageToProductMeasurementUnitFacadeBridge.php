<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Facade;

use Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer;

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
     * @return \Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer
     */
    public function getBaseUnitByIdProduct(int $idProduct): ProductMeasurementBaseUnitTransfer
    {
        return $this->productMeasurementUnitFacade->getBaseUnitByIdProduct($idProduct);
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
     * @return string[]
     */
    public function getProductMeasurementUnitCodeMap(): array
    {
        return $this->productMeasurementUnitFacade->getProductMeasurementUnitCodeMap();
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
}
