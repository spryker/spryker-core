<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Cart\Business\Mocks;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\CartOperationStrategyPluginInterface;

class CartItemAddTripleStrategy implements CartOperationStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isApplicable(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $itemTransfer->setQuantity(
            $itemTransfer->getQuantity() * 3
        );

        foreach ($quoteTransfer->getItems() as $currentItemTransfer) {
            if ($this->getItemIdentifier($currentItemTransfer) === $this->getItemIdentifier($itemTransfer)) {
                $currentItemTransfer->setQuantity(
                    $currentItemTransfer->getQuantity() + $itemTransfer->getQuantity()
                );

                return $quoteTransfer;
            }
        }

        $quoteTransfer->getItems()->append($itemTransfer);

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
