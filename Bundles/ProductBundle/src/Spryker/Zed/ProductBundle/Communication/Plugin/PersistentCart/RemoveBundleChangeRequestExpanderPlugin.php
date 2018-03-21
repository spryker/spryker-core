<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Communication\Plugin\PersistentCart;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\PersistentCart\Dependency\Plugin\CartChangeRequestExpandPluginInterface;

class RemoveBundleChangeRequestExpanderPlugin implements CartChangeRequestExpandPluginInterface
{
    /**
     * Specification:
     * - Replace items with bundle items is it exist
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expand(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        $itemTransferList = [];
        foreach ($cartChangeTransfer->getItems() as $quoteTransfer) {
            $bundledItemTransferList = $this->getBundledItems($cartChangeTransfer->getQuote(), $quoteTransfer->getGroupKey(), $quoteTransfer->getQuantity());
            if (count($bundledItemTransferList)) {
                $itemTransferList += $bundledItemTransferList;
                continue;
            }
            $itemTransferList[] = $quoteTransfer;
        }
        $cartChangeTransfer->setItems(new ArrayObject($itemTransferList));

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $groupKey
     * @param int $numberOfBundlesToRemove
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getBundledItems(QuoteTransfer $quoteTransfer, $groupKey, $numberOfBundlesToRemove)
    {
        if (!$numberOfBundlesToRemove) {
            $numberOfBundlesToRemove = $this->getBundledProductTotalQuantity($quoteTransfer, $groupKey);
        }
        $bundledItems = [];
        foreach ($quoteTransfer->getBundleItems() as $bundleItemTransfer) {
            if ($numberOfBundlesToRemove === 0) {
                return $bundledItems;
            }

            if ($bundleItemTransfer->getGroupKey() !== $groupKey) {
                continue;
            }

            foreach ($quoteTransfer->getItems() as $itemTransfer) {
                if ($itemTransfer->getRelatedBundleItemIdentifier() !== $bundleItemTransfer->getBundleItemIdentifier()) {
                    continue;
                }
                $bundledItems[] = $itemTransfer;
            }
            $numberOfBundlesToRemove--;
        }

        return $bundledItems;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $groupKey
     *
     * @return int
     */
    protected function getBundledProductTotalQuantity(QuoteTransfer $quoteTransfer, $groupKey)
    {
        $bundleItemQuantity = 0;
        foreach ($quoteTransfer->getBundleItems() as $bundleItemTransfer) {
            if ($bundleItemTransfer->getGroupKey() !== $groupKey) {
                continue;
            }
            $bundleItemQuantity += $bundleItemTransfer->getQuantity();
        }

        return $bundleItemQuantity;
    }
}
