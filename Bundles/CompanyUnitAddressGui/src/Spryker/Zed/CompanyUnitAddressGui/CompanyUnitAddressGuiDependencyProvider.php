<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressGui;

use Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade\CompanyUnitAddressGuiToCompanyUnitAddressFacadeBridge;
use Spryker\Zed\CompanyUnitAddressGui\Dependency\QueryContainer\CompanyUnitAddressGuiToCompanyUnitAddressQueryContainerBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CompanyUnitAddressGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    const QUERY_CONTAINER_COMPANY_UNIT_ADDRESS = 'QUERY_CONTAINER_COMPANY_UNIT_ADDRESS';
    const FACADE_COMPANY_UNIT_ADDRESS = 'FACADE_COMPANY_UNIT_ADDRESS';
    const PLUGINS_COMPANY_UNIT_ADDRESS_FORM = 'PLUGINS_COMPANY_UNIT_ADDRESS_FORM';

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
    protected function addCompanyUnitAddressFormPlugins(Container $container)
    {
        $container[static::PLUGINS_COMPANY_UNIT_ADDRESS_FORM] = function (Container $container) {
            return $this->getCompanyUnitAddressFormPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressGuiExtension\Communication\Plugin\CompanyUnitAddressEditFormExpanderPluginInterface[]
     */
    protected function getCompanyUnitAddressFormPlugins(): array
    {
        return [];
    }
}
