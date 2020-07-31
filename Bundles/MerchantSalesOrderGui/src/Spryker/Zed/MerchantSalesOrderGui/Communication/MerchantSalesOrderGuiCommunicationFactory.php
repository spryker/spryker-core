<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderGui\Communication;

use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantSalesOrderGui\Communication\Table\MyOrderTable;
use Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToCustomerFacadeInterface;
use Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToMerchantSalesOrderFacadeInterface;
use Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToMerchantUserFacadeInterface;
use Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToMoneyFacadeInterface;
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
            $this->getCustomerFacade(),
            $this->getMerchantUserFacade()
        );
    }

    /**
     * @phpstan-return array <string, string>
     *
     * @return array
     */
    public function getMerchantSalesOrderDetailExternalBlocksUrls()
    {
        return $this->getConfig()->getMerchantSalesOrderDetailExternalBlocksUrls();
    }

    /**
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     */
    public function getMerchantSalesOrderQuery(): SpyMerchantSalesOrderQuery
    {
        return $this->getProvidedDependency(MerchantSalesOrderGuiDependencyProvider::PROPEL_QUERY_MERCHANT_SALES_ORDER);
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToMoneyFacadeInterface
     */
    public function getMoneyFacade(): MerchantSalesOrderGuiToMoneyFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSalesOrderGuiDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderGui\Dependency\Service\MerchantSalesOrderGuiToUtilSanitizeInterface
     */
    public function getUtilSanitizeService(): MerchantSalesOrderGuiToUtilSanitizeInterface
    {
        return $this->getProvidedDependency(MerchantSalesOrderGuiDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderGui\Dependency\Service\MerchantSalesOrderGuiToUtilDateTimeServiceInterface
     */
    public function getDateTimeService(): MerchantSalesOrderGuiToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(MerchantSalesOrderGuiDependencyProvider::SERVICE_DATE_FORMATTER);
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToCustomerFacadeInterface
     */
    public function getCustomerFacade(): MerchantSalesOrderGuiToCustomerFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSalesOrderGuiDependencyProvider::FACADE_CUSTOMER);
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToMerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): MerchantSalesOrderGuiToMerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSalesOrderGuiDependencyProvider::FACADE_MERCHANT_USER);
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToMerchantSalesOrderFacadeInterface
     */
    public function getMerchantSalesOrderFacade(): MerchantSalesOrderGuiToMerchantSalesOrderFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSalesOrderGuiDependencyProvider::FACADE_MERCHANT_SALES_ORDER);
    }
}
