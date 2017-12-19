<?php

namespace Spryker\Zed\CmsBlockProductStorage\Communication;

use Spryker\Zed\CmsBlockProductStorage\CmsBlockProductStorageDependencyProvider;
use Spryker\Zed\CmsBlockProductStorage\Dependency\Facade\CmsBlockProductStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\CmsBlockProductStorage\Dependency\Service\CmsBlockProductStorageToUtilSanitizeServiceInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CmsBlockProductStorage\Persistence\CmsBlockProductStorageQueryContainer getQueryContainer()
 * @method \Spryker\Zed\CmsBlockProductStorage\CmsBlockProductStorageConfig getConfig()
 */
class CmsBlockProductStorageCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return CmsBlockProductStorageToUtilSanitizeServiceInterface
     */
    public function getUtilSanitizeService()
    {
        return $this->getProvidedDependency(CmsBlockProductStorageDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return CmsBlockProductStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(CmsBlockProductStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
