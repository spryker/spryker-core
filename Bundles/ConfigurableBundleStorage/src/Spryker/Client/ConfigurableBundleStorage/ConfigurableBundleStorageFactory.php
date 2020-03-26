<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleStorage;

use Spryker\Client\ConfigurableBundleStorage\Dependency\Client\ConfigurableBundleStorageToProductStorageClientInterface;
use Spryker\Client\ConfigurableBundleStorage\Dependency\Client\ConfigurableBundleStorageToStorageClientInterface;
use Spryker\Client\ConfigurableBundleStorage\Dependency\Service\ConfigurableBundleStorageToSynchronizationServiceInterface;
use Spryker\Client\ConfigurableBundleStorage\Reader\ConfigurableBundleStorageReader;
use Spryker\Client\ConfigurableBundleStorage\Reader\ConfigurableBundleStorageReaderInterface;
use Spryker\Client\ConfigurableBundleStorage\Reader\ConfigurableBundleTemplateImageStorageReader;
use Spryker\Client\ConfigurableBundleStorage\Reader\ConfigurableBundleTemplateImageStorageReaderInterface;
use Spryker\Client\ConfigurableBundleStorage\Reader\ProductConcreteStorageReader;
use Spryker\Client\ConfigurableBundleStorage\Reader\ProductConcreteStorageReaderInterface;
use Spryker\Client\Kernel\AbstractFactory;

class ConfigurableBundleStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ConfigurableBundleStorage\Reader\ConfigurableBundleStorageReaderInterface
     */
    public function createConfigurableBundleStorageReader(): ConfigurableBundleStorageReaderInterface
    {
        return new ConfigurableBundleStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->createConfigurableBundleTemplateImageStorageReader()
        );
    }

    /**
     * @return \Spryker\Client\ConfigurableBundleStorage\Reader\ConfigurableBundleTemplateImageStorageReaderInterface
     */
    public function createConfigurableBundleTemplateImageStorageReader(): ConfigurableBundleTemplateImageStorageReaderInterface
    {
        return new ConfigurableBundleTemplateImageStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService()
        );
    }

    /**
     * @return \Spryker\Client\ConfigurableBundleStorage\Reader\ProductConcreteStorageReaderInterface
     */
    public function createProductConcreteStorageReader(): ProductConcreteStorageReaderInterface
    {
        return new ProductConcreteStorageReader($this->getProductStorageClient());
    }

    /**
     * @return \Spryker\Client\ConfigurableBundleStorage\Dependency\Client\ConfigurableBundleStorageToStorageClientInterface
     */
    public function getStorageClient(): ConfigurableBundleStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ConfigurableBundleStorage\Dependency\Client\ConfigurableBundleStorageToProductStorageClientInterface
     */
    public function getProductStorageClient(): ConfigurableBundleStorageToProductStorageClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleStorageDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ConfigurableBundleStorage\Dependency\Service\ConfigurableBundleStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): ConfigurableBundleStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
