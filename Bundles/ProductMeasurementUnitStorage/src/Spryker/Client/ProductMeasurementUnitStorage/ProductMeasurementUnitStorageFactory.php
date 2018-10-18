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
use Spryker\Client\ProductMeasurementUnitStorage\Storage\ProductMeasurementBaseUnitReader;
use Spryker\Client\ProductMeasurementUnitStorage\Storage\ProductMeasurementBaseUnitReaderInterface;
use Spryker\Client\ProductMeasurementUnitStorage\Storage\ProductMeasurementSalesUnitReader;
use Spryker\Client\ProductMeasurementUnitStorage\Storage\ProductMeasurementSalesUnitReaderInterface;
use Spryker\Client\ProductMeasurementUnitStorage\Storage\ProductMeasurementUnitReader;
use Spryker\Client\ProductMeasurementUnitStorage\Storage\ProductMeasurementUnitReaderInterface;
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
            $this->getStore(),
            $this->getSynchronizationService()
        );
    }

    /**
     * @return \Spryker\Client\ProductMeasurementUnitStorage\Storage\ProductMeasurementBaseUnitReaderInterface
     */
    public function createProductMeasurementBaseUnitReader(): ProductMeasurementBaseUnitReaderInterface
    {
        return new ProductMeasurementBaseUnitReader(
            $this->createProductConcreteMeasurementUnitStorageReader(),
            $this->createProductMeasurementUnitReader()
        );
    }

    /**
     * @return \Spryker\Client\ProductMeasurementUnitStorage\Storage\ProductMeasurementUnitReaderInterface
     */
    public function createProductMeasurementUnitReader(): ProductMeasurementUnitReaderInterface
    {
        return new ProductMeasurementUnitReader(
            $this->createProductMeasurementUnitStorageReader()
        );
    }

    /**
     * @return \Spryker\Client\ProductMeasurementUnitStorage\Storage\ProductMeasurementSalesUnitReaderInterface
     */
    public function createProductMeasurementSalesUnitReader(): ProductMeasurementSalesUnitReaderInterface
    {
        return new ProductMeasurementSalesUnitReader(
            $this->createProductConcreteMeasurementUnitStorageReader(),
            $this->createProductMeasurementUnitReader()
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

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(ProductMeasurementUnitStorageDependencyProvider::STORE);
    }
}
