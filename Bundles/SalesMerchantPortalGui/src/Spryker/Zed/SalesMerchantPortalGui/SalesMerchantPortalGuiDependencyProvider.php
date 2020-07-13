<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui;

use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToCurrencyFacadeBridge;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToGuiTableFacadeBridge;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantOmsFacadeBridge;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantUserFacadeBridge;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMoneyFacadeBridge;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToStoreFacadeBridge;

class SalesMerchantPortalGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_MERCHANT_USER = 'FACADE_MERCHANT_USER';
    public const FACADE_GUI_TABLE = 'FACADE_GUI_TABLE';
    public const FACADE_CURRENCY = 'FACADE_CURRENCY';
    public const FACADE_MONEY = 'FACADE_MONEY';
    public const FACADE_STORE = 'FACADE_STORE';
    public const FACADE_MERCHANT_OMS = 'FACADE_MERCHANT_OMS';

    public const PROPEL_QUERY_MERCHANT_SALES_ORDER = 'PROPEL_QUERY_MERCHANT_SALES_ORDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addMerchantUserFacade($container);
        $container = $this->addGuiTableFacade($container);
        $container = $this->addCurrencyFacade($container);
        $container = $this->addMoneyFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addMerchantOmsFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = $this->addMerchantSalesOrderPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_USER, function (Container $container) {
            return new SalesMerchantPortalGuiToMerchantUserFacadeBridge(
                $container->getLocator()->merchantUser()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGuiTableFacade(Container $container): Container
    {
        $container->set(static::FACADE_GUI_TABLE, function (Container $container) {
            return new SalesMerchantPortalGuiToGuiTableFacadeBridge(
                $container->getLocator()->guiTable()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCurrencyFacade(Container $container): Container
    {
        $container->set(static::FACADE_CURRENCY, function (Container $container) {
            return new SalesMerchantPortalGuiToCurrencyFacadeBridge(
                $container->getLocator()->currency()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMoneyFacade(Container $container): Container
    {
        $container->set(static::FACADE_MONEY, function (Container $container) {
            return new SalesMerchantPortalGuiToMoneyFacadeBridge(
                $container->getLocator()->money()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container) {
            return new SalesMerchantPortalGuiToStoreFacadeBridge(
                $container->getLocator()->store()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantOmsFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_OMS, function (Container $container) {
            return new SalesMerchantPortalGuiToMerchantOmsFacadeBridge(
                $container->getLocator()->merchantOms()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantSalesOrderPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_MERCHANT_SALES_ORDER, $container->factory(function () {
            return SpyMerchantSalesOrderQuery::create();
        }));

        return $container;
    }
}