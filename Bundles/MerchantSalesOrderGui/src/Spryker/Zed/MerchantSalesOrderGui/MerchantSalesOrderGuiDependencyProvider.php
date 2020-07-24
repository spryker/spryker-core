<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderGui;

use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToCustomerBridge;
use Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToMoneyBridge;
use Spryker\Zed\MerchantSalesOrderGui\Dependency\Service\MerchantSalesOrderGuiToUtilDateTimeServiceBridge;
use Spryker\Zed\MerchantSalesOrderGui\Dependency\Service\MerchantSalesOrderGuiToUtilSanitizeBridge;

/**
 * @method \Spryker\Zed\MerchantSalesOrderGui\MerchantSalesOrderGuiConfig getConfig()
 */
class MerchantSalesOrderGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PROPEL_QUERY_MERCHANT_SALES_ORDER = 'PROPEL_QUERY_MERCHANT_SALES_ORDER';
    public const FACADE_MONEY = 'FACADE_MONEY';
    public const FACADE_CUSTOMER = 'FACADE_CUSTOMER';
    public const SERVICE_UTIL_SANITIZE = 'SERVICE_UTIL_SANITIZE';
    public const SERVICE_DATE_FORMATTER = 'SERVICE_DATE_FORMATTER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addMerchantSalesOrderQuery($container);
        $container = $this->addMoneyFacade($container);
        $container = $this->addCustomerFacade($container);
        $container = $this->addUtilSanitizeService($container);
        $container = $this->addDateTimeService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantSalesOrderQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_MERCHANT_SALES_ORDER, $container->factory(function () {
            return SpyMerchantSalesOrderQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMoneyFacade(Container $container)
    {
        $container->set(static::FACADE_MONEY, function (Container $container) {
            return new MerchantSalesOrderGuiToMoneyBridge($container->getLocator()->money()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerFacade(Container $container)
    {
        $container->set(static::FACADE_CUSTOMER, function (Container $container) {
            return new MerchantSalesOrderGuiToCustomerBridge($container->getLocator()->customer()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilSanitizeService(Container $container)
    {
        $container->set(static::SERVICE_UTIL_SANITIZE, function (Container $container) {
            return new MerchantSalesOrderGuiToUtilSanitizeBridge($container->getLocator()->utilSanitize()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDateTimeService(Container $container)
    {
        $container->set(static::SERVICE_DATE_FORMATTER, function (Container $container) {
            return new MerchantSalesOrderGuiToUtilDateTimeServiceBridge($container->getLocator()->utilDateTime()->service());
        });

        return $container;
    }
}
