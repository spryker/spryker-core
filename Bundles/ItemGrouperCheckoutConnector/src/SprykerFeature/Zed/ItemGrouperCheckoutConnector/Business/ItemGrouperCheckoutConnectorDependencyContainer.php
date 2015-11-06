<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ItemGrouperCheckoutConnector\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\ItemGrouperCheckoutConnectorBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\ItemGrouper\Business\ItemGrouperFacade;
use SprykerFeature\Zed\ItemGrouperCheckoutConnector\ItemGrouperCheckoutConnectorDependencyProvider;

/**
 * @method ItemGrouperCheckoutConnectorBusiness getFactory()
 */
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
