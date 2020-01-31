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

            $itemTransfer->setAmount($cartItemRequestTransfer->getAmount());
            $itemTransfer->setAmountSalesUnit(
                (new ProductMeasurementSalesUnitTransfer())
                    ->setIdProductMeasurementSalesUnit($cartItemRequestTransfer->getIdProductMeasurementSalesUnit())
                    ->setValue($cartItemRequestTransfer->getAmount()->toInt())
            );

            break;
        }

        return $persistentCartChangeTransfer;
    }
}
