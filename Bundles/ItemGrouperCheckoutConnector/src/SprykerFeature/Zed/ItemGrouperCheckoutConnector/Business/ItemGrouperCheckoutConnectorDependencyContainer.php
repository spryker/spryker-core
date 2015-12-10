<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ItemGrouperCheckoutConnector\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\ItemGrouper\Business\ItemGrouperFacade;
use SprykerFeature\Zed\ItemGrouperCheckoutConnector\ItemGrouperCheckoutConnectorDependencyProvider;

class ItemGrouperCheckoutConnectorDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return ItemGrouperFacade
     */
    public function createItemGrouperFacade()
    {
        return $this->getProvidedDependency(ItemGrouperCheckoutConnectorDependencyProvider::ITEM_GROUPER_FACADE);
    }

}
