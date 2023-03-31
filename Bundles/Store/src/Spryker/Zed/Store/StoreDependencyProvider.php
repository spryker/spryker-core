<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store;

use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Store\Dependency\Adapter\StoreToKernelStoreAdapter;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\Store\StoreConfig getConfig()
 */
class StoreDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @deprecated Will be removed after dynamic multi-store is always enabled.
     *
     * @var string
     */
    public const STORE = 'STORE';

    /**
     * @var string
     */
    public const DYNAMIC_STORE_MODE = 'DYNAMIC_STORE_MODE';

    /**
     * @var string
     */
    public const STORE_CURRENT = 'STORE_CURRENT';

    /**
     * @var string
     */
    public const PLUGINS_STORE_PRE_CREATE_VALIDATION = 'PLUGINS_STORE_PRE_CREATE_VALIDATION';

    /**
     * @var string
     */
    public const PLUGINS_STORE_PRE_UPDATE_VALIDATION = 'PLUGINS_STORE_PRE_UPDATE_VALIDATION';

    /**
     * @var string
     */
    public const PLUGINS_STORE_POST_CREATE = 'PLUGINS_STORE_POST_CREATE';

    /**
     * @var string
     */
    public const PLUGINS_STORE_POST_UPDATE = 'PLUGINS_STORE_POST_UPDATE';

    /**
     * @var string
     */
    public const PLUGINS_STORE_COLLECTION_EXPANDER = 'PLUGINS_STORE_COLLECTION_EXPANDER';

    /**
     * @var string
     */
    public const SERVICE_STORE = 'store';

    /**
     * @var string
     */
    public const CURRENT_STORE_PROVIDED_FLAG = 'CURRENT_STORE_PROVIDED_FLAG';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addStore($container);
        $container = $this->addCurrentStore($container);
        $container = $this->addDynamicStoreMode($container);
        $container = $this->addStoreCollectionExpanderPlugins($container);
        $container = $this->addStorePreCreatePlugins($container);
        $container = $this->addStorePreUpdatePlugins($container);
        $container = $this->addStorePostCreatePlugins($container);
        $container = $this->addStorePostUpdatePlugins($container);
        $container = $this->addCurrentStoreProvidedFlag($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = $this->addStore($container);
        $container = $this->addCurrentStore($container);

        return $container;
    }

    /**
     * @deprecated Will be removed after dynamic multi-store is always enabled.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStore(Container $container)
    {
        $container->set(static::STORE, function (Container $container) {
            return new StoreToKernelStoreAdapter(Store::getInstance());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCurrentStore(Container $container): Container
    {
        $container->set(static::STORE_CURRENT, function (Container $container) {
            if (Store::isDynamicStoreMode()) {
                return $container->getApplicationService(static::SERVICE_STORE);
            }

            return $this->getStoreName();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDynamicStoreMode(Container $container): Container
    {
        $container->set(static::DYNAMIC_STORE_MODE, function () {
            return $this->isDynamicStoreModeEnabled();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCurrentStoreProvidedFlag(Container $container): Container
    {
        $container->set(static::CURRENT_STORE_PROVIDED_FLAG, function (Container $container) {
            return !$this->isDynamicStoreModeEnabled() || $container->hasApplicationService(static::SERVICE_STORE);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStorePreCreatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_STORE_PRE_CREATE_VALIDATION, function () {
            return $this->getStorePreCreateValidationPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStorePreUpdatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_STORE_PRE_UPDATE_VALIDATION, function () {
            return $this->getStorePreUpdateValidationPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\StoreExtension\Dependency\Plugin\StorePreCreateValidationPluginInterface>
     */
    protected function getStorePreCreateValidationPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\StoreExtension\Dependency\Plugin\StorePreUpdateValidationPluginInterface>
     */
    protected function getStorePreUpdateValidationPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStorePostCreatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_STORE_POST_CREATE, function () {
            return $this->getStorePostCreatePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStorePostUpdatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_STORE_POST_UPDATE, function () {
            return $this->getStorePostUpdatePlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\StoreExtension\Dependency\Plugin\StorePostCreatePluginInterface>
     */
    protected function getStorePostCreatePlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\StoreExtension\Dependency\Plugin\StorePostUpdatePluginInterface>
     */
    protected function getStorePostUpdatePlugins(): array
    {
        return [];
    }

    /**
     * @deprecated Will be removed after dynamic multi-store is always enabled.
     *
     * @return string
     */
    protected function getStoreName(): string
    {
        return Store::getInstance()->getStoreName();
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreCollectionExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_STORE_COLLECTION_EXPANDER, function () {
            return $this->getStoreCollectionExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\StoreExtension\Dependency\Plugin\StoreCollectionExpanderPluginInterface>
     */
    protected function getStoreCollectionExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @deprecated Will be removed after dynamic multi-store is always enabled.
     *
     * @return bool
     */
    protected function isDynamicStoreModeEnabled(): bool
    {
        return Store::isDynamicStoreMode();
    }
}
