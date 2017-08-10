<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewCollector\Business;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductReviewCollector\Business\Collector\Search\ProductReviewCollector as ProductReviewSearchCollector;
use Spryker\Zed\ProductReviewCollector\Persistence\Search\Propel\ProductReviewCollectorQuery;
use Spryker\Zed\ProductReviewCollector\ProductReviewCollectorDependencyProvider;

/**
 * @method \Spryker\Zed\ProductReviewCollector\ProductReviewCollectorConfig getConfig()
 */
class ProductReviewCollectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ProductReviewCollector\Business\Collector\Search\ProductReviewCollector
     */
    public function createSearchProductReviewCollector()
    {
        $storageProductReviewCollector = new ProductReviewSearchCollector(
            $this->getUtilDataReaderService(),
            $this->getCurrentStore()
        );

        $storageProductReviewCollector->setTouchQueryContainer($this->getTouchQueryContainer());
        $storageProductReviewCollector->setQueryBuilder($this->createProductReviewCollectorQuery());

        return $storageProductReviewCollector;
    }

    /**
     * @return \Spryker\Zed\ProductReviewCollector\Persistence\Search\Propel\ProductReviewCollectorQuery
     */
    protected function createProductReviewCollectorQuery()
    {
        return new ProductReviewCollectorQuery();
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getCurrentStore()
    {
        return Store::getInstance(); // TODO: move to dependency provider
    }

    /**
     * @return \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface
     */
    protected function getUtilDataReaderService()
    {
        return $this->getProvidedDependency(ProductReviewCollectorDependencyProvider::SERVICE_DATA_READER);
    }

    /**
     * @return \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected function getTouchQueryContainer()
    {
        return $this->getProvidedDependency(ProductReviewCollectorDependencyProvider::QUERY_CONTAINER_TOUCH);
    }

    /**
     * @return \Spryker\Zed\Collector\Business\CollectorFacadeInterface
     */
    public function getCollectorFacade()
    {
        return $this->getProvidedDependency(ProductReviewCollectorDependencyProvider::FACADE_COLLECTOR);
    }

    /**
     * @return \Spryker\Zed\ProductReviewCollector\Dependency\Facade\ProductReviewCollectorToSearchInterface
     */
    protected function getSearchFacade()
    {
        return $this->getProvidedDependency(ProductReviewCollectorDependencyProvider::FACADE_SEARCH);
    }

    /**
     * @return \Spryker\Zed\ProductReviewCollector\Dependency\Facade\ProductReviewCollectorToProductReviewInterface
     */
    protected function getProductReviewFacade()
    {
        return $this->getProvidedDependency(ProductReviewCollectorDependencyProvider::FACADE_PRODUCT_REVIEW);
    }

}
