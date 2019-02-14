<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductQuantityStorage\Dependency\Client\ProductQuantityStorageToStorageClientInterface;
use Spryker\Client\ProductQuantityStorage\Dependency\Service\ProductQuantityStorageToProductQuantityServiceInterface;
use Spryker\Client\ProductQuantityStorage\Dependency\Service\ProductQuantityStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductQuantityStorage\Resolver\ProductQuantityResolver;
use Spryker\Client\ProductQuantityStorage\Resolver\ProductQuantityResolverInterface;
use Spryker\Client\ProductQuantityStorage\Storage\ProductQuantityStorageReader;
use Spryker\Client\ProductQuantityStorage\Storage\ProductQuantityStorageReaderInterface;
use Spryker\Client\ProductQuantityStorage\Validator\ProductQuantityItemValidator;
use Spryker\Client\ProductQuantityStorage\Validator\ProductQuantityItemValidatorInterface;

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
     * @return \Spryker\Client\ProductQuantityStorage\Validator\ProductQuantityItemValidatorInterface
     */
    public function createProductQuantityItemTransferValidator(): ProductQuantityItemValidatorInterface
    {
        return new ProductQuantityItemValidator(
            $this->createProductQuantityStorageReader(),
            $this->createProductQuantityResolver()
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
     * @return \Spryker\Client\ProductQuantityStorage\Resolver\ProductQuantityResolverInterface
     */
    public function createProductQuantityResolver(): ProductQuantityResolverInterface
    {
        return new ProductQuantityResolver(
            $this->createProductQuantityStorageReader(),
            $this->getProductQuantityService()
        );
    }

    /**
     * @return \Spryker\Client\ProductQuantityStorage\Dependency\Service\ProductQuantityStorageToProductQuantityServiceInterface
     */
    public function getProductQuantityService(): ProductQuantityStorageToProductQuantityServiceInterface
    {
        return $this->getProvidedDependency(ProductQuantityStorageDependencyProvider::SERVICE_PRODUCT_QUANTITY);
    }
}
