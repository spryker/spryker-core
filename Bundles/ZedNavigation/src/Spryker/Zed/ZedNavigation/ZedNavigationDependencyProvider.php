<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation;

use Spryker\Shared\Url\UrlBuilder;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ZedNavigation\Dependency\Facade\ZedNavigationToRouterFacadeBridge;
use Spryker\Zed\ZedNavigation\Dependency\Util\ZedNavigationToUtilEncodingBridge;

/**
 * @method \Spryker\Zed\ZedNavigation\ZedNavigationConfig getConfig()
 */
class ZedNavigationDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const URL_BUILDER = 'url builder';
    /**
     * @var string
     */
    public const SERVICE_ENCODING = 'util encoding service';
    /**
     * @var string
     */
    public const FACADE_ROUTER = 'FACADE_ROUTER';

    /**
     * @deprecated Use {@link \Spryker\Zed\ZedNavigation\ZedNavigationDependencyProvider::PLUGINS_NAVIGATION_ITEM_COLLECTION_FILTER} instead.
     * @var string
     */
    public const PLUGINS_NAVIGATION_ITEM_FILTER = 'PLUGINS_NAVIGATION_ITEM_FILTER';
    /**
     * @var string
     */
    public const PLUGINS_NAVIGATION_ITEM_COLLECTION_FILTER = 'PLUGINS_NAVIGATION_ITEM_COLLECTION_FILTER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addUrlBuilder($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addNavigationItemFilterPlugins($container);
        $container = $this->addNavigationItemCollectionFilterPlugins($container);
        $container = $this->addRouterFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUrlBuilder(Container $container): Container
    {
        $container->set(static::URL_BUILDER, function () {
            return new UrlBuilder();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_ENCODING, function (Container $container) {
            return new ZedNavigationToUtilEncodingBridge(
                $container->getLocator()->utilEncoding()->service()
            );
        });

        return $container;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\ZedNavigation\ZedNavigationDependencyProvider::addNavigationItemCollectionFilterPlugins()} instead.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addNavigationItemFilterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_NAVIGATION_ITEM_FILTER, function () {
            return $this->getNavigationItemFilterPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addNavigationItemCollectionFilterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_NAVIGATION_ITEM_COLLECTION_FILTER, function () {
            return $this->getNavigationItemCollectionFilterPlugins();
        });

        return $container;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\ZedNavigation\ZedNavigationDependencyProvider::getNavigationItemCollectionFilterPlugins()} instead.
     *
     * @return array<\Spryker\Zed\ZedNavigationExtension\Dependency\Plugin\NavigationItemFilterPluginInterface>
     */
    protected function getNavigationItemFilterPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\ZedNavigationExtension\Dependency\Plugin\NavigationItemCollectionFilterPluginInterface>
     */
    protected function getNavigationItemCollectionFilterPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRouterFacade(Container $container): Container
    {
        $container->set(static::FACADE_ROUTER, function (Container $container) {
            return new ZedNavigationToRouterFacadeBridge($container->getLocator()->router()->facade());
        });

        return $container;
    }
}
