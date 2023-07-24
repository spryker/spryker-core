<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Store;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Store\Dependency\Client\StoreToZedRequestClientInterface;
use Spryker\Client\Store\Plugin\Expander\StoreExpanderInterface;
use Spryker\Client\Store\Plugin\Expander\StoreStoreReferenceExpander;
use Spryker\Client\Store\Reader\StoreReader as ClientStoreReader;
use Spryker\Client\Store\Zed\StoreStub;
use Spryker\Client\Store\Zed\StoreStubInterface;
use Spryker\Shared\Store\Reader\StoreReader;

/**
 * @method \Spryker\Client\Store\StoreConfig getConfig()
 */
class StoreFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\Store\Reader\StoreReaderInterface
     */
    public function createStoreReader()
    {
        if (!$this->getIsDynamicStoreModeEnabled()) {
            return $this->createSharedStoreReader();
        }

        return new ClientStoreReader($this->getStoreCollectionExpanderPlugins());
    }

    /**
     * @return string
     */
    public function getStoreService(): string
    {
        return $this->getProvidedDependency(StoreDependencyProvider::SERVICE_STORE);
    }

    /**
     * @return array<\Spryker\Client\StoreExtension\Dependency\Plugin\StoreExpanderPluginInterface>
     */
    public function getStoreCollectionExpanderPlugins(): array
    {
        return $this->getProvidedDependency(StoreDependencyProvider::PLUGINS_STORE_EXPANDER);
    }

    /**
     * @deprecated Will be removed after dynamic multi-store is always enabled.
     *
     * @return \Spryker\Shared\Store\Reader\StoreReaderInterface
     */
    public function createSharedStoreReader()
    {
        return new StoreReader(
            $this->getStore(),
            $this->createStoreExpanders(),
        );
    }

    /**
     * @deprecated Will be removed after dynamic multi-store is always enabled.
     *
     * @return \Spryker\Shared\Store\Dependency\Adapter\StoreToStoreInterface
     */
    public function getStore()
    {
        return $this->getProvidedDependency(StoreDependencyProvider::STORE);
    }

    /**
     * @deprecated Will be removed after dynamic multi-store is always enabled.
     *
     * @return bool
     */
    public function getIsDynamicStoreModeEnabled(): bool
    {
        return $this->getProvidedDependency(StoreDependencyProvider::DYNAMIC_STORE_MODE);
    }

    /**
     * @return bool
     */
    public function getCurrentStoreDefinedFlag(): bool
    {
        return $this->getProvidedDependency(StoreDependencyProvider::CURRENT_STORE_PROVIDED_FLAG);
    }

    /**
     * @return array<\Spryker\Client\Store\Plugin\Expander\StoreExpanderInterface>
     */
    protected function createStoreExpanders(): array
    {
        return [
            $this->createStoreReferenceExpander(),
        ];
    }

    /**
     * @return \Spryker\Client\Store\Plugin\Expander\StoreExpanderInterface
     */
    public function createStoreReferenceExpander(): StoreExpanderInterface
    {
        return new StoreStoreReferenceExpander($this->getConfig());
    }

    /**
     * @return \Spryker\Client\Store\Zed\StoreStubInterface
     */
    public function createStoreStub(): StoreStubInterface
    {
        return new StoreStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\Store\Dependency\Client\StoreToZedRequestClientInterface
     */
    public function getZedRequestClient(): StoreToZedRequestClientInterface
    {
        return $this->getProvidedDependency(StoreDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
