<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser;

use Spryker\Zed\CompanyUser\Dependency\Facade\CompanyUserToCustomerFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CompanyUserDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_CUSTOMER = 'FACADE_CUSTOMER';

    public const PLUGINS_COMPANY_USER_PRE_SAVE = 'PLUGINS_COMPANY_USER_PRE_SAVE';
    public const PLUGINS_COMPANY_USER_POST_SAVE = 'PLUGINS_COMPANY_USER_POST_SAVE';
    public const PLUGINS_COMPANY_USER_POST_CREATE = 'PLUGINS_COMPANY_USER_POST_CREATE';
    public const PLUGINS_COMPANY_USER_HYDRATE = 'PLUGINS_COMPANY_USER_HYDRATE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addCompanyUserPreSavePlugins($container);
        $container = $this->addCompanyUserPostSavePlugins($container);
        $container = $this->addCompanyUserPostCreatePlugins($container);
        $container = $this->addCompanyUserHydrationPlugins($container);
        $container = $this->addCustomerFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerFacade(Container $container): Container
    {
        $container[static::FACADE_CUSTOMER] = function (Container $container) {
            return new CompanyUserToCustomerFacadeBridge($container->getLocator()->customer()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUserPreSavePlugins(Container $container): Container
    {
        $container[static::PLUGINS_COMPANY_USER_PRE_SAVE] = function () {
            return $this->getCompanyUserPreSavePlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUserPostSavePlugins(Container $container): Container
    {
        $container[static::PLUGINS_COMPANY_USER_POST_SAVE] = function () {
            return $this->getCompanyUserPostSavePlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUserPostCreatePlugins(Container $container): Container
    {
        $container[static::PLUGINS_COMPANY_USER_POST_CREATE] = function () {
            return $this->getCompanyUserPostCreatePlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUserHydrationPlugins(Container $container): Container
    {
        $container[static::PLUGINS_COMPANY_USER_HYDRATE] = function () {
            return $this->getCompanyUserHydrationPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserPreSavePluginInterface[]
     */
    protected function getCompanyUserPreSavePlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserPostSavePluginInterface[]
     */
    protected function getCompanyUserPostSavePlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserHydrationPluginInterface[]
     */
    protected function getCompanyUserHydrationPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserPostCreatePluginInterface[]
     */
    protected function getCompanyUserPostCreatePlugins(): array
    {
        return [];
    }
}
