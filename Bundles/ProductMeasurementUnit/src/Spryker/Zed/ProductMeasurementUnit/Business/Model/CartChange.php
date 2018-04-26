<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Model;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitReaderInterface;

class CartChange implements CartChangeInterface
{
    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitReaderInterface
     */
    protected $productMeasurementSalesUnitReader;

    /**
     * @param \Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnit\ProductMeasurementSalesUnitReaderInterface $productMeasurementSalesUnitReader
     */
    public function __construct(
        ProductMeasurementSalesUnitReaderInterface $productMeasurementSalesUnitReader
    ) {
        $this->productMeasurementSalesUnitReader = $productMeasurementSalesUnitReader;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandQuantitySalesUnit(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getQuantitySalesUnit() === null) {
                continue;
            }

            $itemTransfer->getQuantitySalesUnit()->requireIdProductMeasurementSalesUnit();

            $productMeasurementSalesUnitTransfer = $this->productMeasurementSalesUnitReader
                ->getProductMeasurementSalesUnitTransfer(
                    $itemTransfer->getQuantitySalesUnit()->getIdProductMeasurementSalesUnit()
                );

            $itemTransfer->setQuantitySalesUnit($productMeasurementSalesUnitTransfer);
        }

        return $cartChangeTransfer;
    }
}
