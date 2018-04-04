<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressGui;

use Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCompanyFacadeBridge;
use Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCompanyUnitAddressFacadeBridge;
use Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCountryFacadeBridge;
use Spryker\Zed\CompanyUnitAddressGui\Dependency\QueryContainer\CompanyUnitAddressGuiToCompanyUnitAddressQueryContainerBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CompanyUnitAddressGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    const QUERY_CONTAINER_COMPANY_UNIT_ADDRESS = 'QUERY_CONTAINER_COMPANY_UNIT_ADDRESS';
    const FACADE_COMPANY_UNIT_ADDRESS = 'FACADE_COMPANY_UNIT_ADDRESS';
    const PLUGINS_COMPANY_UNIT_ADDRESS_FORM = 'PLUGINS_COMPANY_UNIT_ADDRESS_FORM';
    const PLUGINS_COMPANY_UNIT_ADDRESS_TABLE_CONFIG_EXPANDER = 'PLUGINS_COMPANY_UNIT_ADDRESS_TABLE_CONFIG_EXPANDER';
    const PLUGINS_COMPANY_UNIT_ADDRESS_TABLE_HEADER_EXPANDER = 'PLUGINS_COMPANY_UNIT_ADDRESS_TABLE_HEADER_EXPANDER';
    const PLUGINS_COMPANY_UNIT_ADDRESS_TABLE_DATA_EXPANDER = 'PLUGINS_COMPANY_UNIT_ADDRESS_TABLE_DATA_EXPANDER';
    public const FACADE_COMPANY = 'FACADE_COMPANY';
    public const FACADE_COUNTRY = 'FACADE_COUNTRY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addCompanyUnitAddressQueryContainer($container);
        $container = $this->addCompanyUnitAddressFacade($container);
        $container = $this->addCompanyUnitAddressFormPlugins($container);
        $container = $this->addCompanyUnitAddressTableConfigExpanderPlugins($container);
        $container = $this->addCompanyUnitAddressTableHeaderExpanderPlugins($container);
        $container = $this->addCompanyUnitAddressTableDataExpanderPlugins($container);
        $container = $this->addCompanyFacade($container);
        $container = $this->addCountryFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUnitAddressQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_COMPANY_UNIT_ADDRESS] = function (Container $container) {
            return new CompanyUnitAddressGuiToCompanyUnitAddressQueryContainerBridge(
                $container->getLocator()->companyUnitAddress()->queryContainer()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUnitAddressFacade(Container $container)
    {
        $container[static::FACADE_COMPANY_UNIT_ADDRESS] = function (Container $container) {
            return new CompanyUnitAddressGuiToCompanyUnitAddressFacadeBridge(
                $container->getLocator()->companyUnitAddress()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyFacade(Container $container)
    {
        $container[static::FACADE_COMPANY] = function (Container $container) {
            return new CompanyUnitAddressGuiToCompanyFacadeBridge(
                $container->getLocator()->company()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCountryFacade(Container $container)
    {
        $container[static::FACADE_COUNTRY] = function (Container $container) {
            return new CompanyUnitAddressGuiToCountryFacadeBridge(
                $container->getLocator()->country()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUnitAddressFormPlugins(Container $container)
    {
        $container[static::PLUGINS_COMPANY_UNIT_ADDRESS_FORM] = function (Container $container) {
            return $this->getCompanyUnitAddressFormPlugins();
        };

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
    protected function addCompanyUnitAddressTableConfigExpanderPlugins(Container $container)
    {
        $container[static::PLUGINS_COMPANY_UNIT_ADDRESS_TABLE_CONFIG_EXPANDER] = function (Container $container) {
            return $this->getCompanyUnitAddressTableConfigExpanderPlugins();
        };

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
    protected function addCompanyUnitAddressTableHeaderExpanderPlugins(Container $container)
    {
        $container[static::PLUGINS_COMPANY_UNIT_ADDRESS_TABLE_HEADER_EXPANDER] = function (Container $container) {
            return $this->getCompanyUnitAddressTableHeaderExpanderPlugins();
        };

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
    protected function addCompanyUnitAddressTableDataExpanderPlugins(Container $container)
    {
        $container[static::PLUGINS_COMPANY_UNIT_ADDRESS_TABLE_DATA_EXPANDER] = function (Container $container) {
            return $this->getCompanyUnitAddressTableDataExpanderPlugins();
        };

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
