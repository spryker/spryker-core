<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Communication\Plugin;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface;

class QuoteItemGroupKeySeparatorPlugin implements ItemExpanderPluginInterface
{
    /**
     * Specification:
     * - Adds separator unique key to items group key.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer)
    {
        foreach ($cartChangeTransfer->getItems() as $cartItem) {
            if ($cartItem->getSeparator()) {
                $cartItem->setGroupKey($this->buildGroupKey($cartItem));
            }
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $cartItem
     *
     * @return string
     */
    protected function buildGroupKey(ItemTransfer $cartItem): string
    {
        return $cartItem->getSku() . '_' . $cartItem->getSeparator();
    }
}
