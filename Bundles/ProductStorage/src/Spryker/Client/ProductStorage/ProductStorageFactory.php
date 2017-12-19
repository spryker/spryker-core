<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductStorage\Mapper\ProductVariantExpander;
use Spryker\Client\ProductStorage\Mapper\ProductStorageDataMapper;
use Spryker\Client\ProductStorage\Storage\ProductConcreteStorageReader;
use Spryker\Client\ProductStorage\Storage\ProductStorage;

class ProductStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductStorage\Storage\ProductStorageInterface
     */
    public function createProductStorage()
    {
        return new ProductStorage(
            $this->getStorageClient(),
            $this->getSynchronizationService()
        );
    }

    /**
     * @return \Spryker\Client\ProductStorage\Dependency\Client\ProductStorageToStorageClientInterface
     */
    protected function getStorageClient()
    {
        return $this->getProvidedDependency(ProductStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductStorage\Dependency\Service\ProductStorageToSynchronizationServiceBridge
     */
    public function getSynchronizationService()
    {
        return $this->getProvidedDependency(ProductStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\ProductStorage\Dependency\Client\ProductStorageToLocaleInterface
     */
    public function getLocaleClient()
    {
        return $this->getProvidedDependency(ProductStorageDependencyProvider::CLIENT_LOCALE);
    }

    /**
     * @return \Spryker\Client\ProductStorage\Storage\ProductConcreteStorageReaderInterface
     */
    public function createProductConcreteStorage()
    {
        return new ProductConcreteStorageReader(
            $this->getSynchronizationService(),
            $this->getStorageClient(),
            $this->getStore()
        );
    }

    /**
     * @return \Spryker\Client\ProductStorage\Dependency\Service\ProductToUtilEncodingInterface
     */
    protected function getUtilEncodingService()
    {
        return $this->getProvidedDependency(ProductStorageDependencyProvider::SERVICE_ENCODING);
    }

    /**
     * @return \Spryker\Client\ProductStorage\Mapper\ProductStorageDataMapperInterface
     */
    public function createProductStorageDataMapper()
    {
        return new ProductStorageDataMapper($this->getStorageProductExpanderPlugins());
    }

    /**
     * @return \Spryker\Client\ProductStorage\Mapper\ProductVariantExpanderInterface
     */
    public function createVariantExpander()
    {
        return new ProductVariantExpander($this->createProductConcreteStorage());
    }

    /**
     * @return \Spryker\Client\ProductStorage\Dependency\Plugin\ProductViewExpanderPluginInterface[]
     */
    protected function getStorageProductExpanderPlugins()
    {
        return $this->getProvidedDependency(ProductStorageDependencyProvider::PLUGIN_STORAGE_PRODUCT_EXPANDERS);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(ProductStorageDependencyProvider::STORE);
    }
}
