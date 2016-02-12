<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ItemGrouperWishlistConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Transfer\GroupableContainerTransfer;

/**
 * @method \Spryker\Zed\ItemGrouperWishlistConnector\Business\ItemGrouperWishlistConnectorBusinessFactory getFactory()
 */
class ItemGrouperWishlistConnectorFacade extends AbstractFacade implements ItemGrouperWishlistConnectorFacadeInterface
{

    /**
     * @param \Generated\Shared\Transfer\GroupableContainerTransfer $items
     *
     * @return \Generated\Shared\Transfer\GroupableContainerTransfer
     */
    public function groupOrderItems(GroupableContainerTransfer $items)
    {
        return $this->getFactory()->getItemGrouperFacade()->groupItemsByKey($items);
    }

}
