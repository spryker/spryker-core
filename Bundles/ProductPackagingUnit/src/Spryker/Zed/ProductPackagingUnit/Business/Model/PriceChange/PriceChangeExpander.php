<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\PriceChange;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitReaderInterface;

class PriceChangeExpander implements PriceChangeExpanderInterface
{
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

            $this->expandItem($itemTransfer);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function expandItem(ItemTransfer $itemTransfer)
    {
        $defaultAmount = $itemTransfer->getProductPackagingUnit()
            ->getProductPackagingUnitAmount()
            ->getDefaultAmount();

        if ($itemTransfer->getAmount() === $defaultAmount) {
            return $itemTransfer;
        }

        $originUnitGrossPrice = $itemTransfer->getOriginUnitGrossPrice();
        $newUnitGrossPrice = (int)(($itemTransfer->getAmount() / $defaultAmount) * $originUnitGrossPrice);

        $itemTransfer->setUnitGrossPrice($newUnitGrossPrice);

        return $itemTransfer;
    }
}
