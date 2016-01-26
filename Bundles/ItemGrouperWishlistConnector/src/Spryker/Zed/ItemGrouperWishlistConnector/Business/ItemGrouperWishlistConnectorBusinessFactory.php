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
     * @deprecated Use getItemGrouperFacade() instead.
     *
     * @return ItemGrouperWishlistConnectorToItemGrouperInterface
     */
    public function createItemGrouperFacade()
    {
        trigger_error('Deprecated, use getItemGrouperFacade() instead.', E_USER_DEPRECATED);

        return $this->getItemGrouperFacade();
    }

    /**
     * @return ItemGrouperWishlistConnectorToItemGrouperInterface
     */
    public function getItemGrouperFacade()
    {
        return $this->getProvidedDependency(ItemGrouperWishlistConnectorDependencyProvider::FACADE_ITEM_GROUPER);
    }

}
