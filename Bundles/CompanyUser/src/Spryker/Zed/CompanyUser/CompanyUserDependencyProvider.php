<?php

namespace Spryker\Zed\CompanyUser;

use Spryker\Zed\CompanyUser\Dependency\CompanyUserHydrationPluginInterface;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CompanyUserDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_CUSTOMER_SAVE = 'PLUGINS_CUSTOMER_SAVE';
    public const PLUGINS_CUSTOMER_HYDRATE = 'PLUGINS_CUSTOMER_HYDRATE';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addCustomerSavePlugins($container);
        $container = $this->addUserDaveHydrationPlugins($container);

        return $container;
    }


    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addCustomerSavePlugins(Container $container)
    {
        $container[static::PLUGINS_CUSTOMER_SAVE] = function (Container $container) {
            return $this->getCompanyUserSavePlugins();
        };

        return $container;
    }

    protected function addUserDaveHydrationPlugins(Container $container)
    {
        $container[static::PLUGINS_CUSTOMER_HYDRATE] = function (Container $container) {
            return $this->getCompanyUserHydrationPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\CompanyUser\Dependency\CompanyUserSavePluginInterface[]
     */
    protected function getCompanyUserSavePlugins()
    {
        return [];
    }


    /**
     * @return CompanyUserHydrationPluginInterface[]
     */
    protected function getCompanyUserHydrationPlugins()
    {
        return [];
    }
}