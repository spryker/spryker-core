<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ItemGrouperWishlistConnector\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\ItemGrouper\GroupableContainerInterface;

/**
 * @method ItemGrouperWishlistConnectorDependencyContainer getDependencyContainer()
 */
class ItemGrouperWishlistConnectorFacade extends AbstractFacade
{

    /**
     * @param GroupableContainerInterface $items
     *
     * @return GroupableContainerInterface
     */
    public function groupOrderItems(GroupableContainerInterface $items)
    {
        return $this->getDependencyContainer()->createItemGrouperFacade()->groupItemsByKey($items);
    }

}
