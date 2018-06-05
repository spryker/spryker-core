<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPackagingUnitStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductPackagingUnitStorage\Expander\ProductViewPriceExpander;
use Spryker\Client\ProductPackagingUnitStorage\Storage\PriceAbstractStorageReader;
use Spryker\Client\ProductPackagingUnitStorage\Storage\PriceConcreteStorageReader;
use Spryker\Client\ProductPackagingUnitStorage\Storage\PriceProductStorageKeyGenerator;

class PriceProductStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductPackagingUnitStorage\Expander\ProductViewPriceExpanderInterface
     */
    public function createProductViewPriceExpander()
    {
        return new ProductViewPriceExpander($this->createPriceAbstractStorageReader(), $this->createPriceConcreteStorageReader(), $this->getPriceProductClient());
    }

    /**
     * @return \Spryker\Client\ProductPackagingUnitStorage\Storage\PriceAbstractStorageReaderInterface
     */
    protected function createPriceAbstractStorageReader()
    {
        return new PriceAbstractStorageReader($this->getStorage(), $this->createPriceProductStorageKeyGenerator());
    }

    /**
     * @return \Spryker\Client\ProductPackagingUnitStorage\Storage\PriceConcreteStorageReaderInterface
     */
    protected function createPriceConcreteStorageReader()
    {
        return new PriceConcreteStorageReader($this->getStorage(), $this->createPriceProductStorageKeyGenerator());
    }

    /**
     * @return \Spryker\Client\ProductPackagingUnitStorage\Dependency\Client\PriceProductStorageToStorageInterface
     */
    protected function getStorage()
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductPackagingUnitStorage\Dependency\Client\PriceProductStorageToPriceProductInterface
     */
    protected function getPriceProductClient()
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::CLIENT_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Client\ProductPackagingUnitStorage\Dependency\Client\PriceProductStorageToStoreClientInterface
     */
    protected function getStoreClient()
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Client\ProductPackagingUnitStorage\Storage\PriceProductStorageKeyGeneratorInterface
     */
    protected function createPriceProductStorageKeyGenerator()
    {
        return new PriceProductStorageKeyGenerator($this->getSynchronizationService(), $this->getStoreClient());
    }

    /**
     * @return \Spryker\Client\ProductPackagingUnitStorage\Dependency\Service\PriceProductStorageToSynchronizationServiceBridge
     */
    protected function getSynchronizationService()
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
