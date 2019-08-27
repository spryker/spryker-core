<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthRestApi;

use Spryker\Zed\AuthRestApi\Dependency\Facade\AuthRestApiToOauthFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\AuthRestApi\AuthRestApiConfig getConfig()
 */
class AuthRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_OAUTH = 'FACADE_OAUTH';

    public const PLUGINS_POST_AUTH = 'PLUGINS_POST_AUTH';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addOauthFacade($container);
        $container = $this->addPostAuthPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOauthFacade(Container $container): Container
    {
        $container->set(static::FACADE_OAUTH, function (Container $container) {
            return new AuthRestApiToOauthFacadeBridge($container->getLocator()->oauth()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPostAuthPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_POST_AUTH, function () {
            return $this->getPostAuthPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\AuthRestApiExtension\Dependency\Plugin\PostAuthPluginInterface[]
     */
    protected function getPostAuthPlugins(): array
    {
        return [];
    }
}
