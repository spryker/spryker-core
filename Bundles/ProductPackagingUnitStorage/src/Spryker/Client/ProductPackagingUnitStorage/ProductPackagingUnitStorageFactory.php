<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPackagingUnitStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductPackagingUnitStorage\Dependency\Client\ProductPackagingUnitStorageToProductMeasurementUnitStorageClientInterface;
use Spryker\Client\ProductPackagingUnitStorage\Dependency\Client\ProductPackagingUnitStorageToStorageClientInterface;
use Spryker\Client\ProductPackagingUnitStorage\Dependency\Service\ProductPackagingUnitStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductPackagingUnitStorage\Expander\ItemTransferExpander;
use Spryker\Client\ProductPackagingUnitStorage\Expander\ItemTransferExpanderInterface;
use Spryker\Client\ProductPackagingUnitStorage\Storage\ProductPackagingUnitStorageKeyGenerator;
use Spryker\Client\ProductPackagingUnitStorage\Storage\ProductPackagingUnitStorageKeyGeneratorInterface;
use Spryker\Client\ProductPackagingUnitStorage\Storage\ProductPackagingUnitStorageReader;
use Spryker\Client\ProductPackagingUnitStorage\Storage\ProductPackagingUnitStorageReaderInterface;

class ProductPackagingUnitStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductPackagingUnitStorage\Storage\ProductPackagingUnitStorageReaderInterface
     */
    public function createProductPackagingUnitStorageReader(): ProductPackagingUnitStorageReaderInterface
    {
        return new ProductPackagingUnitStorageReader($this->getStorage(), $this->createProductPackagingUnitStorageKeyGenerator());
    }

    /**
     * @return \Spryker\Client\ProductPackagingUnitStorage\Expander\ItemTransferExpanderInterface
     */
    public function createItemTransferExpander(): ItemTransferExpanderInterface
    {
        return new ItemTransferExpander(
            $this->createProductPackagingUnitStorageReader(),
            $this->getProductMeasurementUnitStorageClient()
        );
    }

    /**
     * @return \Spryker\Client\ProductPackagingUnitStorage\Dependency\Client\ProductPackagingUnitStorageToStorageClientInterface
     */
    public function getStorage(): ProductPackagingUnitStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(ProductPackagingUnitStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductPackagingUnitStorage\Storage\ProductPackagingUnitStorageKeyGeneratorInterface
     */
    public function createProductPackagingUnitStorageKeyGenerator(): ProductPackagingUnitStorageKeyGeneratorInterface
    {
        return new ProductPackagingUnitStorageKeyGenerator($this->getSynchronizationService());
    }

    /**
     * @return \Spryker\Client\ProductPackagingUnitStorage\Dependency\Service\ProductPackagingUnitStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): ProductPackagingUnitStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(ProductPackagingUnitStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\ProductPackagingUnitStorage\Dependency\Client\ProductPackagingUnitStorageToProductMeasurementUnitStorageClientInterface
     */
    public function getProductMeasurementUnitStorageClient(): ProductPackagingUnitStorageToProductMeasurementUnitStorageClientInterface
    {
        return $this->getProvidedDependency(ProductPackagingUnitStorageDependencyProvider::CLIENT_PRODUCT_MEASUREMENT_UNIT_STORAGE);
    }
}
