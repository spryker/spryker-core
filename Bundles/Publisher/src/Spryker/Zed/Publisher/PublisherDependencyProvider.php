<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Publisher;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\Publisher\PublisherConfig getConfig()
 */
class PublisherDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PUBLISHER_REGISTRY_PLUGINS = 'PUBLISHER_REGISTRY_PLUGINS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[static::PUBLISHER_REGISTRY_PLUGINS] = function (Container $container) {
            return $this->getPublisherRegistryPlugins();
        };
    }

    /**
     * @return \Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherRegistryPluginInterface[]
     */
    public function getPublisherRegistryPlugins(): array
    {
        return [];
    }
}
