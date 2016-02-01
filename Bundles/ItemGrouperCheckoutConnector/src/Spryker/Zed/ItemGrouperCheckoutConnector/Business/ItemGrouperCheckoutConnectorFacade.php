<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ItemGrouperCheckoutConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Transfer\GroupableContainerTransfer;

/**
 * @method ItemGrouperCheckoutConnectorBusinessFactory getFactory()
 */
class ItemGrouperCheckoutConnectorFacade extends AbstractFacade
{

    /**
     * @param GroupableContainerTransfer $orderItems
     *
     * @return \Generated\Shared\Transfer\GroupableContainerTransfer
     */
    public function groupOrderItems(GroupableContainerTransfer $orderItems)
    {
        return $this->getFactory()->getItemGrouperFacade()->groupItemsByKey($orderItems);
    }

}
