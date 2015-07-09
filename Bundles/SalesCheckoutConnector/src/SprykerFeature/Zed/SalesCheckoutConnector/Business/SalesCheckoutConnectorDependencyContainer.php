<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SalesCheckoutConnector\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\SalesCheckoutConnectorBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\SalesCheckoutConnector\SalesCheckoutConnectorDependencyProvider;

/**
 * @method SalesCheckoutConnectorBusiness getFactory()
 */
class SalesCheckoutConnectorDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return SalesOrderSaverInterface
     */
    public function getSalesOrderSaver()
    {
        return $this->getFactory()->createSalesOrderSaver(
            $this->getProvidedDependency(SalesCheckoutConnectorDependencyProvider::FACADE_SALES)
        );
    }

}
