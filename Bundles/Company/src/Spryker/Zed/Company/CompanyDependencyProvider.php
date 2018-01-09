<?php

namespace Spryker\Zed\Company;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CompanyDependencyProvider extends AbstractBundleDependencyProvider
{

    public const PLUGINS_COMPANY_SAVE = 'PLUGINS_COMPANY_SAVE';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addCompanySavePlugins($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addCompanySavePlugins(Container $container)
    {
        $container[static::PLUGINS_COMPANY_SAVE] = function (Container $container) {
            return $this->getCompanyUserSavePlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\Company\Dependency\CompanySavePluginInterface[]
     */
    protected function getCompanyUserSavePlugins()
    {
        return [];
    }

}