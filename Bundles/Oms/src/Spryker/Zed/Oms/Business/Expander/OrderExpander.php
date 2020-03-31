<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Expander;

use Generated\Shared\Transfer\OrderTransfer;

class OrderExpander implements OrderExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithOmsStates(OrderTransfer $orderTransfer): OrderTransfer
    {
        $itemStates = $this->getItemStates($orderTransfer);

        return $orderTransfer->setItemStates($itemStates);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string[]
     */
    protected function getItemStates(OrderTransfer $orderTransfer): array
    {
        $itemStates = [];

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $itemStateTransfer = $itemTransfer->getState();

            if (!$itemStateTransfer || !$itemStateTransfer->getName()) {
                continue;
            }

            $itemStates[] = $itemStateTransfer->getName();
        }

        return array_unique($itemStates);
    }
}
