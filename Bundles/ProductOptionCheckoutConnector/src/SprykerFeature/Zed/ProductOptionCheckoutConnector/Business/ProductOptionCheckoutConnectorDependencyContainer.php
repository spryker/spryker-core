<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOptionCheckoutConnector\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\ProductOptionCheckoutConnectorBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\ProductOptionCheckoutConnector\ProductOptionCheckoutConnectorConfig;

/**
 * @method ProductOptionCheckoutConnectorBusiness getFactory()
 * @method ProductOptionCheckoutConnectorConfig getConfig()
 */
class ProductOptionCheckoutConnectorDependencyContainer extends AbstractBusinessDependencyContainer
{

    public function createProductOptionOrderHydrator()
    {
        return $this->getFactory()->createProductOptionOrderHydrator();
    }

}
