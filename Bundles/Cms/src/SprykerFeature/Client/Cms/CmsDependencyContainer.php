<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Client\Cms;

use SprykerFeature\Client\Cms\KeyBuilder\CmsBlockKeyBuilder;
use SprykerFeature\Client\Cms\Storage\CmsBlockStorage;
use SprykerEngine\Client\Kernel\AbstractDependencyContainer;
use SprykerFeature\Client\Cms\CmsDependencyProvider;
use SprykerFeature\Client\Cms\Storage\CmsBlockStorageInterface;
use SprykerFeature\Client\Storage\StorageClientInterface;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;

class CmsDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return CmsBlockStorageInterface
     */
    public function createCmsBlockFinder()
    {
        return new CmsBlockStorage(
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
        return new CmsBlockKeyBuilder();
    }

}
