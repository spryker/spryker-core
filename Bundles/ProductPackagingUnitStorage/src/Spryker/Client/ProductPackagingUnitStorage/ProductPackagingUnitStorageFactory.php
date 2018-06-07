<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPackagingUnitStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductPackagingUnitStorage\Storage\ProductPackagingUnitStorageKeyGenerator;
use Spryker\Client\ProductPackagingUnitStorage\Storage\ProductPackagingUnitStorageReader;

class ProductPackagingUnitStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductPackagingUnitStorage\Storage\ProductPackagingUnitStorageReaderInterface
     */
    public function createPriceAbstractStorageReader()
    {
        return new ProductPackagingUnitStorageReader($this->getStorage(), $this->createPriceProductStorageKeyGenerator());
    }

    /**
     * @return \Spryker\Client\ProductPackagingUnitStorage\Dependency\Client\ProductPackagingUnitStorageToStorageInterface
     */
    protected function getStorage()
    {
        return $this->getProvidedDependency(ProductPackagingUnitStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductPackagingUnitStorage\Storage\ProductPackagingUnitStorageKeyGeneratorInterface
     */
    protected function createPriceProductStorageKeyGenerator()
    {
        return new ProductPackagingUnitStorageKeyGenerator($this->getSynchronizationService());
    }

    /**
     * @return \Spryker\Client\ProductPackagingUnitStorage\Dependency\Service\ProductPackagingUnitStorageToSynchronizationServiceBridge
     */
    protected function getSynchronizationService()
    {
        return $this->getProvidedDependency(ProductPackagingUnitStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
