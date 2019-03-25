<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApi;

use Spryker\Glue\AuthRestApi\Dependency\Client\AuthRestApiToOauthClientBridge;
use Spryker\Glue\AuthRestApi\Dependency\Service\AuthRestApiToOauthServiceBridge;
use Spryker\Glue\AuthRestApi\Dependency\Service\AuthRestApiToUtilEncodingServiceBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\AuthRestApi\AuthRestApiConfig getConfig()
 */
class AuthRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_OAUTH = 'CLIENT_OAUTH';

    public const SERVICE_OAUTH = 'SERVICE_OAUTH';
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    public const PLUGINS_REST_USER_EXPANDER = 'PLUGINS_REST_USER_EXPANDER';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addOauthClient($container);
        $container = $this->addOauthService($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addRestUserExpanderPlugins($container);

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
    protected function addOauthService(Container $container): Container
    {
        $container[static::SERVICE_OAUTH] = function (Container $container) {
            return new AuthRestApiToOauthServiceBridge(
                $container->getLocator()->oauth()->service()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container[static::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new AuthRestApiToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addRestUserExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_REST_USER_EXPANDER] = function () {
            return $this->getRestUserExpanderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Glue\AuthRestApiExtension\Dependency\Plugin\RestUserMapperPluginInterface[]
     */
    protected function getRestUserExpanderPlugins(): array
    {
        return [];
    }
}
