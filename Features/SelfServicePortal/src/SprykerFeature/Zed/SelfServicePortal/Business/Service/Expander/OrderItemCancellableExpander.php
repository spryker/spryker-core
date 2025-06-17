<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Shared\Oms\OmsConfig;
use Spryker\Zed\Oms\Business\OmsFacadeInterface;

class OrderItemCancellableExpander implements OrderItemCancellableExpanderInterface
{
    /**
     * @param \Spryker\Zed\Oms\Business\OmsFacadeInterface $omsFacade
     */
    public function __construct(protected OmsFacadeInterface $omsFacade)
    {
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expandItemsWithCancellableFlag(array $itemTransfers): array
    {
        foreach ($itemTransfers as $itemTransfer) {
            $this->expandItemWithCancellableFlag($itemTransfer);
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function expandItemWithCancellableFlag(ItemTransfer $itemTransfer): ItemTransfer
    {
        if (!$itemTransfer->getState()) {
            return $itemTransfer->setIsCancellable(false);
        }
        $processName = $itemTransfer->getProcessOrFail();
        $stateName = $itemTransfer->getState()->getNameOrFail();
        $stateFlags = $this->omsFacade->getStateFlags($processName, $stateName);
        $isCancellable = in_array(OmsConfig::STATE_TYPE_FLAG_CANCELLABLE, $stateFlags, true);

        return $itemTransfer->setIsCancellable($isCancellable);
    }
}
