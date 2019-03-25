<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Cart;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductPackagingUnit\Dependency\Service\ProductPackagingUnitToUtilQuantityServiceInterface;

class ProductPackagingUnitCartOperation implements ProductPackagingUnitCartOperationInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Service\ProductPackagingUnitToUtilQuantityServiceInterface
     */
    protected $utilQuantityService;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\Service\ProductPackagingUnitToUtilQuantityServiceInterface $utilQuantityService
     */
    public function __construct(ProductPackagingUnitToUtilQuantityServiceInterface $utilQuantityService)
    {
        $this->utilQuantityService = $utilQuantityService;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addItemToQuote(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $currentItemTransfer) {
            if ($this->getItemIdentifier($currentItemTransfer) === $this->getItemIdentifier($itemTransfer)) {
                $newQuantity = $this->sumQuantities(
                    $currentItemTransfer->getQuantity(),
                    $itemTransfer->getQuantity()
                );

                $currentItemTransfer->setQuantity($newQuantity);

                $currentItemTransfer->setAmount(
                    $currentItemTransfer->getAmount() + $itemTransfer->getAmount()
                );

                return $quoteTransfer;
            }
        }

        $quoteTransfer->getItems()->append($itemTransfer);

        return $quoteTransfer;
    }

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return float
     */
    protected function sumQuantities(float $firstQuantity, float $secondQuantity): float
    {
        return $this->utilQuantityService->sumQuantities($firstQuantity, $secondQuantity);
    }

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return float
     */
    protected function subtractQuantities(float $firstQuantity, float $secondQuantity): float
    {
        return $this->utilQuantityService->subtractQuantities($firstQuantity, $secondQuantity);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeItemFromQuote(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemIndex => $currentItemTransfer) {
            if ($this->getItemIdentifier($currentItemTransfer) === $this->getItemIdentifier($itemTransfer)) {
                $newQuantity = $this->subtractQuantities(
                    $currentItemTransfer->getQuantity(),
                    $itemTransfer->getQuantity()
                );
                $newAmount = $currentItemTransfer->getAmount() - $itemTransfer->getAmount();

                if ($newQuantity <= 0 || $newAmount < 1) {
                    $quoteTransfer->getItems()->offsetUnset($itemIndex);
                    break;
                }

                $currentItemTransfer->setQuantity($newQuantity);
                $currentItemTransfer->setAmount($newAmount);
                break;
            }
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function getItemIdentifier(ItemTransfer $itemTransfer): string
    {
        return $itemTransfer->getGroupKey() ?: $itemTransfer->getSku();
    }
}
