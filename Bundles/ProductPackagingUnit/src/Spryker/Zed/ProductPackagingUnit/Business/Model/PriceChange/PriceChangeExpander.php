<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\PriceChange;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitReaderInterface;

class PriceChangeExpander implements PriceChangeExpanderInterface
{
    /**
     * @uses CalculationPriceMode::PRICE_MODE_NET
     */
    protected const PRICE_MODE_NET = 'NET_MODE';

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitReaderInterface
     */
    protected $productPackagingUnitReader;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitReaderInterface $productPackagingUnitReader
     */
    public function __construct(
        ProductPackagingUnitReaderInterface $productPackagingUnitReader
    ) {
        $this->productPackagingUnitReader = $productPackagingUnitReader;
    }

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

        $amountPerQuantity = (int)($itemTransfer->getAmount() / $itemTransfer->getQuantity());

        if ($amountPerQuantity === $defaultAmount) {
            return $itemTransfer;
        }

        if ($quoteTransfer->getPriceMode() === static::PRICE_MODE_NET) {
            $unitNetPrice = $itemTransfer->getUnitNetPrice();
            $newUnitNetPrice = (int)round((($amountPerQuantity / $defaultAmount) * $unitNetPrice));
            $itemTransfer->setUnitNetPrice($newUnitNetPrice);
        } else {
            $unitGrossPrice = $itemTransfer->getUnitGrossPrice();
            $newUnitGrossPrice = (int)round((($amountPerQuantity / $defaultAmount) * $unitGrossPrice));
            $itemTransfer->setUnitGrossPrice($newUnitGrossPrice);
        }

        return $itemTransfer;
    }
}
