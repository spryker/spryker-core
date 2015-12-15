<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CartCheckoutConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use Spryker\Zed\CartCheckoutConnector\ProductOptionCheckoutConnectorConfig;

/**
 * @method ProductOptionCheckoutConnectorConfig getConfig()
 */
class CartCheckoutConnectorDependencyContainer extends AbstractBusinessDependencyContainer
{

    public function createCartOrderHydrator()
    {
        return new CartOrderHydrator();
    }

}
