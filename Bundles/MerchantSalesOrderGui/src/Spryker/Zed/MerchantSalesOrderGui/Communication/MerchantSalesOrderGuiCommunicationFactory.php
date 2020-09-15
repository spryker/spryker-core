<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToMerchantSalesOrderFacadeInterface;
use Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToMerchantUserFacadeInterface;
use Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToShipmentFacadeInterface;
use Spryker\Zed\MerchantSalesOrderGui\MerchantSalesOrderGuiDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantSalesOrderGui\Persistence\MerchantSalesOrderGuiQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\MerchantSalesOrderGui\MerchantSalesOrderGuiConfig getConfig()
 */
class MerchantSalesOrderGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToMerchantSalesOrderFacadeInterface
     */
    public function getMerchantSalesOrderFacade(): MerchantSalesOrderGuiToMerchantSalesOrderFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSalesOrderGuiDependencyProvider::FACADE_MERCHANT_SALES_ORDER);
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToMerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): MerchantSalesOrderGuiToMerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSalesOrderGuiDependencyProvider::FACADE_MERCHANT_USER);
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToShipmentFacadeInterface
     */
    public function getShipmentFacade(): MerchantSalesOrderGuiToShipmentFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSalesOrderGuiDependencyProvider::FACADE_SHIPMENT);
    }
}
