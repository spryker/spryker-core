<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ItemGrouperCheckoutConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use Spryker\Zed\ItemGrouper\Business\ItemGrouperFacade;
use Spryker\Zed\ItemGrouperCheckoutConnector\ItemGrouperCheckoutConnectorDependencyProvider;

class ItemGrouperCheckoutConnectorDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return ItemGrouperFacade
     */
    public function createItemGrouperFacade()
    {
        return $this->getProvidedDependency(ItemGrouperCheckoutConnectorDependencyProvider::FACADE_ITEM_GROUPER);
    }

}
