<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequest;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ZedRequest\Dependency\Client\ZedRequestToMessengerClientBridge;

/**
 * @method \Spryker\Client\ZedRequest\ZedRequestConfig getConfig()
 */
class ZedRequestDependencyProvider extends AbstractDependencyProvider
{
    public const SERVICE_NETWORK = 'util network service';
    public const SERVICE_TEXT = 'util text service';
    public const META_DATA_PROVIDER_PLUGINS = 'META_DATA_PROVIDER_PLUGINS';
    public const CLIENT_MESSENGER = 'CLIENT_MESSENGER';
    public const PLUGINS_HEADER_EXPANDER = 'PLUGINS_HEADER_EXPANDER';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addUtilNetworkService($container);
        $container = $this->addUtilTextService($container);
        $container = $this->addMetaDataProviderPlugins($container);
        $container = $this->addMessengerClient($container);
        $container = $this->addHeaderExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addUtilNetworkService(Container $container)
    {
        $container->set(static::SERVICE_NETWORK, function (Container $container) {
            return $container->getLocator()->utilNetwork()->service();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addUtilTextService(Container $container)
    {
        $container->set(static::SERVICE_TEXT, function (Container $container) {
            return $container->getLocator()->utilText()->service();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addMetaDataProviderPlugins(Container $container)
    {
        $container->set(static::META_DATA_PROVIDER_PLUGINS, function (Container $container) {
            return $this->getMetaDataProviderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addMessengerClient(Container $container)
    {
        $container->set(static::CLIENT_MESSENGER, function (Container $container) {
            return new ZedRequestToMessengerClientBridge(
                $container->getLocator()->messenger()->client()
            );
        });

        return $container;
    }

    /**
     * Key value pair of mata data provider plugins, array key is the index key of transfer in
     * Spryker\Shared\ZedRequest\Client\AbstractRequest::metaTransfers, you can read back by this key in Zed.
     *
     * @return \Spryker\Client\ZedRequest\Dependency\Plugin\MetaDataProviderPluginInterface[]
     */
    protected function getMetaDataProviderPlugins()
    {
        return [];
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addHeaderExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_HEADER_EXPANDER, function () {
            return $this->getHeaderExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Client\ZedRequestExtension\Dependency\Plugin\HeaderExpanderPluginInterface[]
     */
    protected function getHeaderExpanderPlugins(): array
    {
        return [];
    }
}
