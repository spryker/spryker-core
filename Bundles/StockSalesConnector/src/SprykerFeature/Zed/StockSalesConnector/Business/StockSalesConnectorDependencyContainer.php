<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\StockSalesConnector\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\StockSalesConnector\Dependency\Facade\StockToSalesFacadeInterface;

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
