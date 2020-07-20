<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsStorage;

use Spryker\Client\CmsStorage\Dependency\Client\CmsStorageToStorageClientInterface;
use Spryker\Client\CmsStorage\Dependency\Service\CmsStorageToUtilEncodingServiceInterface;
use Spryker\Client\CmsStorage\Mapper\CmsPageStorageMapper;
use Spryker\Client\CmsStorage\Reader\CmsPageStorageReader;
use Spryker\Client\CmsStorage\Reader\CmsPageStorageReaderInterface;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Shared\Kernel\Store;

class CmsStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CmsStorage\Mapper\CmsPageStorageMapperInterface
     */
    public function createCmsPageStorageMapper()
    {
        return new CmsPageStorageMapper();
    }

    /**
     * @return \Spryker\Client\CmsStorage\Reader\CmsPageStorageReaderInterface
     */
    public function createCmsPageStorageReader(): CmsPageStorageReaderInterface
    {
        return new CmsPageStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Client\CmsStorage\Dependency\Service\CmsStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService()
    {
        return $this->getProvidedDependency(CmsStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore(): Store
    {
        return $this->getProvidedDependency(CmsStorageDependencyProvider::STORE);
    }

    /**
     * @return \Spryker\Client\CmsStorage\Dependency\Client\CmsStorageToStorageClientInterface
     */
    public function getStorageClient(): CmsStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(CmsStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\CmsStorage\Dependency\Service\CmsStorageToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): CmsStorageToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(CmsStorageDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
