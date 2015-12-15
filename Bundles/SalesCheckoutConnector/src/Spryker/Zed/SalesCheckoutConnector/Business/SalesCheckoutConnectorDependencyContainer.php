<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\SalesCheckoutConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use Spryker\Zed\SalesCheckoutConnector\SalesCheckoutConnectorDependencyProvider;

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
