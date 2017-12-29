<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReviewStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductReviewStorage\Storage\ProductAbstractReviewStorageReader;
use Spryker\Client\ProductReviewStorage\Storage\ProductAbstractReviewStorageReaderInterface;
use Spryker\Client\ProductReviewStorage\Storage\ProductReviewStorageKeyGenerator;
use Spryker\Client\ProductReviewStorage\Storage\ProductReviewStorageKeyGeneratorInterface;
use Spryker\Shared\Kernel\Store;

class ProductReviewStorageFactory extends AbstractFactory
{
    /**
     * @return ProductAbstractReviewStorageReaderInterface
     */
    public function createProductConcreteImageStorageReader()
    {
        return new ProductAbstractReviewStorageReader($this->getStorageClient(), $this->createProductReviewStorageKeyGenerator());
    }

    /**
     * @return ProductReviewStorageKeyGeneratorInterface
     */
    protected function createProductReviewStorageKeyGenerator()
    {
        return new ProductReviewStorageKeyGenerator($this->getSynchronizationService(), $this->getStore());
    }

    /**
     * @return \Spryker\Client\ProductReviewStorage\Dependency\Client\ProductReviewStorageToStorageInterface
     */
    protected function getStorageClient()
    {
        return $this->getProvidedDependency(ProductReviewStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductReviewStorage\Dependency\Service\ProductReviewStorageToSynchronizationServiceBridge
     */
    protected function getSynchronizationService()
    {
        return $this->getProvidedDependency(ProductReviewStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return Store
     */
    protected function getStore()
    {
        return $this->getProvidedDependency(ProductReviewStorageDependencyProvider::STORE);
    }
}
