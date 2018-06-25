<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductAlternativeStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductAlternativeStorage\AlternativeProductApplicableCheck\AlternativeProductApplicableCheck;
use Spryker\Client\ProductAlternativeStorage\AlternativeProductApplicableCheck\AlternativeProductApplicableCheckInterface;
use Spryker\Client\ProductAlternativeStorage\Dependency\Client\ProductAlternativeStorageToStorageClientInterface;
use Spryker\Client\ProductAlternativeStorage\Dependency\Service\ProductAlternativeStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductAlternativeStorage\Storage\ProductAlternativeStorageReader;
use Spryker\Client\ProductAlternativeStorage\Storage\ProductAlternativeStorageReaderInterface;
use Spryker\Client\ProductAlternativeStorage\Storage\ProductReplacementStorageReader;
use Spryker\Client\ProductAlternativeStorage\Storage\ProductReplacementStorageReaderInterface;

class ProductAlternativeStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductAlternativeStorage\Storage\ProductAlternativeStorageReaderInterface
     */
    public function createProductAlternativeStorageReader(): ProductAlternativeStorageReaderInterface
    {
        return new ProductAlternativeStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService()
        );
    }

    /**
     * @return \Spryker\Client\ProductAlternativeStorage\Storage\ProductReplacementStorageReaderInterface
     */
    public function createProductReplacementStorageReader(): ProductReplacementStorageReaderInterface
    {
        return new ProductReplacementStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService()
        );
    }

    /**
     * @return \Spryker\Client\ProductAlternativeStorage\AlternativeProductApplicableCheck\AlternativeProductApplicableCheckInterface
     */
    public function createAlternativeProductApplicableCheck(): AlternativeProductApplicableCheckInterface
    {
        return new AlternativeProductApplicableCheck(
            $this->getAlternativeProductApplicableCheckPlugins()
        );
    }
    
    /**
     * @return \Spryker\Client\ProductAlternativeStorage\Dependency\Client\ProductAlternativeStorageToStorageClientInterface
     */
    public function getStorageClient(): ProductAlternativeStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(ProductAlternativeStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductAlternativeStorage\Dependency\Service\ProductAlternativeStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): ProductAlternativeStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(ProductAlternativeStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\ProductAlternativeStorageExtension\Dependency\Plugin\AlternativeProductApplicableCheckPluginInterface[]
     */
    protected function getAlternativeProductApplicableCheckPlugins(): array
    {
        return $this->getProvidedDependency(ProductAlternativeStorageDependencyProvider::PLUGINS_ALTERNATIVE_PRODUCT_APPLICABLE_CHECK);
    }
}
