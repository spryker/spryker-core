<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ItemGrouperWishlistConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Transfer\GroupableContainerTransfer;

/**
 * @method ItemGrouperWishlistConnectorBusinessFactory getBusinessFactory()
 */
class ItemGrouperWishlistConnectorFacade extends AbstractFacade
{

    /**
     * @param GroupableContainerTransfer $items
     *
     * @return GroupableContainerTransfer
     */
    public function groupOrderItems(GroupableContainerTransfer $items)
    {
        return $this->getBusinessFactory()->createItemGrouperFacade()->groupItemsByKey($items);
    }

}
