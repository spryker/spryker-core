<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesMerchantConnector\Business\Expander\OrderItemExpander;
use Spryker\Zed\SalesMerchantConnector\Business\Expander\OrderItemExpanderInterface;
use Spryker\Zed\SalesMerchantConnector\Business\SalesOrderMerchantSaver\SalesOrderMerchantSaver;
use Spryker\Zed\SalesMerchantConnector\Business\SalesOrderMerchantSaver\SalesOrderMerchantSaverInterface;
use Spryker\Zed\SalesMerchantConnector\Dependency\Facade\SalesMerchantConnectorToMerchantFacadeInterface;
use Spryker\Zed\SalesMerchantConnector\Dependency\Facade\SalesMerchantConnectorToMerchantProductOfferFacadeInterface;
use Spryker\Zed\SalesMerchantConnector\Dependency\Facade\SalesMerchantConnectorToStoreFacadeInterface;
use Spryker\Zed\SalesMerchantConnector\SalesMerchantConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\SalesMerchantConnector\SalesMerchantConnectorConfig getConfig()
 * @method \Spryker\Zed\SalesMerchantConnector\Persistence\SalesMerchantConnectorEntityManagerInterface getEntityManager()
 */
class SalesMerchantConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SalesMerchantConnector\Business\Expander\OrderItemExpanderInterface
     */
    public function createOrderItemExpander(): OrderItemExpanderInterface
    {
        return new OrderItemExpander();
    }

    /**
     * @return \Spryker\Zed\SalesMerchantConnector\Business\SalesOrderMerchantSaver\SalesOrderMerchantSaverInterface
     */
    public function createSalesOrderMerchantSaver(): SalesOrderMerchantSaverInterface
    {
        return new SalesOrderMerchantSaver(
            $this->getEntityManager(),
            $this->getMerchantProductOfferFacade(),
            $this->getMerchantFacade(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\SalesMerchantConnector\Dependency\Facade\SalesMerchantConnectorToMerchantProductOfferFacadeInterface
     */
    public function getMerchantProductOfferFacade(): SalesMerchantConnectorToMerchantProductOfferFacadeInterface
    {
        return $this->getProvidedDependency(SalesMerchantConnectorDependencyProvider::FACADE_MERCHANT_PRODUCT_OFFER);
    }

    /**
     * @return \Spryker\Zed\SalesMerchantConnector\Dependency\Facade\SalesMerchantConnectorToMerchantFacadeInterface
     */
    public function getMerchantFacade(): SalesMerchantConnectorToMerchantFacadeInterface
    {
        return $this->getProvidedDependency(SalesMerchantConnectorDependencyProvider::FACADE_MERCHANT);
    }

    /**
     * @return \Spryker\Zed\SalesMerchantConnector\Dependency\Facade\SalesMerchantConnectorToStoreFacadeInterface
     */
    public function getStoreFacade(): SalesMerchantConnectorToStoreFacadeInterface
    {
        return $this->getProvidedDependency(SalesMerchantConnectorDependencyProvider::FACADE_STORE);
    }
}
