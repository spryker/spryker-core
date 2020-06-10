<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Expander;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Oms\OmsConfig;
use Spryker\Zed\Oms\Business\Checker\FlagCheckerInterface;

class OrderExpander implements OrderExpanderInterface
{
    /**
     * @var \Spryker\Zed\Oms\Business\Checker\FlagCheckerInterface
     */
    protected $flagChecker;

    /**
     * @param \Spryker\Zed\Oms\Business\Checker\FlagCheckerInterface $flagChecker
     */
    public function __construct(FlagCheckerInterface $flagChecker)
    {
        $this->flagChecker = $flagChecker;
    }

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
     * @param \Generated\Shared\Transfer\OrderTransfer[] $orderTransfers
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    public function setOrderIsCancellableByItemState(array $orderTransfers): array
    {
        foreach ($orderTransfers as $orderTransfer) {
            $isOrderCancellable = $this->flagChecker->hasOrderItemsFlag($orderTransfer, OmsConfig::STATE_TYPE_FLAG_CANCELLABLE);

            $orderTransfer->setIsCancellable($isOrderCancellable);
        }

        return $orderTransfers;
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

        return array_values(array_unique($itemStates));
    }
}
