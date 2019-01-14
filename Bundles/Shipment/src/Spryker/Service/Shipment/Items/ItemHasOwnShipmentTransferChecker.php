<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Shipment\Items;

use \ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class ItemHasOwnShipmentTransferChecker implements ItemHasOwnShipmentTransferCheckerInterface
{
    protected const PROPERTY_NAME_SHIPMENT_TRANSFER = 'shipment';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function checkByQuote(QuoteTransfer $quoteTransfer): bool
    {
        return $this->hasItemOwnShipmentTransfer($quoteTransfer->getItems());
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function checkByOrder(OrderTransfer $orderTransfer): bool
    {
        return $this->hasItemOwnShipmentTransfer($orderTransfer->getItems());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return bool
     */
    protected function hasItemOwnShipmentTransfer(?ArrayObject $items): bool
    {
        if (empty($items)) {
            return false;
        }

        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = current($items);

        return $itemTransfer->offsetExists(static::PROPERTY_NAME_SHIPMENT_TRANSFER);
    }
}