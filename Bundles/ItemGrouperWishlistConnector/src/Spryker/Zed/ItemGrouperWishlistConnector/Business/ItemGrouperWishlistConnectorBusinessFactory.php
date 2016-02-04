<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */
namespace Spryker\Zed\ItemGrouperWishlistConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ItemGrouperWishlistConnector\ItemGrouperWishlistConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\ItemGrouperWishlistConnector\ItemGrouperWishlistConnectorConfig getConfig()
 */
class ItemGrouperWishlistConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ItemGrouperWishlistConnector\Dependency\Facade\ItemGrouperWishlistConnectorToItemGrouperInterface
     */
    public function getItemGrouperFacade()
    {
        return $this->getProvidedDependency(ItemGrouperWishlistConnectorDependencyProvider::FACADE_ITEM_GROUPER);
    }

}
