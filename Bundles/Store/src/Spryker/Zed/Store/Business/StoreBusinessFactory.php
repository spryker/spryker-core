<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Store\Reader\StoreReader as SharedStoreReader;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Store\Business\Cache\StoreCache;
use Spryker\Zed\Store\Business\Cache\StoreCacheInterface;
use Spryker\Zed\Store\Business\Expander\CurrentStoreReferenceAccessTokenRequestExpander;
use Spryker\Zed\Store\Business\Expander\CurrentStoreReferenceAccessTokenRequestExpanderInterface;
use Spryker\Zed\Store\Business\Expander\CurrentStoreReferenceMessageAttributesExpander;
use Spryker\Zed\Store\Business\Expander\CurrentStoreReferenceMessageAttributesExpanderInterface;
use Spryker\Zed\Store\Business\Expander\DynamicStoreExpander;
use Spryker\Zed\Store\Business\Expander\StoreExpander;
use Spryker\Zed\Store\Business\Expander\StoreExpanderInterface;
use Spryker\Zed\Store\Business\Model\Configuration\StoreConfigurationProvider;
use Spryker\Zed\Store\Business\Model\StoreReader;
use Spryker\Zed\Store\Business\Model\StoreReaderInterface;
use Spryker\Zed\Store\Business\Model\StoreValidator;
use Spryker\Zed\Store\Business\Model\StoreValidatorInterface;
use Spryker\Zed\Store\Business\Reader\StoreReferenceReader;
use Spryker\Zed\Store\Business\Reader\StoreReferenceReaderInterface;
use Spryker\Zed\Store\Business\Validator\MessageValidator;
use Spryker\Zed\Store\Business\Validator\MessageValidatorInterface;
use Spryker\Zed\Store\Business\Validator\StoreValidator as StoreDataValidator;
use Spryker\Zed\Store\Business\Validator\StoreValidatorInterface as StoreDataValidatorInterface;
use Spryker\Zed\Store\Business\Writer\StoreWriter;
use Spryker\Zed\Store\Business\Writer\StoreWriterInterface;
use Spryker\Zed\Store\StoreDependencyProvider;

/**
 * @method \Spryker\Zed\Store\StoreConfig getConfig()
 * @method \Spryker\Zed\Store\Persistence\StoreEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Store\Persistence\StoreRepositoryInterface getRepository()
 * @method \Spryker\Zed\Store\Persistence\StoreQueryContainerInterface getQueryContainer()
 */
class StoreBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Store\Business\Model\StoreReaderInterface
     */
    public function createStoreReader(): StoreReaderInterface
    {
        return new StoreReader(
            $this->getRepository(),
            $this->createStoreCache(),
            $this->createStoreReferenceReader(),
            $this->createStoreExpander(),
            $this->getIsDynamicStoreModeEnabled(),
        );
    }

    /**
     * @return \Spryker\Zed\Store\Business\Writer\StoreWriterInterface
     */
    public function createStoreWriter(): StoreWriterInterface
    {
        return new StoreWriter(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createStoreDataValidator(),
            $this->getStorePostCreatePlugins(),
            $this->getStorePostUpdatePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\Store\Business\Validator\StoreValidatorInterface
     */
    public function createStoreDataValidator(): StoreDataValidatorInterface
    {
        return new StoreDataValidator(
            $this->getRepository(),
            $this->getStorePreCreateValidationPlugins(),
            $this->getStorePreUpdateValidationPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\Store\Business\Expander\StoreExpanderInterface
     */
    public function createStoreExpander(): StoreExpanderInterface
    {
        if ($this->getIsDynamicStoreModeEnabled()) {
            return $this->createDynamicStoreExpander();
        }

        return new StoreExpander(
            $this->createSharedStoreReader(),
        );
    }

    /**
     * @return \Spryker\Zed\Store\Business\Expander\StoreExpanderInterface
     */
    public function createDynamicStoreExpander(): StoreExpanderInterface
    {
        return new DynamicStoreExpander(
            $this->getStoreCollectionExpanderPlugins(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\Store\Business\Model\StoreValidatorInterface
     */
    public function createStoreValidator(): StoreValidatorInterface
    {
        return new StoreValidator($this->createStoreReader());
    }

    /**
     * @return \Spryker\Zed\Store\Business\Cache\StoreCacheInterface
     */
    public function createStoreCache(): StoreCacheInterface
    {
        return new StoreCache();
    }

    /**
     * @deprecated Will be removed after dynamic multi-store is always enabled.
     *
     * @return \Spryker\Shared\Store\Reader\StoreReaderInterface
     */
    protected function createSharedStoreReader()
    {
        return new SharedStoreReader($this->getSharedStore());
    }

    /**
     * @deprecated Will be removed after dynamic multi-store is always enabled.
     *
     * @return \Spryker\Shared\Store\Dependency\Adapter\StoreToStoreInterface
     */
    public function getSharedStore()
    {
        return $this->getProvidedDependency(StoreDependencyProvider::STORE);
    }

    /**
     * @param bool $fallbackToDefault
     *
     * @return string
     */
    public function getCurrentStore(bool $fallbackToDefault = false): string
    {
        if ($fallbackToDefault === true && !$this->getCurrentStoreDefinedFlag()) {
            /** @var \Generated\Shared\Transfer\StoreTransfer $storeTransfer */
            $storeTransfer = current($this->createStoreReader()->getAllStores());

            return $storeTransfer->getNameOrFail();
        }

        return $this->getProvidedDependency(StoreDependencyProvider::STORE_CURRENT);
    }

    /**
     * @param bool $fallbackToDefault
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStoreTransfer(bool $fallbackToDefault = false): StoreTransfer
    {
        return $this->createStoreReader()->getStoreByName(
            $this->getCurrentStore($fallbackToDefault),
        );
    }

    /**
     * @return bool
     */
    public function getIsDynamicStoreModeEnabled(): bool
    {
        return $this->getProvidedDependency(StoreDependencyProvider::DYNAMIC_STORE_MODE);
    }

    /**
     * @return array<\Spryker\Zed\StoreExtension\Dependency\Plugin\StorePreCreateValidationPluginInterface>
     */
    public function getStorePreCreateValidationPlugins(): array
    {
        return $this->getProvidedDependency(StoreDependencyProvider::PLUGINS_STORE_PRE_CREATE_VALIDATION);
    }

    /**
     * @return array<\Spryker\Zed\StoreExtension\Dependency\Plugin\StorePreUpdateValidationPluginInterface>
     */
    public function getStorePreUpdateValidationPlugins(): array
    {
        return $this->getProvidedDependency(StoreDependencyProvider::PLUGINS_STORE_PRE_UPDATE_VALIDATION);
    }

    /**
     * @return array<\Spryker\Zed\StoreExtension\Dependency\Plugin\StorePostCreatePluginInterface>
     */
    public function getStorePostCreatePlugins(): array
    {
        return $this->getProvidedDependency(StoreDependencyProvider::PLUGINS_STORE_POST_CREATE);
    }

    /**
     * @return array<\Spryker\Zed\StoreExtension\Dependency\Plugin\StorePostUpdatePluginInterface>
     */
    public function getStorePostUpdatePlugins(): array
    {
        return $this->getProvidedDependency(StoreDependencyProvider::PLUGINS_STORE_POST_UPDATE);
    }

    /**
     * @return array<\Spryker\Zed\StoreExtension\Dependency\Plugin\StoreCollectionExpanderPluginInterface>
     */
    public function getStoreCollectionExpanderPlugins(): array
    {
        return $this->getProvidedDependency(StoreDependencyProvider::PLUGINS_STORE_COLLECTION_EXPANDER);
    }

    /**
     * @return bool
     */
    public function getCurrentStoreDefinedFlag(): bool
    {
        return $this->getProvidedDependency(StoreDependencyProvider::CURRENT_STORE_PROVIDED_FLAG);
    }

    /**
     * @deprecated Unused method will be removed in next major.
     *
     * @return \Spryker\Zed\Store\Business\Model\Configuration\StoreConfigurationProviderInterface
     */
    protected function createStoreConfigurationProvider()
    {
        return new StoreConfigurationProvider($this->getStore());
    }

    /**
     * @deprecated Unused method will be removed in next major.
     *
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return Store::getInstance();
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Zed\Store\Business\Reader\StoreReferenceReaderInterface
     */
    public function createStoreReferenceReader(): StoreReferenceReaderInterface
    {
        return new StoreReferenceReader($this->getConfig());
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Zed\Store\Business\Expander\CurrentStoreReferenceAccessTokenRequestExpanderInterface
     */
    public function createStoreReferenceAccessTokenRequestExpander(): CurrentStoreReferenceAccessTokenRequestExpanderInterface
    {
        return new CurrentStoreReferenceAccessTokenRequestExpander(
            $this->createStoreReader(),
            $this->getCurrentStore(),
        );
    }

    /**
     * @return \Spryker\Zed\Store\Business\Validator\MessageValidatorInterface
     */
    public function createMessageTransferValidator(): MessageValidatorInterface
    {
        return new MessageValidator(
            $this->createStoreReader(),
            $this->getIsDynamicStoreModeEnabled(),
        );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Zed\Store\Business\Expander\CurrentStoreReferenceMessageAttributesExpanderInterface
     */
    public function createCurrentStoreReferenceMessageAttributesExpander(): CurrentStoreReferenceMessageAttributesExpanderInterface
    {
        return new CurrentStoreReferenceMessageAttributesExpander(
            $this->createStoreReader(),
            $this->getCurrentStore(),
        );
    }
}
