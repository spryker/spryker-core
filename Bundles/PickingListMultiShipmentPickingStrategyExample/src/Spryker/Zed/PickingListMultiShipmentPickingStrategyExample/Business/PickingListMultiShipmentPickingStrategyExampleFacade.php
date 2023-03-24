<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PickingListMultiShipmentPickingStrategyExample\Business;

use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListOrderItemGroupTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\PickingListMultiShipmentPickingStrategyExample\Business\PickingListMultiShipmentPickingStrategyExampleBusinessFactory getFactory()
 */
class PickingListMultiShipmentPickingStrategyExampleFacade extends AbstractFacade implements PickingListMultiShipmentPickingStrategyExampleFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PickingListOrderItemGroupTransfer $pickingListOrderItemGroupTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    public function generatePickingLists(PickingListOrderItemGroupTransfer $pickingListOrderItemGroupTransfer): PickingListCollectionTransfer
    {
        return $this->getFactory()
            ->createMultiShipmentPickingListGenerator()
            ->generatePickingLists($pickingListOrderItemGroupTransfer);
    }
}
