<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductMeasurementUnitStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductMeasurementUnitStorage\Storage\ProductMeasurementUnitStorageReader;

class ProductMeasurementUnitStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductMeasurementUnitStorage\Storage\ProductMeasurementUnitStorageReaderInterface
     */
    public function createProductMeasurementUnitStorageReader()
    {
        return new ProductMeasurementUnitStorageReader(
            $this->getStorage(),
            $this->getSynchronizationService()
        );
    }

    /**
     * @return \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Client\ProductMeasurementUnitStorageToStorageInterface
     */
    protected function getStorage()
    {
        return $this->getProvidedDependency(ProductMeasurementUnitStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Service\ProductMeasurementUnitStorageToSynchronizationServiceBridge
     */
    protected function getSynchronizationService()
    {
        return $this->getProvidedDependency(ProductMeasurementUnitStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
