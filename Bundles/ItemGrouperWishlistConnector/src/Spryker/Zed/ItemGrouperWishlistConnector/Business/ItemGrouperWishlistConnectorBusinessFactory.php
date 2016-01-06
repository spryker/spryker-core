<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */
namespace Spryker\Zed\ItemGrouperWishlistConnector\Business;

use Spryker\Zed\ItemGrouperWishlistConnector\Dependency\Facade\ItemGrouperWishlistConnectorToItemGrouperInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ItemGrouperWishlistConnector\ItemGrouperWishlistConnectorDependencyProvider;
use Spryker\Zed\ItemGrouperWishlistConnector\ItemGrouperWishlistConnectorConfig;

/**
 * @method ItemGrouperWishlistConnectorConfig getConfig()
 */
class ItemGrouperWishlistConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return ItemGrouperWishlistConnectorToItemGrouperInterface
     */
    public function createItemGrouperFacade()
    {
        return $this->getProvidedDependency(ItemGrouperWishlistConnectorDependencyProvider::FACADE_ITEM_GROUPER);
    }

}
