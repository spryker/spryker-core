<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplicationAuthorizationConnector;

use Spryker\Glue\GlueBackendApiApplicationAuthorizationConnector\Dependency\Facade\GlueBackendApiApplicationAuthorizationConnectorToAuthorizationFacadeBridge;
use Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Backend\Container;

/**
 * @method \Spryker\Glue\GlueBackendApiApplicationAuthorizationConnector\GlueBackendApiApplicationAuthorizationConnectorConfig getConfig()
 */
class GlueBackendApiApplicationAuthorizationConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_AUTHORIZATION = 'FACADE_AUTHORIZATION';

    /**
     * @var string
     */
    public const FACADE_GLUE_BACKEND_API_APPLICATION_AUTHORIZATION_CONNECTOR = 'FACADE_GLUE_BACKEND_API_APPLICATION_AUTHORIZATION_CONNECTOR';

    /**
     * @var string
     */
    public const PLUGINS_AUTHORIZATION_REQUEST_EXPANDER = 'PLUGINS_AUTHORIZATION_REQUEST_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_PROTECTED_ROUTE_AUTHORIZATION_CONFIG_PROVIDER = 'PLUGINS_PROTECTED_ROUTE_AUTHORIZATION_CONFIG_PROVIDER';

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    public function provideBackendDependencies(Container $container): Container
    {
        $container = parent::provideBackendDependencies($container);
        $container = $this->addAuthorizationFacade($container);
        $container = $this->addGlueBackendApiApplicationAuthorizationConnectorFacade($container);
        $container = $this->addAuthorizationRequestExpanderPlugins($container);
        $container = $this->addProtectedRouteAuthorizationConfigProviderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addAuthorizationFacade(Container $container): Container
    {
        $container->set(static::FACADE_AUTHORIZATION, function (Container $container) {
            return new GlueBackendApiApplicationAuthorizationConnectorToAuthorizationFacadeBridge($container->getLocator()->authorization()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addGlueBackendApiApplicationAuthorizationConnectorFacade(Container $container): Container
    {
        // phpcs:ignore
        $container->set(static::FACADE_GLUE_BACKEND_API_APPLICATION_AUTHORIZATION_CONNECTOR, function (Container $container) {
            return $container->getLocator()->glueBackendApiApplicationAuthorizationConnector()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addAuthorizationRequestExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_AUTHORIZATION_REQUEST_EXPANDER, function (Container $container) {
            return $this->getAuthorizationRequestExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Glue\GlueBackendApiApplicationAuthorizationConnectorExtension\Dependency\Plugin\AuthorizationRequestExpanderPluginInterface>
     */
    protected function getAuthorizationRequestExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addProtectedRouteAuthorizationConfigProviderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PROTECTED_ROUTE_AUTHORIZATION_CONFIG_PROVIDER, function (Container $container) {
            return $this->getProtectedRouteAuthorizationConfigProviderPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Glue\GlueBackendApiApplicationAuthorizationConnectorExtension\Dependency\Plugin\ProtectedRouteAuthorizationConfigProviderPluginInterface>
     */
    protected function getProtectedRouteAuthorizationConfigProviderPlugins(): array
    {
        return [];
    }
}
