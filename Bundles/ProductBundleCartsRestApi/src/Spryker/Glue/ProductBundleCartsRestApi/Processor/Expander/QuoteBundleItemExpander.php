<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductBundleCartsRestApi\Processor\Expander;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;

class QuoteBundleItemExpander implements QuoteBundleItemExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandBundleItemsWithShipment(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if (!$quoteTransfer->getBundleItems()->count()) {
            return $quoteTransfer;
        }

        return $this->copyItemShipmentsToBundleItems($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function copyItemShipmentsToBundleItems(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $indexedShipmentTransfers = $this->getShipmentTransfersIndexedByBundleIdentifier($quoteTransfer);

        foreach ($quoteTransfer->getBundleItems() as $bundleItem) {
            $bundleIdentifier = $bundleItem->getBundleItemIdentifierOrFail();
            if (!isset($indexedShipmentTransfers[$bundleIdentifier])) {
                continue;
            }

            $bundleItem->setShipment($indexedShipmentTransfers[$bundleIdentifier]);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\ShipmentTransfer>
     */
    protected function getShipmentTransfersIndexedByBundleIdentifier(QuoteTransfer $quoteTransfer): array
    {
        $indexedShipmentTransfers = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $bundleIdentifier = $itemTransfer->getRelatedBundleItemIdentifier();
            if (!$bundleIdentifier || !$itemTransfer->getShipment() || isset($indexedShipmentTransfers[$bundleIdentifier])) {
                continue;
            }

            $indexedShipmentTransfers[$bundleIdentifier] = (new ShipmentTransfer())
                ->fromArray($itemTransfer->getShipmentOrFail()->toArray());
        }

        return $indexedShipmentTransfers;
    }
}
