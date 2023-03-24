<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PickingListMultiShipmentPickingStrategyExample\Business;

use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListOrderItemGroupTransfer;

interface PickingListMultiShipmentPickingStrategyExampleFacadeInterface
{
    /**
     * Specification:
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
    public function generatePickingLists(PickingListOrderItemGroupTransfer $pickingListOrderItemGroupTransfer): PickingListCollectionTransfer;
}
