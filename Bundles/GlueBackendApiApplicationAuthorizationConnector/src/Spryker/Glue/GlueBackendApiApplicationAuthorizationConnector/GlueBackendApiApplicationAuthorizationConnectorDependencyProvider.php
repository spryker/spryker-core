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
    public const PLUGINS_AUTHORIZATION_REQUEST_EXPANDER = 'PLUGINS_AUTHORIZATION_REQUEST_EXPANDER';

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    public function provideBackendDependencies(Container $container): Container
    {
        $container = parent::provideBackendDependencies($container);
        $container = $this->addAuthorizationFacade($container);
        $container = $this->addAuthorizationRequestExpanderPlugins($container);

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
}
