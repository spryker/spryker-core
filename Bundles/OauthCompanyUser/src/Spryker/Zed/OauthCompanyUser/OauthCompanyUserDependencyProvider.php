<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCompanyUser;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\OauthCompanyUser\Dependency\Facade\OauthCompanyUserToCompanyUserFacadeBridge;
use Spryker\Zed\OauthCompanyUser\Dependency\Facade\OauthCompanyUserToCustomerFacadeBridge;
use Spryker\Zed\OauthCompanyUser\Dependency\Facade\OauthCompanyUserToOauthFacadeBridge;
use Spryker\Zed\OauthCompanyUser\Dependency\Service\OauthCompanyUserToUtilEncodingServiceBridge;

/**
 * @method \Spryker\Zed\OauthCompanyUser\OauthCompanyUserConfig getConfig()
 */
class OauthCompanyUserDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_COMPANY_USER = 'FACADE_COMPANY_USER';
    public const FACADE_OAUTH = 'FACADE_OAUTH';
    public const FACADE_CUSTOMER = 'FACADE_CUSTOMER';

    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    public const PLUGINS_OAUTH_COMPANY_USER_IDENTIFIER_EXPANDER = 'PLUGINS_OAUTH_COMPANY_USER_IDENTIFIER_EXPANDER';
    public const PLUGINS_CUSTOMER_OAUTH_REQUEST_MAPPER = 'PLUGINS_CUSTOMER_OAUTH_REQUEST_MAPPER';
    public const PLUGINS_CUSTOMER_EXPANDER = 'PLUGINS_CUSTOMER_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addOauthFacade($container);
        $container = $this->addCompanyUserFacade($container);
        $container = $this->addCustomerFacade($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addOauthCompanyUserIdentifierExpanderPlugins($container);
        $container = $this->addCustomerOauthRequestMapperPlugins($container);
        $container = $this->addCustomerExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addCompanyUserFacade($container);
        $container = $this->addUtilEncodingService($container);

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
            return new OauthCompanyUserToCompanyUserFacadeBridge($container->getLocator()->companyUser()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOauthFacade(Container $container): Container
    {
        $container[static::FACADE_OAUTH] = function (Container $container) {
            return new OauthCompanyUserToOauthFacadeBridge($container->getLocator()->oauth()->facade());
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
            return new OauthCompanyUserToCustomerFacadeBridge($container->getLocator()->customer()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container[static::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new OauthCompanyUserToUtilEncodingServiceBridge($container->getLocator()->utilEncoding()->service());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOauthCompanyUserIdentifierExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_OAUTH_COMPANY_USER_IDENTIFIER_EXPANDER] = function () {
            return $this->getOauthCompanyUserIdentifierExpanderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\OauthCompanyUserExtension\Dependency\Plugin\OauthCompanyUserIdentifierExpanderPluginInterface[]
     */
    protected function getOauthCompanyUserIdentifierExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerOauthRequestMapperPlugins(Container $container): Container
    {
        $container[static::PLUGINS_CUSTOMER_OAUTH_REQUEST_MAPPER] = function () {
            return $this->getCustomerOauthRequestMapperPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_CUSTOMER_EXPANDER] = function () {
            return $this->getCustomerExpanderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\OauthCompanyUserExtension\Dependency\Plugin\CustomerOauthRequestMapperPluginInterface[]
     */
    protected function getCustomerOauthRequestMapperPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\OauthCompanyUserExtension\Dependency\Plugin\CustomerExpanderPluginInterface[]
     */
    protected function getCustomerExpanderPlugins(): array
    {
        return [];
    }
}
