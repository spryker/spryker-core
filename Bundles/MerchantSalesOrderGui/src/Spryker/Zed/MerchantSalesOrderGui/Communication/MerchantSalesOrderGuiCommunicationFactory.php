<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderGui\Communication;

use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantSalesOrderGui\Communication\Table\MyOrderTable;
use Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToCustomerInterface;
use Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToMoneyInterface;
use Spryker\Zed\MerchantSalesOrderGui\Dependency\Service\MerchantSalesOrderGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\MerchantSalesOrderGui\Dependency\Service\MerchantSalesOrderGuiToUtilSanitizeInterface;
use Spryker\Zed\MerchantSalesOrderGui\MerchantSalesOrderGuiDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantSalesOrderGui\Persistence\MerchantSalesOrderGuiQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\MerchantSalesOrderGui\MerchantSalesOrderGuiConfig getConfig()
 */
class MerchantSalesOrderGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantSalesOrderGui\Communication\Table\MyOrderTable
     */
    public function createMyOrderTable(): MyOrderTable
    {
        return new MyOrderTable(
            $this->getMerchantSalesOrderQuery(),
            $this->getMoneyFacade(),
            $this->getUtilSanitizeService(),
            $this->getDateTimeService(),
            $this->getCustomerFacade()
        );
    }

    /**
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     */
    public function getMerchantSalesOrderQuery(): SpyMerchantSalesOrderQuery
    {
        return $this->getProvidedDependency(MerchantSalesOrderGuiDependencyProvider::PROPEL_QUERY_MERCHANT_SALES_ORDER);
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToMoneyInterface
     */
    protected function getMoneyFacade(): MerchantSalesOrderGuiToMoneyInterface
    {
        return $this->getProvidedDependency(MerchantSalesOrderGuiDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderGui\Dependency\Service\MerchantSalesOrderGuiToUtilSanitizeInterface
     */
    protected function getUtilSanitizeService(): MerchantSalesOrderGuiToUtilSanitizeInterface
    {
        return $this->getProvidedDependency(MerchantSalesOrderGuiDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderGui\Dependency\Service\MerchantSalesOrderGuiToUtilDateTimeServiceInterface
     */
    protected function getDateTimeService(): MerchantSalesOrderGuiToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(MerchantSalesOrderGuiDependencyProvider::SERVICE_DATE_FORMATTER);
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToCustomerInterface
     */
    protected function getCustomerFacade(): MerchantSalesOrderGuiToCustomerInterface
    {
        return $this->getProvidedDependency(MerchantSalesOrderGuiDependencyProvider::FACADE_CUSTOMER);
    }
}
