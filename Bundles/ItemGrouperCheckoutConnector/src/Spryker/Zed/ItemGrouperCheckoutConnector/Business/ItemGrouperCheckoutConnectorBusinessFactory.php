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
     * @return ItemGrouperCheckoutConnectorToItemGrouperInterface
     */
    public function createItemGrouperFacade()
    {
        return $this->getProvidedDependency(ItemGrouperCheckoutConnectorDependencyProvider::FACADE_ITEM_GROUPER);
    }

}
