<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AnalyticsGui;

use Spryker\Zed\AnalyticsGui\Dependency\Facade\AnalyticsGuiToUserFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class AnalyticsGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_USER = 'FACADE_USER';

    /**
     * @var string
     */
    public const PLUGINS_ANALYTICS_COLLECTION_EXPANDER = 'PLUGINS_ANALYTICS_COLLECTION_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addUserFacade($container);
        $container = $this->addAnalyticsCollectionExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_USER, function (Container $container) {
            return new AnalyticsGuiToUserFacadeBridge($container->getLocator()->user()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAnalyticsCollectionExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ANALYTICS_COLLECTION_EXPANDER, function () {
            return $this->getAnalyticsCollectionExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Zed\AnalyticsGuiExtension\Dependency\Plugin\AnalyticsCollectionExpanderPluginInterface>
     */
    protected function getAnalyticsCollectionExpanderPlugins(): array
    {
        return [];
    }
}
