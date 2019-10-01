<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\PriceChange;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\DecimalObject\Decimal;

class PriceChangeExpander implements PriceChangeExpanderInterface
{
    /**
     * @uses CalculationPriceMode::PRICE_MODE_NET
     */
    protected const PRICE_MODE_NET = 'NET_MODE';
    protected const DIVISION_SCALE = 10;

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function setCustomAmountPrice(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getAmount()
                || !$itemTransfer->getProductPackagingUnit()
                || !$itemTransfer->getProductPackagingUnit()
                    ->getProductPackagingUnitAmount()
                || !$itemTransfer->getProductPackagingUnit()
                    ->getProductPackagingUnitAmount()
                    ->getIsVariable()
            ) {
                continue;
            }

            $this->expandItem($cartChangeTransfer->getQuote(), $itemTransfer);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function expandItem(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer)
    {
        $defaultAmount = $itemTransfer->getProductPackagingUnit()
            ->getProductPackagingUnitAmount()
            ->getDefaultAmount();

        $amountPerQuantity = $itemTransfer->getAmount()->divide($itemTransfer->getQuantity(), static::DIVISION_SCALE);

        if ($amountPerQuantity->equals($defaultAmount)) {
            return $itemTransfer;
        }

        if ($quoteTransfer->getPriceMode() === static::PRICE_MODE_NET) {
            $unitNetPrice = $itemTransfer->getUnitNetPrice();
            $newUnitNetPrice = $amountPerQuantity->divide($defaultAmount, static::DIVISION_SCALE)->multiply($unitNetPrice);
            $itemTransfer->setUnitNetPrice($newUnitNetPrice->round(0, Decimal::ROUND_HALF_UP)->toInt());
        } else {
            $unitGrossPrice = $itemTransfer->getUnitGrossPrice();
            $newUnitGrossPrice = $amountPerQuantity->divide($defaultAmount, static::DIVISION_SCALE)->multiply($unitGrossPrice);
            $itemTransfer->setUnitGrossPrice($newUnitGrossPrice->round(0, Decimal::ROUND_HALF_UP)->toInt());
        }

        return $itemTransfer;
    }
}
