<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\StockSalesConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\StockSalesConnector\Dependency\Facade\StockToSalesFacadeInterface;

class StockSalesConnectorDependencyContainer extends AbstractBusinessFactory
{

    /**
     * @return StockToSalesFacadeInterface
     */
    public function getStockFacade()
    {
        return $this->getLocator()->stock()->facade();
    }

}
