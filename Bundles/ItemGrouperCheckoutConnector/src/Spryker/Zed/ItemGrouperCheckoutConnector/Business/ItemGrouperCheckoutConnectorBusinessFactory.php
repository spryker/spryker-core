<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ItemGrouperCheckoutConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ItemGrouperCheckoutConnector\ItemGrouperCheckoutConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\ItemGrouperCheckoutConnector\ItemGrouperCheckoutConnectorConfig getConfig()
 */
class ItemGrouperCheckoutConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ItemGrouperCheckoutConnector\Dependency\Facade\ItemGrouperCheckoutConnectorToItemGrouperInterface
     */
    public function getItemGrouperFacade()
    {
        return $this->getProvidedDependency(ItemGrouperCheckoutConnectorDependencyProvider::FACADE_ITEM_GROUPER);
    }

}
