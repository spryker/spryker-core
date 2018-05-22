<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyGui;

use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Spryker\Zed\CompanyGui\Dependency\Facade\CompanyGuiToCompanyFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CompanyGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PROPEL_COMPANY_QUERY = 'PROPEL_COMPANY_QUERY';
    public const FACADE_COMPANY = 'FACADE_COMPANY';
    public const PLUGINS_COMPANY_TABLE_CONFIG_EXPANDER = 'PLUGINS_COMPANY_TABLE_CONFIG_EXPANDER';
    public const PLUGINS_COMPANY_TABLE_HEADER_EXPANDER = 'PLUGINS_COMPANY_HEADER_TABLE_EXPANDER';
    public const PLUGINS_COMPANY_TABLE_DATA_EXPANDER = 'PLUGINS_COMPANY_TABLE_DATA_EXPANDER';
    public const PLUGINS_COMPANY_TABLE_ACTION_EXPANDER = 'PLUGINS_COMPANY_TABLE_ACTION_EXPANDER';
    public const PLUGINS_COMPANY_FORM_EXPANDER = 'PLUGINS_COMPANY_FORM_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addPropelCompanyQuery($container);
        $container = $this->addCompanyFacade($container);
        $container = $this->addCompanyTableConfigExpanderPlugins($container);
        $container = $this->addCompanyTableHeaderExpanderPlugins($container);
        $container = $this->addCompanyTableDataExpanderPlugins($container);
        $container = $this->addCompanyTableActionExpanderPlugins($container);
        $container = $this->addCompanyFormPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPropelCompanyQuery(Container $container): Container
    {
        $container[static::PROPEL_COMPANY_QUERY] = function (Container $container) {
            return SpyCompanyQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyFacade(Container $container): Container
    {
        $container[static::FACADE_COMPANY] = function (Container $container) {
            return new CompanyGuiToCompanyFacadeBridge($container->getLocator()->company()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyTableConfigExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_COMPANY_TABLE_CONFIG_EXPANDER] = function (Container $container) {
            return $this->getCompanyTableConfigExpanderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\CompanyGuiExtension\Dependency\Plugin\CompanyTableConfigExpanderPluginInterface[]
     */
    protected function getCompanyTableConfigExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyTableHeaderExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_COMPANY_TABLE_HEADER_EXPANDER] = function (Container $container) {
            return $this->getCompanyTableHeaderExpanderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\CompanyGuiExtension\Dependency\Plugin\CompanyTableHeaderExpanderPluginInterface[]
     */
    protected function getCompanyTableHeaderExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyTableDataExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_COMPANY_TABLE_DATA_EXPANDER] = function (Container $container) {
            return $this->getCompanyTableDataExpanderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\CompanyGuiExtension\Dependency\Plugin\CompanyTableDataExpanderPluginInterface[]
     */
    protected function getCompanyTableDataExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyTableActionExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_COMPANY_TABLE_ACTION_EXPANDER] = function (Container $container) {
            return $this->getCompanyTableActionExpanderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\CompanyGuiExtension\Dependency\Plugin\CompanyTableActionExpanderInterface[]
     */
    protected function getCompanyTableActionExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyFormPlugins(Container $container): Container
    {
        $container[static::PLUGINS_COMPANY_FORM_EXPANDER] = function (Container $container) {
            return $this->getCompanyFormPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\CompanyGuiExtension\Dependency\Plugin\CompanyFormExpanderPluginInterface[]
     */
    protected function getCompanyFormPlugins(): array
    {
        return [];
    }
}
