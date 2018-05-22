<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductMeasurementUnitStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductMeasurementUnitStorage\Dependency\Client\ProductMeasurementUnitStorageToStorageClientInterface;
use Spryker\Client\ProductMeasurementUnitStorage\Dependency\Service\ProductMeasurementUnitStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductMeasurementUnitStorage\Storage\ProductConcreteMeasurementUnitStorageReader;
use Spryker\Client\ProductMeasurementUnitStorage\Storage\ProductConcreteMeasurementUnitStorageReaderInterface;
use Spryker\Client\ProductMeasurementUnitStorage\Storage\ProductMeasurementUnitStorageReader;
use Spryker\Client\ProductMeasurementUnitStorage\Storage\ProductMeasurementUnitStorageReaderInterface;

class ProductMeasurementUnitStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductMeasurementUnitStorage\Storage\ProductMeasurementUnitStorageReaderInterface
     */
    public function createProductMeasurementUnitStorageReader(): ProductMeasurementUnitStorageReaderInterface
    {
        return new ProductMeasurementUnitStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService()
        );
    }

    /**
     * @return \Spryker\Client\ProductMeasurementUnitStorage\Storage\ProductConcreteMeasurementUnitStorageReaderInterface
     */
    public function createProductConcreteMeasurementUnitStorageReader(): ProductConcreteMeasurementUnitStorageReaderInterface
    {
        return new ProductConcreteMeasurementUnitStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService()
        );
    }

    /**
     * @return \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Client\ProductMeasurementUnitStorageToStorageClientInterface
     */
    public function getStorageClient(): ProductMeasurementUnitStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(ProductMeasurementUnitStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Service\ProductMeasurementUnitStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): ProductMeasurementUnitStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(ProductMeasurementUnitStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
