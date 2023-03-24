<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PickingListMultiShipmentPickingStrategyExample\Communication\Plugin\PickingList;

use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListOrderItemGroupTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListGeneratorStrategyPluginInterface;

/**
 * @method \Spryker\Zed\PickingListMultiShipmentPickingStrategyExample\Business\PickingListMultiShipmentPickingStrategyExampleFacadeInterface getFacade()
 * @method \Spryker\Zed\PickingListMultiShipmentPickingStrategyExample\PickingListMultiShipmentPickingStrategyExampleConfig getConfig()
 */
class MultiShipmentPickingListGeneratorStrategyPlugin extends AbstractPlugin implements PickingListGeneratorStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true if warehouse picking list strategy is applicable.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PickingListOrderItemGroupTransfer $pickingListOrderItemGroupTransfer
     *
     * @return bool
     */
    public function isApplicable(PickingListOrderItemGroupTransfer $pickingListOrderItemGroupTransfer): bool
    {
        $stockTransfer = $pickingListOrderItemGroupTransfer->getWarehouse();
        if (!$stockTransfer) {
            return false;
        }

        if ($stockTransfer->getPickingListStrategy() !== $this->getConfig()->getPickingListStrategy()) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritDoc}
     * - Splits sales order items by shipment.
     * - Groups order items by shipment.
     * - Generates picking list collection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PickingListOrderItemGroupTransfer $pickingListOrderItemGroupTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    public function generatePickingLists(PickingListOrderItemGroupTransfer $pickingListOrderItemGroupTransfer): PickingListCollectionTransfer
    {
        return $this->getFacade()->generatePickingLists($pickingListOrderItemGroupTransfer);
    }
}
