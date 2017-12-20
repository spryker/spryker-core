<?php

namespace Spryker\Zed\ProductReviewStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductReviewStorage\Dependency\Facade\ProductReviewStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductReviewStorage\Dependency\Service\ProductReviewStorageToUtilSanitizeServiceInterface;
use Spryker\Zed\ProductReviewStorage\ProductReviewStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductReviewStorage\Persistence\ProductReviewStorageQueryContainer getQueryContainer()
 * @method \Spryker\Zed\ProductReviewStorage\ProductReviewStorageConfig getConfig()
 */
class ProductReviewStorageCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return ProductReviewStorageToUtilSanitizeServiceInterface
     */
    public function getUtilSanitizeService()
    {
        return $this->getProvidedDependency(ProductReviewStorageDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return ProductReviewStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(ProductReviewStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(ProductReviewStorageDependencyProvider::STORE);
    }

}
