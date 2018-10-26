<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui;

use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCompanyFacadeBridge;
use Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCompanyUserFacadeBridge;
use Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCustomerFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CompanyUserGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_COMPANY_USER = 'FACADE_COMPANY_USER';
    public const FACADE_COMPANY = 'FACADE_COMPANY';
    public const FACADE_COMPANY_ROLE = 'FACADE_COMPANY_ROLE';
    public const FACADE_CUSTOMER = 'FACADE_CUSTOMER';
    public const PROPEL_QUERY_COMPANY_USER = 'PROPEL_QUERY_COMPANY_USER';

    public const PLUGINS_COMPANY_USER_TABLE_CONFIG_EXPANDER = 'PLUGINS_COMPANY_USER_TABLE_CONFIG_EXPANDER';
    public const PLUGINS_COMPANY_USER_TABLE_PREPARE_DATA_EXPANDER = 'PLUGINS_COMPANY_USER_TABLE_PREPARE_DATA_EXPANDER';
    public const PLUGINS_COMPANY_USER_FORM_EXPANDER = 'PLUGINS_COMPANY_USER_FORM_EXPANDER';
    public const PLUGINS_COMPANY_USER_ATTACH_CUSTOMER_FORM_EXPANDER = 'PLUGINS_COMPANY_USER_ATTACH_CUSTOMER_FORM_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addCompanyUserFacade($container);
        $container = $this->addCompanyFacade($container);
        $container = $this->addCustomerFacade($container);
        $container = $this->addCompanyUserPropelQuery($container);
        $container = $this->addCompanyUserTableConfigExpanderPlugins($container);
        $container = $this->addCompanyUserTablePrepareDataExpanderPlugins($container);
        $container = $this->addCompanyUserFormExpanderPlugins($container);
        $container = $this->addCompanyUserAttachCustomerFormExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUserFacade(Container $container): Container
    {
        $container[static::FACADE_COMPANY_USER] = function (Container $container) {
            return new CompanyUserGuiToCompanyUserFacadeBridge(
                $container->getLocator()->companyUser()->facade()
            );
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
            return new CompanyUserGuiToCompanyFacadeBridge(
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
    protected function addCustomerFacade(Container $container): Container
    {
        $container[static::FACADE_CUSTOMER] = function (Container $container) {
            return new CompanyUserGuiToCustomerFacadeBridge(
                $container->getLocator()->customer()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUserFormExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_COMPANY_USER_FORM_EXPANDER] = function (Container $container) {
            return $this->getCompanyUserFormExpanderPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUserPropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_COMPANY_USER] = function (Container $container) {
            return SpyCompanyUserQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUserTableConfigExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_COMPANY_USER_TABLE_CONFIG_EXPANDER] = function (Container $container) {
            return $this->getCompanyUserTableConfigExpanderPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUserAttachCustomerFormExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_COMPANY_USER_ATTACH_CUSTOMER_FORM_EXPANDER] = function (Container $container) {
            return $this->getCompanyUserAttachCustomerFormExpanderPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUserTablePrepareDataExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_COMPANY_USER_TABLE_PREPARE_DATA_EXPANDER] = function (Container $container) {
            return $this->getCompanyUserTablePrepareDataExpanderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserTableConfigExpanderPluginInterface[]
     */
    protected function getCompanyUserTableConfigExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserTablePrepareDataExpanderPluginInterface[]
     */
    protected function getCompanyUserTablePrepareDataExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserFormExpanderPluginInterface[]
     */
    protected function getCompanyUserFormExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserAttachCustomerFormExpanderPluginInterface[]
     */
    protected function getCompanyUserAttachCustomerFormExpanderPlugins(): array
    {
        return [];
    }
}
