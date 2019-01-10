<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Sales\Model;

use \ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class SplitDeliveryEnabledChecker implements SplitDeliveryEnabledCheckerInterface
{
    protected const CHECK_SPLIT_DELIVERY_METHOD_NAME = 'getShipment';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function checkByQuote(QuoteTransfer $quoteTransfer): bool
    {
        return $this->isSplitDeliveryEnabled($quoteTransfer->getItems());
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function checkByOrder(OrderTransfer $orderTransfer): bool
    {
        return $this->isSplitDeliveryEnabled($orderTransfer->getItems());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return bool
     */
    protected function isSplitDeliveryEnabled(?ArrayObject $items): bool
    {
        if (empty($items)) {
            return false;
        }

        $itemTransfer = current($items);

        return method_exists($itemTransfer, static::CHECK_SPLIT_DELIVERY_METHOD_NAME);
    }
}