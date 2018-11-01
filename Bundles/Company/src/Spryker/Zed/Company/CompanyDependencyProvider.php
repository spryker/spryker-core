<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Company;

use Spryker\Zed\Company\Dependency\Facade\CompanyToStoreFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CompanyDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_STORE = 'FACADE_STORE';
    public const COMPANY_PRE_SAVE_PLUGINS = 'COMPANY_PRE_SAVE_PLUGINS';
    public const COMPANY_POST_SAVE_PLUGINS = 'COMPANY_POST_SAVE_PLUGINS';
    public const COMPANY_POST_CREATE_PLUGINS = 'COMPANY_POST_CREATE_PLUGINS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addStoreFacade($container);
        $container = $this->addCompanyPreSavePlugins($container);
        $container = $this->addCompanyPostSavePlugins($container);
        $container = $this->addCompanyPostCreatePlugins($container);

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
            return new CompanyToStoreFacadeBridge($container->getLocator()->store()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyPreSavePlugins(Container $container): Container
    {
        $container[static::COMPANY_PRE_SAVE_PLUGINS] = function () {
            return $this->getCompanyPreSavePlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyPostSavePlugins(Container $container): Container
    {
        $container[static::COMPANY_POST_SAVE_PLUGINS] = function () {
            return $this->getCompanyPostSavePlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyPostCreatePlugins(Container $container): Container
    {
        $container[static::COMPANY_POST_CREATE_PLUGINS] = function () {
            return $this->getCompanyPostCreatePlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\CompanyExtension\Dependency\Plugin\CompanyPreSavePluginInterface[]
     */
    protected function getCompanyPreSavePlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\CompanyExtension\Dependency\Plugin\CompanyPostSavePluginInterface[]
     */
    protected function getCompanyPostSavePlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\CompanyExtension\Dependency\Plugin\CompanyPostCreatePluginInterface[]
     */
    protected function getCompanyPostCreatePlugins(): array
    {
        return [];
    }
}
