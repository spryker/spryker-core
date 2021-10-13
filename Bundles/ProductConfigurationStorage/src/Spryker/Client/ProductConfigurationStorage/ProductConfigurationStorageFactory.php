<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductConfigurationStorage\Builder\ProductConfigurationSessionKeyBuilder;
use Spryker\Client\ProductConfigurationStorage\Builder\ProductConfigurationSessionKeyBuilderInterface;
use Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToLocaleClientInterface;
use Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToProductStorageClientInterface;
use Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToSessionClientInterface;
use Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToStorageClientInterface;
use Spryker\Client\ProductConfigurationStorage\Dependency\Service\ProductConfigurationStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductConfigurationStorage\Expander\PriceProductFilterExpander;
use Spryker\Client\ProductConfigurationStorage\Expander\PriceProductFilterExpanderInterface;
use Spryker\Client\ProductConfigurationStorage\Expander\ProductViewExpander;
use Spryker\Client\ProductConfigurationStorage\Expander\ProductViewExpanderInterface;
use Spryker\Client\ProductConfigurationStorage\Mapper\ProductConfigurationInstanceMapper;
use Spryker\Client\ProductConfigurationStorage\Mapper\ProductConfigurationInstanceMapperInterface;
use Spryker\Client\ProductConfigurationStorage\Mapper\ProductConfigurationStorageMapper;
use Spryker\Client\ProductConfigurationStorage\Mapper\ProductConfigurationStorageMapperInterface;
use Spryker\Client\ProductConfigurationStorage\Reader\ProductConfigurationAvailabilityReader;
use Spryker\Client\ProductConfigurationStorage\Reader\ProductConfigurationAvailabilityReaderInterface;
use Spryker\Client\ProductConfigurationStorage\Reader\ProductConfigurationInstanceReader;
use Spryker\Client\ProductConfigurationStorage\Reader\ProductConfigurationInstanceReaderInterface;
use Spryker\Client\ProductConfigurationStorage\Reader\ProductConfigurationPriceReader;
use Spryker\Client\ProductConfigurationStorage\Reader\ProductConfigurationPriceReaderInterface;
use Spryker\Client\ProductConfigurationStorage\Storage\ProductConfigurationStorageReader;
use Spryker\Client\ProductConfigurationStorage\Storage\ProductConfigurationStorageReaderInterface;
use Spryker\Client\ProductConfigurationStorage\Writer\ProductConfigurationInstanceWriter;
use Spryker\Client\ProductConfigurationStorage\Writer\ProductConfigurationInstanceWriterInterface;

/**
 * @method \Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageConfig getConfig()
 */
class ProductConfigurationStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductConfigurationStorage\Expander\ProductViewExpanderInterface
     */
    public function createProductViewExpander(): ProductViewExpanderInterface
    {
        return new ProductViewExpander(
            $this->createProductConfigurationInstanceReader()
        );
    }

    /**
     * @return \Spryker\Client\ProductConfigurationStorage\Mapper\ProductConfigurationInstanceMapperInterface
     */
    public function createProductConfigurationInstanceMapper(): ProductConfigurationInstanceMapperInterface
    {
        return new ProductConfigurationInstanceMapper();
    }

    /**
     * @return \Spryker\Client\ProductConfigurationStorage\Reader\ProductConfigurationInstanceReaderInterface
     */
    public function createProductConfigurationInstanceReader(): ProductConfigurationInstanceReaderInterface
    {
        return new ProductConfigurationInstanceReader(
            $this->createProductConfigurationStorageReader(),
            $this->getSessionClient(),
            $this->createProductConfigurationInstanceMapper(),
            $this->createProductConfigurationSessionKeyBuilder()
        );
    }

    /**
     * @return \Spryker\Client\ProductConfigurationStorage\Writer\ProductConfigurationInstanceWriterInterface
     */
    public function createProductConfigurationInstanceWriter(): ProductConfigurationInstanceWriterInterface
    {
        return new ProductConfigurationInstanceWriter(
            $this->getSessionClient(),
            $this->createProductConfigurationSessionKeyBuilder()
        );
    }

    /**
     * @return \Spryker\Client\ProductConfigurationStorage\Storage\ProductConfigurationStorageReaderInterface
     */
    public function createProductConfigurationStorageReader(): ProductConfigurationStorageReaderInterface
    {
        return new ProductConfigurationStorageReader(
            $this->getSynchronizationService(),
            $this->getStorageClient(),
            $this->createProductConfigurationStorageMapper()
        );
    }

    /**
     * @return \Spryker\Client\ProductConfigurationStorage\Mapper\ProductConfigurationStorageMapperInterface
     */
    public function createProductConfigurationStorageMapper(): ProductConfigurationStorageMapperInterface
    {
        return new ProductConfigurationStorageMapper();
    }

    /**
     * @return \Spryker\Client\ProductConfigurationStorage\Expander\PriceProductFilterExpanderInterface
     */
    public function createPriceProductFilterExpander(): PriceProductFilterExpanderInterface
    {
        return new PriceProductFilterExpander();
    }

    /**
     * @return \Spryker\Client\ProductConfigurationStorage\Reader\ProductConfigurationAvailabilityReaderInterface
     */
    public function createProductConfigurationAvailabilityReader(): ProductConfigurationAvailabilityReaderInterface
    {
        return new ProductConfigurationAvailabilityReader(
            $this->createProductConfigurationInstanceReader()
        );
    }

    /**
     * @return \Spryker\Client\ProductConfigurationStorage\Reader\ProductConfigurationPriceReaderInterface
     */
    public function createProductConfigurationPriceReader(): ProductConfigurationPriceReaderInterface
    {
        return new ProductConfigurationPriceReader(
            $this->getLocaleClient(),
            $this->getProductStorageClient(),
            $this->createProductConfigurationInstanceReader()
        );
    }

    /**
     * @return \Spryker\Client\ProductConfigurationStorage\Builder\ProductConfigurationSessionKeyBuilderInterface
     */
    public function createProductConfigurationSessionKeyBuilder(): ProductConfigurationSessionKeyBuilderInterface
    {
        return new ProductConfigurationSessionKeyBuilder();
    }

    /**
     * @return \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToSessionClientInterface
     */
    public function getSessionClient(): ProductConfigurationStorageToSessionClientInterface
    {
        return $this->getProvidedDependency(ProductConfigurationStorageDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \Spryker\Client\ProductConfigurationStorage\Dependency\Service\ProductConfigurationStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): ProductConfigurationStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(ProductConfigurationStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToStorageClientInterface
     */
    public function getStorageClient(): ProductConfigurationStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(ProductConfigurationStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToProductStorageClientInterface
     */
    public function getProductStorageClient(): ProductConfigurationStorageToProductStorageClientInterface
    {
        return $this->getProvidedDependency(ProductConfigurationStorageDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToLocaleClientInterface
     */
    public function getLocaleClient(): ProductConfigurationStorageToLocaleClientInterface
    {
        return $this->getProvidedDependency(ProductConfigurationStorageDependencyProvider::CLIENT_LOCALE);
    }
}
