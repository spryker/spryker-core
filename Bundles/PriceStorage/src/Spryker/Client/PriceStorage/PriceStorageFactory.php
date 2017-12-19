<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceStorage;

use Spryker\Client\PriceStorage\Storage\PriceAbstractStorageReader;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\PriceStorage\Storage\PriceConcreteStorageReader;
use Spryker\Client\PriceStorage\Storage\PriceStorageKeyGenerator;
use Spryker\Client\PriceStorage\Storage\PriceStorageKeyGeneratorInterface;
use Spryker\Client\PriceStorage\Expander\ProductViewPriceExpander;
use Spryker\Client\PriceStorage\Expander\ProductViewPriceExpanderInterface;
use Spryker\Shared\Kernel\Store;

class PriceStorageFactory extends AbstractFactory
{
    /**
     * @return ProductViewPriceExpanderInterface
     */
    public function createProductViewPriceExpander()
    {
        return new ProductViewPriceExpander($this->createPriceAbstractStorageReader(), $this->createPriceConcreteStorageReader());
    }

    /**
     * @return \Spryker\Client\PriceStorage\Storage\PriceAbstractStorageReaderInterface
     */
    protected function createPriceAbstractStorageReader()
    {
        return new PriceAbstractStorageReader($this->getStorage(), $this->createPriceStorageKeyGenerator());
    }

    /**
     * @return \Spryker\Client\PriceStorage\Storage\PriceConcreteStorageReaderInterface
     */
    protected function createPriceConcreteStorageReader()
    {
        return new PriceConcreteStorageReader($this->getStorage(), $this->createPriceStorageKeyGenerator());
    }

    /**
     * @return \Spryker\Client\PriceStorage\Dependency\Client\PriceStorageToStorageInterface
     */
    protected function getStorage()
    {
        return $this->getProvidedDependency(PriceStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return PriceStorageKeyGeneratorInterface
     */
    protected function createPriceStorageKeyGenerator()
    {
        return new PriceStorageKeyGenerator($this->getSynchronizationService(), $this->getStore());
    }

    /**
     * @return \Spryker\Client\PriceStorage\Dependency\Service\PriceStorageToSynchronizationServiceBridge
     */
    protected function getSynchronizationService()
    {
        return $this->getProvidedDependency(PriceStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return Store
     */
    protected function getStore()
    {
        return $this->getProvidedDependency(PriceStorageDependencyProvider::STORE);
    }
}
