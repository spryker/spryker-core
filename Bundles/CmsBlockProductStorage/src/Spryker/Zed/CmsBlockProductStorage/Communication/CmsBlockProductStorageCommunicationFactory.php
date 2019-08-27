<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductStorage\Communication;

use Spryker\Zed\CmsBlockProductStorage\Business\Storage\CmsBlockProductStorageReader;
use Spryker\Zed\CmsBlockProductStorage\Business\Storage\CmsBlockProductStorageReaderInterface;
use Spryker\Zed\CmsBlockProductStorage\CmsBlockProductStorageDependencyProvider;
use Spryker\Zed\CmsBlockProductStorage\Dependency\Client\CmsBlockProductStorageToStorageClientInterface;
use Spryker\Zed\CmsBlockProductStorage\Dependency\Service\CmsBlockProductStorageToSynchronizationServiceInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CmsBlockProductStorage\Persistence\CmsBlockProductStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsBlockProductStorage\CmsBlockProductStorageConfig getConfig()
 * @method \Spryker\Zed\CmsBlockProductStorage\Business\CmsBlockProductStorageFacadeInterface getFacade()
 */
class CmsBlockProductStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CmsBlockProductStorage\Business\Storage\CmsBlockProductStorageReaderInterface
     */
    public function createCmsBlockProductStorageReader(): CmsBlockProductStorageReaderInterface
    {
        return new CmsBlockProductStorageReader($this->getStorageClient(), $this->getSynchronizationService());
    }

    /**
     * @return \Spryker\Zed\CmsBlockProductStorage\Dependency\Client\CmsBlockProductStorageToStorageClientInterface
     */
    public function getStorageClient(): CmsBlockProductStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(CmsBlockProductStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Zed\CmsBlockProductStorage\Dependency\Service\CmsBlockProductStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): CmsBlockProductStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(CmsBlockProductStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Zed\CmsBlockProductStorage\Dependency\Facade\CmsBlockProductStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(CmsBlockProductStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
