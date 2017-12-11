<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryStorage;

use Spryker\Client\CategoryStorage\Storage\CategoryTreeStorageReader;
use Spryker\Client\CategoryStorage\Storage\CategoryTreeStorageReaderInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CategoryStorageFactory extends AbstractFactory
{

    /**
     * @return CategoryTreeStorageReaderInterface
     */
    public function createCategoryTreeStorageReader()
    {
        return new CategoryTreeStorageReader(
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
