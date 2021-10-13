<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress;

use Spryker\Zed\CompanyUnitAddress\Dependency\Facade\CompanyUnitAddressToCompanyBusinessUnitFacadeBridge;
use Spryker\Zed\CompanyUnitAddress\Dependency\Facade\CompanyUnitAddressToCountryFacadeBridge;
use Spryker\Zed\CompanyUnitAddress\Dependency\Facade\CompanyUnitAddressToLocaleFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CompanyUnitAddress\CompanyUnitAddressConfig getConfig()
 */
class CompanyUnitAddressDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_COUNTRY = 'FACADE_COUNTRY';
    /**
     * @var string
     */
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    /**
     * @var string
     */
    public const FACADE_COMPANY_BUSINESS_UNIT = 'FACADE_COMPANY_BUSINESS_UNIT';
    /**
     * @var string
     */
    public const PLUGIN_ADDRESS_POST_SAVE = 'PLUGIN_ADDRESS_POST_SAVE';
    /**
     * @var string
     */
    public const PLUGIN_ADDRESS_TRANSFER_HYDRATING = 'PLUGIN_ADDRESS_TRANSFER_HYDRATING';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addCountryFacade($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addCompanyBusinessUnitFacade($container);
        $container = $this->addAddressPostUpdatePlugins($container);
        $container = $this->addCompanyUnitAddressHydratePlugins($container);

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
            return new CompanyUnitAddressToCountryFacadeBridge($container->getLocator()->country()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new CompanyUnitAddressToLocaleFacadeBridge($container->getLocator()->locale()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyBusinessUnitFacade(Container $container): Container
    {
        $container->set(static::FACADE_COMPANY_BUSINESS_UNIT, function (Container $container) {
            return new CompanyUnitAddressToCompanyBusinessUnitFacadeBridge($container->getLocator()->companyBusinessUnit()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAddressPostUpdatePlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_ADDRESS_POST_SAVE, function (Container $container) {
            return $this->getCompanyUnitAddressPostSavePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUnitAddressHydratePlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_ADDRESS_TRANSFER_HYDRATING, function (Container $container) {
            return $this->getCompanyUnitAddressHydratePlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\CompanyUnitAddressExtension\Dependency\Plugin\CompanyUnitAddressPostSavePluginInterface>
     */
    protected function getCompanyUnitAddressPostSavePlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\CompanyUnitAddressExtension\Dependency\Plugin\CompanyUnitAddressHydratePluginInterface>
     */
    protected function getCompanyUnitAddressHydratePlugins(): array
    {
        return [];
    }
}
