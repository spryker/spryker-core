<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlockStorage;

use Spryker\Client\CmsBlockStorage\Storage\CmsBlockStorage;
use Spryker\Client\Kernel\AbstractFactory;

class CmsBlockStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CmsBlockStorage\Storage\CmsBlockStorageInterface
     */
    public function createCmsBlockStorage()
    {
        return new CmsBlockStorage(
            $this->getStorage(),
            $this->getSynchronizationService(),
            $this->getCmsBlockStorageBlocksFinderPlugins()
        );
    }

    /**
     * @return \Spryker\Client\CmsBlockStorageExtension\Dependency\Plugin\CmsBlockStorageReaderPluginInterface[]
     */
    public function getCmsBlockStorageBlocksFinderPlugins(): array
    {
        return $this->getProvidedDependency(CmsBlockStorageDependencyProvider::PLUGINS_CMS_BLOCK_STORAGE_BLOCKS_FINDER);
    }

    /**
     * @return \Spryker\Client\CmsBlockStorage\Dependency\Client\CmsBlockStorageToStorageInterface
     */
    protected function getStorage()
    {
        return $this->getProvidedDependency(CmsBlockStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\CmsBlockStorage\Dependency\Service\CmsBlockStorageToSynchronizationServiceInterface
     */
    protected function getSynchronizationService()
    {
        return $this->getProvidedDependency(CmsBlockStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
