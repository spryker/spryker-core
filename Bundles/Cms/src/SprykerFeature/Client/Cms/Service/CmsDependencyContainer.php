<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Client\Cms\Service;

use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Client\Cms\CmsDependencyProvider;
use SprykerFeature\Client\Cms\Service\Storage\CmsBlockStorageInterface;
use SprykerFeature\Client\Storage\Service\StorageClientInterface;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;

class CmsDependencyContainer extends AbstractServiceDependencyContainer
{

    /**
     * @return CmsBlockStorageInterface
     */
    public function createCmsBlockFinder()
    {
        return $this->getFactory()->createStorageCmsBlockStorage(
            $this->getStorage(),
            $this->getKeyBuilder()
        );
    }

    /**
     * @return StorageClientInterface
     */
    private function getStorage()
    {
        return $this->getProvidedDependency(CmsDependencyProvider::KV_STORAGE);
    }

    /**
     * @return KeyBuilderInterface
     */
    private function getKeyBuilder()
    {
        return $this->getFactory()->createKeyBuilderCmsBlockKeyBuilder();
    }

}
