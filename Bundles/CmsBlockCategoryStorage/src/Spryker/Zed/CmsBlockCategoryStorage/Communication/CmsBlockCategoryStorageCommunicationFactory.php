<?php

namespace Spryker\Zed\CmsBlockCategoryStorage\Communication;

use Spryker\Zed\CmsBlockCategoryStorage\CmsBlockCategoryStorageDependencyProvider;
use Spryker\Zed\CmsBlockCategoryStorage\Dependency\Facade\CmsBlockCategoryStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\CmsBlockCategoryStorage\Dependency\Service\CmsBlockCategoryStorageToUtilSanitizeServiceInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CmsBlockCategoryStorage\Persistence\CmsBlockCategoryStorageQueryContainer getQueryContainer()
 * @method \Spryker\Zed\CmsBlockCategoryStorage\CmsBlockCategoryStorageConfig getConfig()
 */
class CmsBlockCategoryStorageCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return CmsBlockCategoryStorageToUtilSanitizeServiceInterface
     */
    public function getUtilSanitizeService()
    {
        return $this->getProvidedDependency(CmsBlockCategoryStorageDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return CmsBlockCategoryStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(CmsBlockCategoryStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
