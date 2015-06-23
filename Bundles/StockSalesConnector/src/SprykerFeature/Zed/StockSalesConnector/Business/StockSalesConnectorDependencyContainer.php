<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\StockSalesConnector\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\StockSalesConnector\Dependency\Facade\StockToSalesFacadeInterface;

class StockSalesConnectorDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return StockToSalesFacadeInterface
     */
    public function getStockFacade()
    {
        return $this->getLocator()->stock()->facade();
    }

}
