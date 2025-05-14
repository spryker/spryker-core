<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Communication\Plugin\Sales;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Shared\Oms\OmsConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPluginInterface;

/**
 * @method \SprykerFeature\Zed\SspServiceManagement\Business\SspServiceManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspServiceManagement\SspServiceManagementConfig getConfig()
 * @method \SprykerFeature\Zed\SspServiceManagement\Communication\SspServiceManagementCommunicationFactory getFactory()
 */
class SspServiceCancellableOrderItemExpanderPlugin extends AbstractPlugin implements OrderItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands order items with isCancellable property.
     * - Sets isCancellable to true if the item state has OmsConfig::STATE_TYPE_FLAG_CANCELLABLE flag.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expand(array $itemTransfers): array
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

        $stateFlags = $this->getFactory()->getOmsFacade()->getStateFlags($processName, $stateName);
        $isCancellable = in_array(OmsConfig::STATE_TYPE_FLAG_CANCELLABLE, $stateFlags, true);

        return $itemTransfer->setIsCancellable($isCancellable);
    }
}
