<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockSalesConnector\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\StockSalesConnector\StockSalesConnectorDependencyProvider;

class StockSalesConnectorCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\StockSalesConnector\Dependency\Facade\StockSalesConnectorToStockInterface
     */
    public function getStockFacade()
    {
        return $this->getProvidedDependency(StockSalesConnectorDependencyProvider::FACADE_STOCK);
    }
}
