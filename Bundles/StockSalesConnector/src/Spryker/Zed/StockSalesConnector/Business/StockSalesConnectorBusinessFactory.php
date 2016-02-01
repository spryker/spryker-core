<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\StockSalesConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\StockSalesConnector\Dependency\Facade\StockToSalesFacadeInterface;
use Spryker\Zed\StockSalesConnector\StockSalesConnectorDependencyProvider;

class StockSalesConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\StockSalesConnector\Dependency\Facade\StockToSalesFacadeInterface
     */
    public function getStockFacade()
    {
        return $this->getProvidedDependency(StockSalesConnectorDependencyProvider::FACADE_STOCK);
    }

}
