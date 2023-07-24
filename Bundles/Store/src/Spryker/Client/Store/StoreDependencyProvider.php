<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Store;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Store\Dependency\Client\StoreToZedRequestClientBridge;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Store\Dependency\Adapter\StoreToKernelStoreAdapter;

/**
 * @method \Spryker\Client\Store\StoreConfig getConfig()
 */
class StoreDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const SERVICE_STORE = 'store';

    /**
     * @var string
     */
    public const PLUGINS_STORE_EXPANDER = 'PLUGINS_STORE_EXPANDER';

    /**
     * @deprecated Exists for BC-reasons only.
     *
     * @var string
     */
    public const STORE = 'STORE';

    /**
     * @deprecated Exists for BC-reasons only.
     *
     * @var string
     */
    public const DYNAMIC_STORE_MODE = 'DYNAMIC_STORE_MODE';

    /**
     * @var string
     */
    public const CURRENT_STORE_PROVIDED_FLAG = 'CURRENT_STORE_PROVIDED_FLAG';

    /**
     * @var string
     */
    public const CLIENT_ZED_REQUEST = 'CLIENT_ZED_REQUEST';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container = $this->addStore($container);
        $container = $this->addStoreService($container);
        $container = $this->addStoreExpanderPlugins($container);
        $container = $this->addDynamicStoreMode($container);
        $container = $this->addCurrentStoreProvidedFlag($container);
        $container = $this->addZedRequestClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStoreService(Container $container): Container
    {
        $container->set(static::SERVICE_STORE, function (Container $container) {
            if ($this->isDynamicStoreModeEnabled()) {
                return $container->getApplicationService(static::SERVICE_STORE);
            }

            return $this->getStoreName();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStoreExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_STORE_EXPANDER, function () {
            return $this->getStoreExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Client\StoreExtension\Dependency\Plugin\StoreExpanderPluginInterface>
     */
    protected function getStoreExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @deprecated Exists for BC-reasons only.
     *
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStore(Container $container)
    {
        $container->set(static::STORE, function () {
            return new StoreToKernelStoreAdapter(Store::getInstance());
        });

        return $container;
    }

    /**
     * @deprecated Exists for BC-reasons only.
     *
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addDynamicStoreMode(Container $container): Container
    {
        $container->set(static::DYNAMIC_STORE_MODE, function () {
            return $this->isDynamicStoreModeEnabled();
        });

        return $container;
    }

    /**
     * @deprecated Exists for BC-reasons only.
     *
     * @return string
     */
    protected function getStoreName(): string
    {
        return Store::getInstance()->getStoreName();
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

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCurrentStoreProvidedFlag(Container $container): Container
    {
        $container->set(static::CURRENT_STORE_PROVIDED_FLAG, function (Container $container) {
            return !$this->isDynamicStoreModeEnabled() || $container->hasApplicationService(static::SERVICE_STORE);
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addZedRequestClient(Container $container): Container
    {
        $container->set(static::CLIENT_ZED_REQUEST, function (Container $container) {
            return new StoreToZedRequestClientBridge(
                $container->getLocator()->zedRequest()->client(),
            );
        });

        return $container;
    }
}
