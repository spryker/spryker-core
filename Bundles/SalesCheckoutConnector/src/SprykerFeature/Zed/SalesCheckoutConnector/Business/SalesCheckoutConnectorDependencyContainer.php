<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SalesCheckoutConnector\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\SalesCheckoutConnectorBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\SalesCheckoutConnector\SalesCheckoutConnectorDependencyProvider;

class SalesCheckoutConnectorDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return SalesOrderSaverInterface
     */
    public function getSalesOrderSaver()
    {
        return new SalesOrderSaver(
            $this->getProvidedDependency(SalesCheckoutConnectorDependencyProvider::FACADE_SALES)
        );
    }

}
