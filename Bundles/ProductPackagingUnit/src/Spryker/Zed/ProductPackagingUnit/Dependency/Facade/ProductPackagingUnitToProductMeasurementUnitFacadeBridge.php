<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Dependency\Facade;

use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;

class ProductPackagingUnitToProductMeasurementUnitFacadeBridge implements ProductPackagingUnitToProductMeasurementUnitFacadeInterface
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
     * @param int $idProductMeasurementSalesUnit
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    public function getProductMeasurementSalesUnitTransfer(int $idProductMeasurementSalesUnit): ProductMeasurementSalesUnitTransfer
    {
        return $this->productMeasurementUnitFacade->getProductMeasurementSalesUnitTransfer($idProductMeasurementSalesUnit);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    public function translateProductMeasurementSalesUnit(
        ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer
    ): ProductMeasurementSalesUnitTransfer {
        return $this->productMeasurementUnitFacade->translateProductMeasurementSalesUnit($productMeasurementSalesUnitTransfer);
    }

    /**
     * @param int $idProduct
     *
     * @return list<\Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer>
     */
    public function getSalesUnitsByIdProduct(int $idProduct): array
    {
        return $this->productMeasurementUnitFacade->getSalesUnitsByIdProduct($idProduct);
    }
}
