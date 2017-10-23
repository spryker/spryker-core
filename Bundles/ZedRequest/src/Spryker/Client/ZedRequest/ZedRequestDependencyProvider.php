<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequest;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class ZedRequestDependencyProvider extends AbstractDependencyProvider
{
    const SERVICE_NETWORK = 'util network service';
    const SERVICE_TEXT = 'util text service';
    const META_DATA_PROVIDER_PLUGINS = 'META_DATA_PROVIDER_PLUGINS';

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

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addUtilNetworkService(Container $container)
    {
        $container[static::SERVICE_NETWORK] = function (Container $container) {
            return $container->getLocator()->utilNetwork()->service();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addUtilTextService(Container $container)
    {
        $container[static::SERVICE_TEXT] = function (Container $container) {
            return $container->getLocator()->utilText()->service();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addMetaDataProviderPlugins(Container $container)
    {
        $container[static::META_DATA_PROVIDER_PLUGINS] = function (Container $container) {
            return $this->getMetaDataProviderPlugins();
        };

        return $container;
    }

    /**
     * Key value pair of mata data provider plugins, array key is the index key of transfer in
     * \Spryker\Shared\ZedRequest\Client\AbstractRequest::metaTransfers, you can read back by this key in zed.
     *
     * @return \Spryker\Client\ZedRequest\Dependency\Plugin\MetaDataProviderPluginInterface[]
     */
    protected function getMetaDataProviderPlugins()
    {
        return [];
    }
}
