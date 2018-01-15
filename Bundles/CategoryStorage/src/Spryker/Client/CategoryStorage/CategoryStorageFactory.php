<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryStorage;

use Spryker\Client\CategoryStorage\Storage\CategoryNodeStorage;
use Spryker\Client\CategoryStorage\Storage\CategoryTreeStorageReader;
use Spryker\Client\Kernel\AbstractFactory;

class CategoryStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CategoryStorage\Storage\CategoryTreeStorageReaderInterface
     */
    public function createCategoryTreeStorageReader()
    {
        return new CategoryTreeStorageReader(
            $this->getStorage(),
            $this->getSynchronizationService()
        );
    }

    /**
     * @return \Spryker\Client\CategoryStorage\Storage\CategoryNodeStorageInterface
     */
    public function createCategoryNodeStorage()
    {
        return new CategoryNodeStorage(
            $this->getStorage(),
            $this->getSynchronizationService()
        );
    }

    /**
     * @return \Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToStorageInterface
     */
    protected function getStorage()
    {
        return $this->getProvidedDependency(CategoryStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\CategoryStorage\Dependency\Service\CategoryStorageToSynchronizationServiceBridge
     */
    public function getSynchronizationService()
    {
        return $this->getProvidedDependency(CategoryStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
