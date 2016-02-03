<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionCheckoutConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\ProductOptionCheckoutConnector\ProductOptionCheckoutConnectorConfig getConfig()
 */
class ProductOptionCheckoutConnectorBusinessFactory extends AbstractBusinessFactory
{

    public function createProductOptionOrderHydrator()
    {
        return new ProductOptionOrderHydrator();
    }

}
