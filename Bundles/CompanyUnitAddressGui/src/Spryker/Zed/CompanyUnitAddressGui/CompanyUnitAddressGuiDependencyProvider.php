<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressGui;

use Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery;
use Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCompanyFacadeBridge;
use Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCompanyUnitAddressFacadeBridge;
use Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCountryFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CompanyUnitAddressGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_COMPANY_UNIT_ADDRESS = 'FACADE_COMPANY_UNIT_ADDRESS';
    public const PLUGINS_COMPANY_UNIT_ADDRESS_FORM = 'PLUGINS_COMPANY_UNIT_ADDRESS_FORM';
    public const PLUGINS_COMPANY_UNIT_ADDRESS_TABLE_CONFIG_EXPANDER = 'PLUGINS_COMPANY_UNIT_ADDRESS_TABLE_CONFIG_EXPANDER';
    public const PLUGINS_COMPANY_UNIT_ADDRESS_TABLE_HEADER_EXPANDER = 'PLUGINS_COMPANY_UNIT_ADDRESS_TABLE_HEADER_EXPANDER';
    public const PLUGINS_COMPANY_UNIT_ADDRESS_TABLE_DATA_EXPANDER = 'PLUGINS_COMPANY_UNIT_ADDRESS_TABLE_DATA_EXPANDER';
    public const FACADE_COMPANY = 'FACADE_COMPANY';
    public const FACADE_COUNTRY = 'FACADE_COUNTRY';
    public const PROPEL_QUERY_COMPANY_UNIT_ADDRESS = 'PROPEL_QUERY_COMPANY_UNIT_ADDRESS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addCompanyUnitAddressFacade($container);
        $container = $this->addCompanyUnitAddressFormPlugins($container);
        $container = $this->addCompanyUnitAddressTableConfigExpanderPlugins($container);
        $container = $this->addCompanyUnitAddressTableHeaderExpanderPlugins($container);
        $container = $this->addCompanyUnitAddressTableDataExpanderPlugins($container);
        $container = $this->addCompanyFacade($container);
        $container = $this->addCountryFacade($container);
        $container = $this->addCompanyUnitAddressPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUnitAddressFacade(Container $container): Container
    {
        $container->set(static::FACADE_COMPANY_UNIT_ADDRESS, function (Container $container) {
            return new CompanyUnitAddressGuiToCompanyUnitAddressFacadeBridge(
                $container->getLocator()->companyUnitAddress()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUnitAddressPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_COMPANY_UNIT_ADDRESS, $container->factory(function () {
            return SpyCompanyUnitAddressQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyFacade(Container $container): Container
    {
        $container->set(static::FACADE_COMPANY, function (Container $container) {
            return new CompanyUnitAddressGuiToCompanyFacadeBridge(
                $container->getLocator()->company()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCountryFacade(Container $container): Container
    {
        $container->set(static::FACADE_COUNTRY, function (Container $container) {
            return new CompanyUnitAddressGuiToCountryFacadeBridge(
                $container->getLocator()->country()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUnitAddressFormPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_COMPANY_UNIT_ADDRESS_FORM, function () {
            return $this->getCompanyUnitAddressFormPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressGuiExtension\Dependency\Plugin\CompanyUnitAddressEditFormExpanderPluginInterface[]
     */
    protected function getCompanyUnitAddressFormPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUnitAddressTableConfigExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_COMPANY_UNIT_ADDRESS_TABLE_CONFIG_EXPANDER, function () {
            return $this->getCompanyUnitAddressTableConfigExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressGuiExtension\Dependency\Plugin\CompanyUnitAddressTableConfigExpanderPluginInterface[]
     */
    protected function getCompanyUnitAddressTableConfigExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUnitAddressTableHeaderExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_COMPANY_UNIT_ADDRESS_TABLE_HEADER_EXPANDER, function () {
            return $this->getCompanyUnitAddressTableHeaderExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressGuiExtension\Dependency\Plugin\CompanyUnitAddressTableHeaderExpanderPluginInterface[]
     */
    protected function getCompanyUnitAddressTableHeaderExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUnitAddressTableDataExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_COMPANY_UNIT_ADDRESS_TABLE_DATA_EXPANDER, function () {
            return $this->getCompanyUnitAddressTableDataExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressGuiExtension\Dependency\Plugin\CompanyUnitAddressTableDataExpanderPluginInterface[]
     */
    protected function getCompanyUnitAddressTableDataExpanderPlugins(): array
    {
        return [];
    }
}
