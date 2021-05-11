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

            /** @var string $priceMode */
            $priceMode = $priceProductFilterTransfer->requirePriceMode()->getPriceMode();

            if ($this->isMinGreaterThan($priceMode, $priceProductTransfer, $minPriceProductTransfer)) {
                $minPriceProductTransfer = $priceProductTransfer;
            }
        }

        return $minPriceProductTransfer;
    }

    /**
     * @param string $priceMode
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer|null $minPriceProductTransfer
     *
     * @return bool
     */
    protected function isMinGreaterThan(string $priceMode, PriceProductTransfer $priceProductTransfer, ?PriceProductTransfer $minPriceProductTransfer = null)
    {
        /** @var \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer */
        $moneyValueTransfer = $priceProductTransfer->requireMoneyValue()->getMoneyValue();

        if ($priceMode === PriceProductConfig::PRICE_GROSS_MODE) {
            if ($moneyValueTransfer->getGrossAmount() === null) {
                return false;
            }

            if (!$minPriceProductTransfer) {
                return true;
            }

            /** @var \Generated\Shared\Transfer\MoneyValueTransfer $minPriceProductMoneyValueTransfer */
            $minPriceProductMoneyValueTransfer = $minPriceProductTransfer->requireMoneyValue()->getMoneyValue();
            if ($minPriceProductMoneyValueTransfer->getGrossAmount() > $moneyValueTransfer->getGrossAmount()) {
                return true;
            }

            return false;
        }

        if ($moneyValueTransfer->getNetAmount() === null) {
            return false;
        }

        if (!$minPriceProductTransfer) {
            return true;
        }

        /** @var \Generated\Shared\Transfer\MoneyValueTransfer $minPriceProductMoneyValueTransfer */
        $minPriceProductMoneyValueTransfer = $minPriceProductTransfer->requireMoneyValue()->getMoneyValue();
        if ($minPriceProductMoneyValueTransfer->getNetAmount() > $moneyValueTransfer->getNetAmount()) {
            return true;
        }

        return false;
    }
}
