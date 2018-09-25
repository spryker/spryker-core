<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui;

use Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCompanyFacadeBridge;
use Spryker\Zed\CompanyUserGui\Dependency\Facade\CompanyUserGuiToCompanyUserFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CompanyUserGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_COMPANY_USER = 'FACADE_COMPANY_USER';
    public const FACADE_COMPANY = 'FACADE_COMPANY';
    public const PLUGINS_CUSTOMER_COMPANY_ATTACH_FORM_EXPANDER = 'PLUGINS_CUSTOMER_COMPANY_ATTACH_FORM_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->provideCompanyUserFacade($container);
        $container = $this->provideCompanyFacade($container);
        $container = $this->addCustomerCompanyAttachFormPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideCompanyUserFacade(Container $container): Container
    {
        $container[static::FACADE_COMPANY_USER] = function (Container $container) {
            return new CompanyUserGuiToCompanyUserFacadeBridge($container->getLocator()->companyUser()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideCompanyFacade(Container $container): Container
    {
        $container[static::FACADE_COMPANY] = function (Container $container) {
            return new CompanyUserGuiToCompanyFacadeBridge($container->getLocator()->company()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerCompanyAttachFormPlugins(Container $container): Container
    {
        $container[static::PLUGINS_CUSTOMER_COMPANY_ATTACH_FORM_EXPANDER] = function (Container $container) {
            return $this->getCustomerCompanyAttachFormPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CustomerCompanyAttachFormExpanderPluginInterface[]
     */
    protected function getCustomerCompanyAttachFormPlugins(): array
    {
        return [];
    }
}
