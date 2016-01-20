<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ItemGrouperCheckoutConnector\Business;

use Spryker\Zed\ItemGrouperCheckoutConnector\Dependency\Facade\ItemGrouperCheckoutConnectorToItemGrouperInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ItemGrouperCheckoutConnector\ItemGrouperCheckoutConnectorDependencyProvider;
use Spryker\Zed\ItemGrouperCheckoutConnector\ItemGrouperCheckoutConnectorConfig;

/**
 * @method ItemGrouperCheckoutConnectorConfig getConfig()
 */
class ItemGrouperCheckoutConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @deprecated, Use getItemGrouperFacade() instead.
     *
     * @return ItemGrouperCheckoutConnectorToItemGrouperInterface
     */
    public function createItemGrouperFacade()
    {
        trigger_error('Deprecated, use getItemGrouperFacade() instead.', E_USER_DEPRECATED);

        return $this->getItemGrouperFacade();
    }

    /**
     * @return ItemGrouperCheckoutConnectorToItemGrouperInterface
     */
    public function getItemGrouperFacade()
    {
        return $this->getProvidedDependency(ItemGrouperCheckoutConnectorDependencyProvider::FACADE_ITEM_GROUPER);
    }

}
