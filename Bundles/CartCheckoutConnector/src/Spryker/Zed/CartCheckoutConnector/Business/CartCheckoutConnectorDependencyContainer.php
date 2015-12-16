<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CartCheckoutConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\CartCheckoutConnector\ProductOptionCheckoutConnectorConfig;

/**
 * @method ProductOptionCheckoutConnectorConfig getConfig()
 */
class CartCheckoutConnectorDependencyContainer extends AbstractBusinessFactory
{

    public function createCartOrderHydrator()
    {
        return new CartOrderHydrator();
    }

}
