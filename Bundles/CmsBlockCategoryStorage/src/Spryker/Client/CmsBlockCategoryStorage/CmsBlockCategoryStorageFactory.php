<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlockCategoryStorage;

use Spryker\Client\CmsBlockCategoryStorage\Dependency\Client\CmsBlockCategoryStorageToStorageClientInterface;
use Spryker\Client\CmsBlockCategoryStorage\Dependency\Service\CmsBlockCategoryStorageToSynchronizationServiceInterface;
use Spryker\Client\CmsBlockCategoryStorage\Storage\CmsBlockCategoryStorageReader;
use Spryker\Client\CmsBlockCategoryStorage\Storage\CmsBlockCategoryStorageReaderInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CmsBlockCategoryStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CmsBlockCategoryStorage\Storage\CmsBlockCategoryStorageReaderInterface
     */
    public function createCmsBlockCategoryStorageReader(): CmsBlockCategoryStorageReaderInterface
    {
        return new CmsBlockCategoryStorageReader($this->getStorageClient(), $this->getSynchronizationService());
    }

    /**
     * @return \Spryker\Client\CmsBlockCategoryStorage\Dependency\Client\CmsBlockCategoryStorageToStorageClientInterface
     */
    public function getStorageClient(): CmsBlockCategoryStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(CmsBlockCategoryStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\CmsBlockCategoryStorage\Dependency\Service\CmsBlockCategoryStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): CmsBlockCategoryStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(CmsBlockCategoryStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
