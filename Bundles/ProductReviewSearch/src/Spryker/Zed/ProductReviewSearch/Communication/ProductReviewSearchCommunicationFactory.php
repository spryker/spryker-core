<?php

namespace Spryker\Zed\ProductReviewSearch\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductReviewSearch\Dependency\Facade\ProductReviewSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductReviewSearch\Dependency\Service\ProductReviewSearchToUtilSanitizeServiceInterface;
use Spryker\Zed\ProductReviewSearch\ProductReviewSearchDependencyProvider;

/**
 * @method \Spryker\Zed\ProductReviewSearch\Persistence\ProductReviewSearchQueryContainer getQueryContainer()
 * @method \Spryker\Zed\ProductReviewSearch\ProductReviewSearchConfig getConfig()
 */
class ProductReviewSearchCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return ProductReviewSearchToUtilSanitizeServiceInterface
     */
    public function getUtilSanitizeService()
    {
        return $this->getProvidedDependency(ProductReviewSearchDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return ProductReviewSearchToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(ProductReviewSearchDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(ProductReviewSearchDependencyProvider::STORE);
    }

    /**
     * @return \Spryker\Zed\ProductReviewSearch\Dependency\Service\ProductReviewSearchToUtilEncodingInterface
     */
    public function getUtilEncoding()
    {
        return $this->getProvidedDependency(ProductReviewSearchDependencyProvider::SERVICE_UTIL_ENCODING);
    }

}
