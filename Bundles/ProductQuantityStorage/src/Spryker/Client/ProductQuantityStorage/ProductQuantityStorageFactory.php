<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductQuantityStorage\Dependency\Client\ProductQuantityStorageToStorageClientInterface;
use Spryker\Client\ProductQuantityStorage\Dependency\Service\ProductQuantityStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductQuantityStorage\Expander\ProductConcreteTransferExpander;
use Spryker\Client\ProductQuantityStorage\Expander\ProductConcreteTransferExpanderInterface;
use Spryker\Client\ProductQuantityStorage\Storage\ProductQuantityStorageReader;
use Spryker\Client\ProductQuantityStorage\Storage\ProductQuantityStorageReaderInterface;
use Spryker\Client\ProductQuantityStorage\Validator\ProductQuantityValidator;
use Spryker\Client\ProductQuantityStorage\Validator\ProductQuantityValidatorInterface;

class ProductQuantityStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductQuantityStorage\Storage\ProductQuantityStorageReaderInterface
     */
    public function createProductQuantityStorageReader(): ProductQuantityStorageReaderInterface
    {
        return new ProductQuantityStorageReader(
            $this->getStorage(),
            $this->getSynchronizationService()
        );
    }

    /**
     * @return \Spryker\Client\ProductQuantityStorage\Expander\ProductConcreteTransferExpanderInterface
     */
    public function createProductConcreteTransferExpander(): ProductConcreteTransferExpanderInterface
    {
        return new ProductConcreteTransferExpander(
            $this->createProductQuantityStorageReader()
        );
    }

    /**
     * @return \Spryker\Client\ProductQuantityStorage\Dependency\Client\ProductQuantityStorageToStorageClientInterface
     */
    public function getStorage(): ProductQuantityStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(ProductQuantityStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductQuantityStorage\Dependency\Service\ProductQuantityStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): ProductQuantityStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(ProductQuantityStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\ProductQuantityStorage\Validator\ProductQuantityValidatorInterface
     */
    public function createProductQuantityValidator(): ProductQuantityValidatorInterface
    {
        return new ProductQuantityValidator();
    }
}
