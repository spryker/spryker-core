<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Cart;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Service\ProductPackagingUnit\ProductPackagingUnitServiceInterface;

class ProductPackagingUnitCartOperation implements ProductPackagingUnitCartOperationInterface
{
    /**
     * @var \Spryker\Service\ProductPackagingUnit\ProductPackagingUnitServiceInterface
     */
    protected $service;

    /**
     * @param \Spryker\Service\ProductPackagingUnit\ProductPackagingUnitServiceInterface $service
     */
    public function __construct(ProductPackagingUnitServiceInterface $service)
    {
        $this->service = $service;
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
                $newQuantity = $currentItemTransfer->getQuantity() + $itemTransfer->getQuantity();

                $currentItemTransfer->setQuantity($this->service->round($newQuantity));

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
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeItemFromQuote(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemIndex => $currentItemTransfer) {
            if ($this->getItemIdentifier($currentItemTransfer) === $this->getItemIdentifier($itemTransfer)) {
                $newQuantity = $currentItemTransfer->getQuantity() - $itemTransfer->getQuantity();
                $newQuantity = $this->service->round($newQuantity);
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
