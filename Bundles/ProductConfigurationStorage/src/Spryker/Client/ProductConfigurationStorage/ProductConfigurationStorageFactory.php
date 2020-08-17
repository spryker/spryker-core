<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToSessionClientInterface;
use Spryker\Client\ProductConfigurationStorage\Dependency\ProductConfigurationStorageToStorageClientInterface;
use Spryker\Client\ProductConfigurationStorage\Dependency\ProductConfigurationStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductConfigurationStorage\Expander\ProductViewExpander;
use Spryker\Client\ProductConfigurationStorage\Expander\ProductViewExpanderInterface;
use Spryker\Client\ProductConfigurationStorage\Mapper\ProductConfigurationStorageMapper;
use Spryker\Client\ProductConfigurationStorage\Mapper\ProductConfigurationStorageMapperInterface;
use Spryker\Client\ProductConfigurationStorage\Reader\ProductConfigurationInstanceReader;
use Spryker\Client\ProductConfigurationStorage\Reader\ProductConfigurationInstanceReaderInterface;
use Spryker\Client\ProductConfigurationStorage\Storage\ProductConfigurationStorageReader;
use Spryker\Client\ProductConfigurationStorage\Storage\ProductConfigurationStorageReaderInterface;

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
     * @return \Spryker\Client\ProductConfigurationStorage\Mapper\ProductConfigurationStorageMapperInterface
     */
    public function createProductConfigurationStorageMapper(): ProductConfigurationStorageMapperInterface
    {
        return new ProductConfigurationStorageMapper();
    }

    /**
     * @return \Spryker\Client\ProductConfigurationStorage\Reader\ProductConfigurationInstanceReaderInterface
     */
    public function createProductConfigurationInstanceReader(): ProductConfigurationInstanceReaderInterface
    {
        return new ProductConfigurationInstanceReader(
            $this->createProductConfigurationStorageReader(),
            $this->getSessionClient(),
            $this->createProductConfigurationStorageMapper()
        );
    }

    /**
     * @return \Spryker\Client\ProductConfigurationStorage\Storage\ProductConfigurationStorageReaderInterface
     */
    public function createProductConfigurationStorageReader(): ProductConfigurationStorageReaderInterface
    {
        return new ProductConfigurationStorageReader(
            $this->getSynchronizationService(),
            $this->getStorageClient()
        );
    }

    /**
     * @return \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToSessionClientInterface
     */
    public function getSessionClient(): ProductConfigurationStorageToSessionClientInterface
    {
        return $this->getProvidedDependency(ProductConfigurationStorageDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \Spryker\Client\ProductConfigurationStorage\Dependency\ProductConfigurationStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): ProductConfigurationStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(ProductConfigurationStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\ProductConfigurationStorage\Dependency\ProductConfigurationStorageToStorageClientInterface
     */
    public function getStorageClient(): ProductConfigurationStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(ProductConfigurationStorageDependencyProvider::CLIENT_STORAGE);
    }
}
