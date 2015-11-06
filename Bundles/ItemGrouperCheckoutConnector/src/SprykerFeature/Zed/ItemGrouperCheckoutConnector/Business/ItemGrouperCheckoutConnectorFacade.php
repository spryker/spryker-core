<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ItemGrouperCheckoutConnector\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\ItemGrouper\GroupableContainerInterface;

/**
 * @method ItemGrouperCheckoutConnectorDependencyContainer getDependencyContainer()
 */
class ItemGrouperCheckoutConnectorFacade extends AbstractFacade
{

    /**
     * @param GroupableContainerInterface $orderItems
     *
     * @return GroupableContainerInterface
     */
    public function groupOrderItems(GroupableContainerInterface $orderItems)
    {
        return $this->getDependencyContainer()->createItemGrouperFacade()->groupItemsByKey($orderItems);
    }

}
