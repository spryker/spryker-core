<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ItemGrouperCheckoutConnector\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Transfer\GroupableContainerTransfer;

/**
 * @method ItemGrouperCheckoutConnectorDependencyContainer getDependencyContainer()
 */
class ItemGrouperCheckoutConnectorFacade extends AbstractFacade
{

    /**
     * @param GroupableContainerTransfer $orderItems
     *
     * @return GroupableContainerTransfer
     */
    public function groupOrderItems(GroupableContainerTransfer $orderItems)
    {
        return $this->getDependencyContainer()->createItemGrouperFacade()->groupItemsByKey($orderItems);
    }

}
