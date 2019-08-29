<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlockProductStorage;

use Spryker\Client\CmsBlockProductStorage\Dependency\Client\CmsBlockProductStorageToStorageClientInterface;
use Spryker\Client\CmsBlockProductStorage\Dependency\Service\CmsBlockProductStorageToSynchronizationServiceInterface;
use Spryker\Client\CmsBlockProductStorage\Storage\CmsBlockProductStorageReader;
use Spryker\Client\CmsBlockProductStorage\Storage\CmsBlockProductStorageReaderInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CmsBlockProductStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CmsBlockProductStorage\Storage\CmsBlockProductStorageReaderInterface
     */
    public function createCmsBlockProductStorageReader(): CmsBlockProductStorageReaderInterface
    {
        return new CmsBlockProductStorageReader($this->getStorageClient(), $this->getSynchronizationService());
    }

    /**
     * @return \Spryker\Client\CmsBlockProductStorage\Dependency\Client\CmsBlockProductStorageToStorageClientInterface
     */
    public function getStorageClient(): CmsBlockProductStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(CmsBlockProductStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\CmsBlockProductStorage\Dependency\Service\CmsBlockProductStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): CmsBlockProductStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(CmsBlockProductStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
