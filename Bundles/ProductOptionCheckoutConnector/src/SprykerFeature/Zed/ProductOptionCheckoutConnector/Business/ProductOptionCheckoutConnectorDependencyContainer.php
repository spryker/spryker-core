<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOptionCheckoutConnector\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\ProductOptionCheckoutConnector\ProductOptionCheckoutConnectorConfig;

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
