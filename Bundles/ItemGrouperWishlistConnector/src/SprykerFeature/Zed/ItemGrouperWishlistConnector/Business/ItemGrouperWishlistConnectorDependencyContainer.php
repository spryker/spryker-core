<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ItemGrouperWishlistConnector\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\ItemGrouperWishlistConnectorBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\ItemGrouper\Business\ItemGrouperFacade;
use SprykerFeature\Zed\ItemGrouperWishlistConnector\ItemGrouperWishlistConnectorDependencyProvider;

/**
 * @method ItemGrouperWishlistConnectorBusiness getFactory()
 */
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
