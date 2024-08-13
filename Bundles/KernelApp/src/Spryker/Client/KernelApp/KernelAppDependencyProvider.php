<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\KernelApp;

use GuzzleHttp\Client as GuzzleHttpClient;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\KernelApp\Dependency\External\KernelAppToGuzzleHttpClientAdapter;

/**
 * @method \Spryker\Client\KernelApp\KernelAppConfig getConfig()
 */
class KernelAppDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_HTTP = 'CLIENT_HTTP';

    /**
     * @var string
     */
    public const REQUEST_EXPANDER_PLUGINS = 'REQUEST_EXPANDER_PLUGINS';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = $this->addHttpClient($container);
        $container = $this->addRequestExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addHttpClient(Container $container): Container
    {
        $container->set(static::CLIENT_HTTP, function () {
            // @codeCoverageIgnoreStart
            // The GuzzleClient has to be mocked for testing, no coverage possible.
            return new KernelAppToGuzzleHttpClientAdapter(new GuzzleHttpClient());
            // @codeCoverageIgnoreEnd
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addRequestExpanderPlugins(Container $container): Container
    {
        $container->set(static::REQUEST_EXPANDER_PLUGINS, function () {
            return $this->getRequestExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Shared\KernelAppExtension\RequestExpanderPluginInterface>
     */
    public function getRequestExpanderPlugins(): array
    {
        return [];
    }
}
