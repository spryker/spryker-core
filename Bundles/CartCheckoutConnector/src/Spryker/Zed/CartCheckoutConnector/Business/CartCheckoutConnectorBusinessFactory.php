<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CartCheckoutConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CartCheckoutConnector\ProductOptionCheckoutConnectorConfig getConfig()
 */
class CartCheckoutConnectorBusinessFactory extends AbstractBusinessFactory
{

    public function createCartOrderHydrator()
    {
        return new CartOrderHydrator();
    }

}
