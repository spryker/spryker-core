<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionCheckoutConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use Spryker\Zed\ProductOptionCheckoutConnector\ProductOptionCheckoutConnectorConfig;

/**
 * @method ProductOptionCheckoutConnectorConfig getConfig()
 */
class ProductOptionCheckoutConnectorDependencyContainer extends AbstractBusinessDependencyContainer
{

    public function createProductOptionOrderHydrator()
    {
        return new ProductOptionOrderHydrator();
    }

}
