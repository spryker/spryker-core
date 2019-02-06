<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApi;

use Spryker\Glue\AuthRestApi\Dependency\Client\AuthRestApiToOauthClientBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\AuthRestApi\AuthRestApiConfig getConfig()
 */
class AuthRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_OAUTH = 'CLIENT_OAUTH';

    public const PLUGINS_REST_USER_IDENTIFIER_EXPANDER = 'PLUGINS_REST_USER_IDENTIFIER_EXPANDER';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addOauthClient($container);
        $container = $this->addRestUserIdentifierExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addOauthClient(Container $container): Container
    {
        $container[static::CLIENT_OAUTH] = function (Container $container) {
            return new AuthRestApiToOauthClientBridge($container->getLocator()->oauth()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addRestUserIdentifierExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_REST_USER_IDENTIFIER_EXPANDER] = function () {
            return $this->getRestUserIdentifierExpanderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Glue\AuthRestApiExtension\Dependency\Plugin\RestUserIdentifierExpanderPluginInterface[]
     */
    protected function getRestUserIdentifierExpanderPlugins(): array
    {
        return [];
    }
}
