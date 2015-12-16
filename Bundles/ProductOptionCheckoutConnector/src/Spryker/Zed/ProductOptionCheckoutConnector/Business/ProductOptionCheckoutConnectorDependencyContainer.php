<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionCheckoutConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOptionCheckoutConnector\ProductOptionCheckoutConnectorConfig;

/**
 * @method ProductOptionCheckoutConnectorConfig getConfig()
 */
class ProductOptionCheckoutConnectorDependencyContainer extends AbstractBusinessFactory
{

    public function createProductOptionOrderHydrator()
    {
        return new ProductOptionOrderHydrator();
    }

}
