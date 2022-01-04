<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOptionStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductOptionStorage\Dependency\Client\ProductOptionStorageToLocaleClientInterface;
use Spryker\Client\ProductOptionStorage\Dependency\Client\ProductOptionStorageToStoreClientInterface;
use Spryker\Client\ProductOptionStorage\Dependency\Service\ProductOptionStorageToUtilEncodingServiceInterface;
use Spryker\Client\ProductOptionStorage\Mapper\ProductOptionMapper;
use Spryker\Client\ProductOptionStorage\Mapper\ProductOptionMapperInterface;
use Spryker\Client\ProductOptionStorage\Price\ValuePriceReader;
use Spryker\Client\ProductOptionStorage\Storage\ProductOptionStorageReader;

class ProductOptionStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductOptionStorage\Storage\ProductOptionStorageReaderInterface
     */
    public function createProductOptionStorageReader()
    {
        return new ProductOptionStorageReader(
            $this->getStorage(),
            $this->getStoreClient(),
            $this->getSynchronizationService(),
            $this->createValuePriceReader(),
            $this->createProductOptionMapper(),
            $this->getUtilEncodingService(),
            $this->getLocaleClient(),
        );
    }

    /**
     * @return \Spryker\Client\ProductOptionStorage\Dependency\Client\ProductOptionStorageToStorageInterface
     */
    protected function getStorage()
    {
        return $this->getProvidedDependency(ProductOptionStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductOptionStorage\Dependency\Service\ProductOptionStorageToSynchronizationServiceBridge
     */
    protected function getSynchronizationService()
    {
        return $this->getProvidedDependency(ProductOptionStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\ProductOptionStorage\Dependency\Service\ProductOptionStorageToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ProductOptionStorageToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ProductOptionStorageDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Client\ProductOptionStorage\Price\ValuePriceReaderInterface
     */
    protected function createValuePriceReader()
    {
        return new ValuePriceReader(
            $this->getCurrencyClient(),
            $this->getPriceClient(),
        );
    }

    /**
     * @return \Spryker\Client\ProductOptionStorage\Mapper\ProductOptionMapperInterface
     */
    public function createProductOptionMapper(): ProductOptionMapperInterface
    {
        return new ProductOptionMapper();
    }

    /**
     * @return \Spryker\Client\ProductOptionStorage\Dependency\Client\ProductOptionStorageToPriceClientInterface
     */
    protected function getPriceClient()
    {
        return $this->getProvidedDependency(ProductOptionStorageDependencyProvider::CLIENT_PRICE);
    }

    /**
     * @return \Spryker\Client\ProductOptionStorage\Dependency\Client\ProductOptionStorageToCurrencyClientInterface
     */
    protected function getCurrencyClient()
    {
        return $this->getProvidedDependency(ProductOptionStorageDependencyProvider::CLIENT_CURRENCY);
    }

    /**
     * @return \Spryker\Client\ProductOptionStorage\Dependency\Client\ProductOptionStorageToStoreClientInterface
     */
    public function getStoreClient(): ProductOptionStorageToStoreClientInterface
    {
        return $this->getProvidedDependency(ProductOptionStorageDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Client\ProductOptionStorage\Dependency\Client\ProductOptionStorageToLocaleClientInterface
     */
    public function getLocaleClient(): ProductOptionStorageToLocaleClientInterface
    {
        return $this->getProvidedDependency(ProductOptionStorageDependencyProvider::CLIENT_LOCALE);
    }
}
