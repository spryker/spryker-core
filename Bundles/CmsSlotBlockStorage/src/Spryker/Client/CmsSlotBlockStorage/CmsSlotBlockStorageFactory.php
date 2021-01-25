<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockStorage;

use Spryker\Client\CmsSlotBlockStorage\Dependency\Client\CmsSlotBlockStorageToStorageClientInterface;
use Spryker\Client\CmsSlotBlockStorage\Dependency\Service\CmsSlotBlockStorageToSynchronizationServiceInterface;
use Spryker\Client\CmsSlotBlockStorage\Storage\CmsSlotBlockStorageReader;
use Spryker\Client\CmsSlotBlockStorage\Storage\CmsSlotBlockStorageReaderInterface;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Service\CmsSlotBlockStorage\CmsSlotBlockStorageServiceInterface;

class CmsSlotBlockStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CmsSlotBlockStorage\Storage\CmsSlotBlockStorageReaderInterface
     */
    public function createCmsSlotBlockStorageReader(): CmsSlotBlockStorageReaderInterface
    {
        return new CmsSlotBlockStorageReader(
            $this->getStorageClient(),
            $this->getCmsSlotBlockStorageService(),
            $this->getSynchronizationService()
        );
    }

    /**
     * @return \Spryker\Client\CmsSlotBlockStorage\Dependency\Client\CmsSlotBlockStorageToStorageClientInterface
     */
    public function getStorageClient(): CmsSlotBlockStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(CmsSlotBlockStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Service\CmsSlotBlockStorage\CmsSlotBlockStorageServiceInterface
     */
    public function getCmsSlotBlockStorageService(): CmsSlotBlockStorageServiceInterface
    {
        return $this->getProvidedDependency(CmsSlotBlockStorageDependencyProvider::SERVICE_CMS_SLOT_BLOCK_STORAGE);
    }

    /**
     * @return \Spryker\Client\CmsSlotBlockStorage\Dependency\Service\CmsSlotBlockStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): CmsSlotBlockStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(CmsSlotBlockStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
