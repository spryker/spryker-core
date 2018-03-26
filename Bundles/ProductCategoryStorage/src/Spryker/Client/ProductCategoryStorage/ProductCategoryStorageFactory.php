<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategoryStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductCategoryStorage\Storage\ProductAbstractCategoryStorageReader;

class ProductCategoryStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductCategoryStorage\Storage\ProductAbstractCategoryStorageReaderInterface
     */
    public function createProductCategoryStorageReader()
    {
        return new ProductAbstractCategoryStorageReader($this->getStorage(), $this->getSynchronizationService());
    }

    /**
     * @return \Spryker\Client\ProductCategoryStorage\Dependency\Client\ProductCategoryStorageToStorageClientInterface
     */
    protected function getStorage()
    {
        return $this->getProvidedDependency(ProductCategoryStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductCategoryStorage\Dependency\Service\ProductCategoryStorageToSynchronizationServiceBridge
     */
    protected function getSynchronizationService()
    {
        return $this->getProvidedDependency(ProductCategoryStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
