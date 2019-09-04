<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProduct\FilterStrategy;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Shared\PriceProduct\PriceProductConfig;

class SinglePriceProductFilterMinStrategy implements SinglePriceProductFilterStrategyInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function findOne(array $priceProductTransfers, PriceProductFilterTransfer $priceProductFilterTransfer): ?PriceProductTransfer
    {
        $minPriceProductTransfer = null;

        foreach ($priceProductTransfers as $priceProductTransfer) {
            if ($priceProductTransfer->getPriceTypeName() !== $priceProductFilterTransfer->getPriceTypeName()) {
                continue;
            }

            if ($minPriceProductTransfer === null) {
                $minPriceProductTransfer = $priceProductTransfer;
            }

            if ($this->isMinGreaterThan($minPriceProductTransfer, $priceProductTransfer, $priceProductFilterTransfer->getPriceMode())) {
                $minPriceProductTransfer = $priceProductTransfer;
            }
        }

        return $minPriceProductTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $minPriceProductTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param string $priceMode
     *
     * @return bool
     */
    protected function isMinGreaterThan(PriceProductTransfer $minPriceProductTransfer, PriceProductTransfer $priceProductTransfer, string $priceMode)
    {
        if ($priceMode === PriceProductConfig::PRICE_GROSS_MODE) {
            if ($priceProductTransfer->getMoneyValue()->getGrossAmount() === null) {
                return false;
            }

            if ($minPriceProductTransfer->getMoneyValue()->getGrossAmount() > $priceProductTransfer->getMoneyValue()->getGrossAmount()) {
                return true;
            }

            return false;
        }

        if ($priceProductTransfer->getMoneyValue()->getNetAmount() === null) {
            return false;
        }

        if ($minPriceProductTransfer->getMoneyValue()->getNetAmount() > $priceProductTransfer->getMoneyValue()->getNetAmount()) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $minPriceProductTransfer
     * @param string $priceMode
     *
     * @return bool
     */
    protected function isMinimumPriceHasValueForPriceMode(PriceProductTransfer $minPriceProductTransfer, string $priceMode): bool
    {
        $moneyValueTransfer = $minPriceProductTransfer->getMoneyValue();

        if ($priceMode === PriceProductConfig::PRICE_GROSS_MODE) {
            return $moneyValueTransfer->getGrossAmount() !== null;
        }

        return $moneyValueTransfer->getNetAmount() !== null;
    }
}
