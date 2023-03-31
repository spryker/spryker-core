<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreGui;

use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\StoreGui\Dependency\Facade\StoreGuiToStoreFacadeBridge;
use Spryker\Zed\StoreGui\Dependency\Service\StoreGuiToUtilEncodingServiceBridge;

/**
 * @method \Spryker\Zed\StoreGui\StoreGuiConfig getConfig()
 */
class StoreGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PROPEL_QUERY_STORE = 'PROPEL_QUERY_STORE';

    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @var string
     */
    public const PLUGINS_STORE_FORM_EXPANDER = 'PLUGINS_STORE_FORM_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_STORE_FORM_VIEW_EXPANDER = 'PLUGINS_STORE_FORM_VIEW_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_STORE_FORM_TABS_EXPANDER = 'PLUGINS_STORE_FORM_TABS_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_STORE_VIEW_EXPANDER = 'PLUGINS_STORE_VIEW_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_STORE_TABLE_EXPANDER = 'PLUGINS_STORE_TABLE_EXPANDER';

    /**
     * @uses \Spryker\Zed\Http\Communication\Plugin\Application\HttpApplicationPlugin::SERVICE_REQUEST_STACK
     *
     * @var string
     */
    public const SERVICE_REQUEST_STACK = 'request_stack';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addStorePropelQuery($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addStoreFormExpanderPlugins($container);
        $container = $this->addStoreFormViewExpanderPlugins($container);
        $container = $this->addStoreFormTabsExpanderPlugins($container);
        $container = $this->addStoreViewExpanderPlugins($container);
        $container = $this->addStoreTableExpanderPlugins($container);
        $container = $this->addRequestStack($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStorePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_STORE, $container->factory(function () {
            return SpyStoreQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container) {
            return new StoreGuiToStoreFacadeBridge(
                $container->getLocator()->store()->facade(),
            );
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
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new StoreGuiToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFormExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_STORE_FORM_EXPANDER, function () {
            return $this->getStoreFormExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFormViewExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_STORE_FORM_VIEW_EXPANDER, function () {
            return $this->getStoreFormViewExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFormTabsExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_STORE_FORM_TABS_EXPANDER, function () {
            return $this->getStoreFormTabsExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreViewExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_STORE_VIEW_EXPANDER, function () {
            return $this->getStoreViewExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreTableExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_STORE_TABLE_EXPANDER, function () {
            return $this->getStoreTableExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\StoreGuiExtension\Dependency\Plugin\StoreFormExpanderPluginInterface>
     */
    protected function getStoreFormExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\StoreGuiExtension\Dependency\Plugin\StoreFormViewExpanderPluginInterface>
     */
    protected function getStoreFormViewExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\StoreGuiExtension\Dependency\Plugin\StoreFormTabExpanderPluginInterface>
     */
    protected function getStoreFormTabsExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\StoreGuiExtension\Dependency\Plugin\StoreViewExpanderPluginInterface>
     */
    protected function getStoreViewExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\StoreGuiExtension\Dependency\Plugin\StoreTableExpanderPluginInterface>
     */
    protected function getStoreTableExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRequestStack(Container $container): Container
    {
        $container->set(static::SERVICE_REQUEST_STACK, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_REQUEST_STACK);
        });

        return $container;
    }
}
