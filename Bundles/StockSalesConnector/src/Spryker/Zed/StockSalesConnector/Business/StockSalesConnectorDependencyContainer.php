<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\StockSalesConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use Spryker\Zed\StockSalesConnector\Dependency\Facade\StockToSalesFacadeInterface;

class StockSalesConnectorDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return StockToSalesFacadeInterface
     */
    public function getStockFacade()
    {
        return $this->getLocator()->stock()->facade();
    }

}
