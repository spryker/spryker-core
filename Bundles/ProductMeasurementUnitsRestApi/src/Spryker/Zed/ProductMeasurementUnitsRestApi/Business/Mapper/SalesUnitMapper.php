<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitsRestApi\Business\Mapper;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;

class SalesUnitMapper implements SalesUnitMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function mapCartItemRequestTransferToPersistentCartChangeTransfer(
        CartItemRequestTransfer $cartItemRequestTransfer,
        PersistentCartChangeTransfer $persistentCartChangeTransfer
    ): PersistentCartChangeTransfer {
        foreach ($persistentCartChangeTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getSku() !== $cartItemRequestTransfer->getSku()) {
                continue;
            }

            $idProductMeasurementSalesUnit = $cartItemRequestTransfer->getIdProductMeasurementSalesUnit();
            if (!$idProductMeasurementSalesUnit) {
                continue;
            }

            $amount = $cartItemRequestTransfer->getAmount();
            $itemTransfer->setAmount($amount);
            $productMeasurementSalesUnitTransfer = (new ProductMeasurementSalesUnitTransfer())
                ->setIdProductMeasurementSalesUnit($idProductMeasurementSalesUnit)
                ->setValue($amount ? $amount->toInt() : $amount);

            $itemTransfer->setAmountSalesUnit($productMeasurementSalesUnitTransfer);
            $itemTransfer->setQuantitySalesUnit($productMeasurementSalesUnitTransfer);

            break;
        }

        return $persistentCartChangeTransfer;
    }
}
