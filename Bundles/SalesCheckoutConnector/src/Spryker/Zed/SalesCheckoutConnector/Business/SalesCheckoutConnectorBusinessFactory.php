<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\SalesCheckoutConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesCheckoutConnector\SalesCheckoutConnectorDependencyProvider;
use Spryker\Zed\SalesCheckoutConnector\SalesCheckoutConnectorConfig;

/**
 * @method SalesCheckoutConnectorConfig getConfig()
 */
class SalesCheckoutConnectorBusinessFactory extends AbstractBusinessFactory
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
