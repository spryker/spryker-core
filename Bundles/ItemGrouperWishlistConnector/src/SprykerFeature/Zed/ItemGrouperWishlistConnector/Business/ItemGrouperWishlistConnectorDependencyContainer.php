<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ItemGrouperWishlistConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use Spryker\Zed\ItemGrouper\Business\ItemGrouperFacade;
use Spryker\Zed\ItemGrouperWishlistConnector\ItemGrouperWishlistConnectorDependencyProvider;

class ItemGrouperWishlistConnectorDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return ItemGrouperFacade
     */
    public function createItemGrouperFacade()
    {
        return $this->getProvidedDependency(ItemGrouperWishlistConnectorDependencyProvider::FACADE_ITEM_GROUPER);
    }

}
