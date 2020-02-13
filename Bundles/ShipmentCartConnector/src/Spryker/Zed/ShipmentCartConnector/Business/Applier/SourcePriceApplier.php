<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCartConnector\Business\Applier;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;

class SourcePriceApplier implements SourcePriceApplierInterface
{
    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    public function applySourcePrices(MoneyValueTransfer $moneyValueTransfer, ShipmentMethodTransfer $shipmentMethodTransfer): MoneyValueTransfer
    {
        if (!$this->hasSourcePrices($shipmentMethodTransfer)) {
            return $moneyValueTransfer;
        }

        $sourcePrice = $shipmentMethodTransfer->getSourcePrice();

        if ($sourcePrice->getNetAmount() !== null) {
            $moneyValueTransfer->setNetAmount($sourcePrice->getNetAmount());
        }

        if ($sourcePrice->getGrossAmount() !== null) {
            $moneyValueTransfer->setGrossAmount($sourcePrice->getGrossAmount());
        }

        return $moneyValueTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return bool
     */
    protected function hasSourcePrices(ShipmentMethodTransfer $shipmentMethodTransfer): bool
    {
        $sourcePrice = $shipmentMethodTransfer->getSourcePrice();

        if (!$sourcePrice) {
            return false;
        }

        return $sourcePrice->getGrossAmount() !== null || $sourcePrice->getNetAmount() !== null;
    }
}
