<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\MerchantProductOfferStorage\Dependency\Client\MerchantProductOfferStorageToMerchantStorageClientBridge;
use Spryker\Client\MerchantProductOfferStorage\Dependency\Client\MerchantProductOfferStorageToStorageClientBridge;
use Spryker\Client\MerchantProductOfferStorage\Dependency\Client\MerchantProductOfferStorageToStoreClientBridge;
use Spryker\Client\MerchantProductOfferStorage\Dependency\Service\MerchantProductOfferStorageToSynchronizationServiceBridge;
use Spryker\Client\MerchantProductOfferStorage\Dependency\Service\MerchantProductOfferStorageToUtilEncodingServiceBridge;
use Spryker\Client\MerchantProductOfferStorage\Exception\ProductOfferStorageCollectionSorterPluginException;
use Spryker\Client\MerchantProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageCollectionSorterPluginInterface;

class MerchantProductOfferStorageDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';
    public const CLIENT_STORE = 'CLIENT_STORE';
    public const CLIENT_MERCHANT_STORAGE = 'CLIENT_MERCHANT_STORAGE';
    public const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';
    public const PLUGINS_PRODUCT_OFFER_REFERENCE_STRATEGY = 'PLUGINS_PRODUCT_OFFER_REFERENCE_STRATEGY';
    public const PLUGINS_PRODUCT_OFFER_STORAGE_EXPANDER = 'PLUGINS_PRODUCT_OFFER_STORAGE_EXPANDER';
    public const PLUGIN_PRODUCT_OFFER_STORAGE_COLLECTION_SORTER = 'PLUGIN_PRODUCT_OFFER_STORAGE_COLLECTION_SORTER';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container = $this->addClientStorage($container);
        $container = $this->addClientStore($container);
        $container = $this->addServiceSynchronization($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addProductOfferReferenceStrategyPlugins($container);
        $container = $this->addProductOfferStorageExpanderPlugins($container);
        $container = $this->addProductOfferStorageCollectionSorterPlugin($container);
        $container = $this->addMerchantStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductOfferReferenceStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_OFFER_REFERENCE_STRATEGY, function () {
            return $this->getProductOfferReferenceStrategyPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Client\MerchantProductOfferStorageExtension\Dependency\Plugin\ProductOfferReferenceStrategyPluginInterface[]
     */
    protected function getProductOfferReferenceStrategyPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductOfferStorageExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_OFFER_STORAGE_EXPANDER, function () {
            return $this->getProductOfferStorageExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Client\MerchantProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageExpanderPluginInterface[]
     */
    protected function getProductOfferStorageExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductOfferStorageCollectionSorterPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_PRODUCT_OFFER_STORAGE_COLLECTION_SORTER, function () {
            return $this->createProductOfferStorageCollectionSorterPlugin();
        });

        return $container;
    }

    /**
     * @throws \Spryker\Client\MerchantProductOfferStorage\Exception\ProductOfferStorageCollectionSorterPluginException
     *
     * @return \Spryker\Client\MerchantProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageCollectionSorterPluginInterface
     */
    protected function createProductOfferStorageCollectionSorterPlugin(): ProductOfferStorageCollectionSorterPluginInterface
    {
        throw new ProductOfferStorageCollectionSorterPluginException(
            sprintf(
                'Missing instance of %s! You need to configure ProductOfferStorageCollectionSorterPlugin ' .
                'in your own MerchantProductOfferStorageDependencyProvider::createProductOfferStorageCollectionSorterPlugin() ' .
                'to be able to get default offer reference.',
                ProductOfferStorageCollectionSorterPluginInterface::class
            )
        );
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addClientStorage(Container $container): Container
    {
        $container->set(static::CLIENT_STORAGE, function (Container $container) {
            return new MerchantProductOfferStorageToStorageClientBridge(
                $container->getLocator()->storage()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addServiceSynchronization(Container $container): Container
    {
        $container->set(static::SERVICE_SYNCHRONIZATION, function (Container $container) {
            return new MerchantProductOfferStorageToSynchronizationServiceBridge(
                $container->getLocator()->synchronization()->service()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new MerchantProductOfferStorageToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addClientStore(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container) {
            return new MerchantProductOfferStorageToStoreClientBridge(
                $container->getLocator()->store()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addMerchantStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_MERCHANT_STORAGE, function (Container $container) {
            return new MerchantProductOfferStorageToMerchantStorageClientBridge(
                $container->getLocator()->merchantStorage()->client()
            );
        });

        return $container;
    }
}
