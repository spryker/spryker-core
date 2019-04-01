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
use Spryker\Zed\ProductPackagingUnit\Dependency\Service\ProductPackagingUnitToUtilPriceServiceInterface;
use Spryker\Zed\ProductPackagingUnit\Dependency\Service\ProductPackagingUnitToUtilQuantityServiceInterface;

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
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Service\ProductPackagingUnitToUtilPriceServiceInterface
     */
    protected $utilPriceService;

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Service\ProductPackagingUnitToUtilQuantityServiceInterface
     */
    protected $utilQuantityService;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit\ProductPackagingUnitReaderInterface $productPackagingUnitReader
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\Service\ProductPackagingUnitToUtilPriceServiceInterface $utilPriceService
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\Service\ProductPackagingUnitToUtilQuantityServiceInterface $utilQuantityService
     */
    public function __construct(
        ProductPackagingUnitReaderInterface $productPackagingUnitReader,
        ProductPackagingUnitToUtilPriceServiceInterface $utilPriceService,
        ProductPackagingUnitToUtilQuantityServiceInterface $utilQuantityService
    ) {
        $this->productPackagingUnitReader = $productPackagingUnitReader;
        $this->utilPriceService = $utilPriceService;
        $this->utilQuantityService = $utilQuantityService;
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

        $amountPerQuantity = $itemTransfer->getAmount() / $itemTransfer->getQuantity();

        if ($this->isQuantityEqual($amountPerQuantity, $defaultAmount)) {
            return $itemTransfer;
        }

        if ($quoteTransfer->getPriceMode() === static::PRICE_MODE_NET) {
            $unitNetPrice = $itemTransfer->getUnitNetPrice();
            $newUnitNetPrice = (($amountPerQuantity / $defaultAmount) * $unitNetPrice);
            $itemTransfer->setUnitNetPrice($this->roundPrice($newUnitNetPrice));
        } else {
            $unitGrossPrice = $itemTransfer->getUnitGrossPrice();
            $newUnitGrossPrice = (($amountPerQuantity / $defaultAmount) * $unitGrossPrice);
            $itemTransfer->setUnitGrossPrice($this->roundPrice($newUnitGrossPrice));
        }

        return $itemTransfer;
    }

    /**
     * @param float $price
     *
     * @return int
     */
    protected function roundPrice(float $price): int
    {
        return $this->utilPriceService->roundPrice($price);
    }

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return bool
     */
    protected function isQuantityEqual(float $firstQuantity, float $secondQuantity): bool
    {
        return $this->utilQuantityService->isQuantityEqual($firstQuantity, $secondQuantity);
    }
}
