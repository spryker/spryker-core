<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplierGui;

use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToCompanySupplierFacadeBridge;
use Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToCurrencyFacadeBridge;
use Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToMoneyFacadeBridge;
use Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToStoreFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CompanySupplierGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_COMPANY_SUPPLIER = 'FACADE_COMPANY_SUPPLIER';
    public const FACADE_MONEY = 'FACADE_MONEY';
    public const FACADE_STORE = 'FACADE_STORE';
    public const FACADE_CURRENCY = 'FACADE_CURRENCY';
    public const PROPEL_QUERY_PRODUCT = 'PROPEL_QUERY_PRODUCT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addCompanySupplierFacade($container);
        $container = $this->addMoneyFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addCurrencyFacade($container);
        $container = $this->addProductQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanySupplierFacade(Container $container): Container
    {
        $container[static::FACADE_COMPANY_SUPPLIER] = function (Container $container) {
            return new CompanySupplierGuiToCompanySupplierFacadeBridge($container->getLocator()->companySupplier()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMoneyFacade(Container $container): Container
    {
        $container[static::FACADE_MONEY] = function (Container $container) {
            return new CompanySupplierGuiToMoneyFacadeBridge($container->getLocator()->money()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container[static::FACADE_STORE] = function (Container $container) {
            return new CompanySupplierGuiToStoreFacadeBridge($container->getLocator()->store()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCurrencyFacade(Container $container): Container
    {
        $container[static::FACADE_CURRENCY] = function (Container $container) {
            return new CompanySupplierGuiToCurrencyFacadeBridge($container->getLocator()->currency()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductQuery(Container $container)
    {
        $container[static::PROPEL_QUERY_PRODUCT] = function (Container $container) {
            return SpyProductQuery::create();
        };

        return $container;
    }
}
