<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryImageStorage;

use Spryker\Client\CategoryImageStorage\Dependency\CategoryImageStorageToStorageClientInterface;
use Spryker\Client\CategoryImageStorage\Dependency\CategoryImageStorageToSynchronizationServiceInterface;
use Spryker\Client\CategoryImageStorage\Storage\CategoryImageStorageReader;
use Spryker\Client\CategoryImageStorage\Storage\CategoryImageStorageReaderInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CategoryImageStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CategoryImageStorage\Storage\CategoryImageStorageReaderInterface
     */
    public function createCategoryImageStorageReader(): CategoryImageStorageReaderInterface
    {
        return new CategoryImageStorageReader(
            $this->getSynchronizationService(),
            $this->getStorageClient()
        );
    }

    /**
     * @return \Spryker\Client\CategoryImageStorage\Dependency\CategoryImageStorageToSynchronizationServiceInterface
     */
    protected function getSynchronizationService(): CategoryImageStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(CategoryImageStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\CategoryImageStorage\Dependency\CategoryImageStorageToStorageClientInterface
     */
    protected function getStorageClient(): CategoryImageStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(CategoryImageStorageDependencyProvider::CLIENT_STORAGE);
    }
}
