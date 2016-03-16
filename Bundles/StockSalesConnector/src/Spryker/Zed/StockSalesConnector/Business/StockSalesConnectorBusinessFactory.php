<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockSalesConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\StockSalesConnector\StockSalesConnectorDependencyProvider;

class StockSalesConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\StockSalesConnector\Dependency\Facade\StockSalesConnectorToStockInterface
     */
    public function getStockFacade()
    {
        return $this->getProvidedDependency(StockSalesConnectorDependencyProvider::FACADE_STOCK);
    }

}
