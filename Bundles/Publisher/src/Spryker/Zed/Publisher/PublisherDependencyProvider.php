<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Publisher;

use Spryker\Zed\GlossaryStorage\Communication\Plugin\Publisher\GlossaryKey\GlossaryPublisherTriggerPlugin;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Publisher\Dependency\Facade\PublisherToEventBehaviorFacadeBridge;

/**
 * @method \Spryker\Zed\Publisher\PublisherConfig getConfig()
 */
class PublisherDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_PUBLISHER_REGISTRY = 'PLUGINS_PUBLISHER_REGISTRY';
    public const PLUGINS_RESOURCE_PUBLISHER = 'PLUGINS_RESOURCE_PUBLISHER';

    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addPublisherRegistryPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addEventBehaviorFacade($container);
        $container = $this->addResourcePublishedPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPublisherRegistryPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PUBLISHER_REGISTRY, function (Container $container) {
            return $this->getPublisherRegistryPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventBehaviorFacade(Container $container): Container
    {
        $container->set(static::FACADE_EVENT_BEHAVIOR, function (Container $container) {
            return new PublisherToEventBehaviorFacadeBridge(
                $container->getLocator()->eventBehavior()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addResourcePublishedPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_RESOURCE_PUBLISHER, function (Container $container) {
            return $this->getResourcePublisherPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherRegistryPluginInterface[]
     */
    protected function getPublisherRegistryPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface[]
     */
    protected function getResourcePublisherPlugins(): array
    {
        return [];
    }
}
