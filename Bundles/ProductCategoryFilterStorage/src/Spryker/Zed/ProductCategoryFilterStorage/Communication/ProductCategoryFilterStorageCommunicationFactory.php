<?php

namespace Spryker\Zed\ProductCategoryFilterStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductCategoryFilterStorage\Dependency\Facade\ProductCategoryFilterStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductCategoryFilterStorage\Dependency\Service\ProductCategoryFilterStorageToUtilSanitizeServiceInterface;
use Spryker\Zed\ProductCategoryFilterStorage\ProductCategoryFilterStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductCategoryFilterStorage\Persistence\ProductCategoryFilterStorageQueryContainer getQueryContainer()
 * @method \Spryker\Zed\ProductCategoryFilterStorage\ProductCategoryFilterStorageConfig getConfig()
 */
class ProductCategoryFilterStorageCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return ProductCategoryFilterStorageToUtilSanitizeServiceInterface
     */
    public function getUtilSanitizeService()
    {
        return $this->getProvidedDependency(ProductCategoryFilterStorageDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return ProductCategoryFilterStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(ProductCategoryFilterStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(ProductCategoryFilterStorageDependencyProvider::STORE);
    }

}
