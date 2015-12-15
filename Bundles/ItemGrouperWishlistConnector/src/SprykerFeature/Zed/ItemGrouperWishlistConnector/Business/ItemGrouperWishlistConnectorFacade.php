<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ItemGrouperWishlistConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Transfer\GroupableContainerTransfer;

/**
 * @method ItemGrouperWishlistConnectorDependencyContainer getDependencyContainer()
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
        return $this->getDependencyContainer()->createItemGrouperFacade()->groupItemsByKey($items);
    }

}
