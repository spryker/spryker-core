<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewCollector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductReviewCollector\Business\Collector\ProductReviewCollectorRunner;
use Spryker\Zed\ProductReviewCollector\Business\Collector\ProductReviewCollectorRunnerInterface;
use Spryker\Zed\ProductReviewCollector\Business\Collector\Search\ProductReviewCollector as ProductReviewSearchCollector;
use Spryker\Zed\ProductReviewCollector\Business\Collector\Storage\ProductAbstractReviewCollector as ProductAbstractReviewStorageCollector;
use Spryker\Zed\ProductReviewCollector\Dependency\Facade\ProductReviewCollectorToSearchInterface;
use Spryker\Zed\ProductReviewCollector\Dependency\Facade\ProductReviewCollectorToStoreFacadeInterface;
use Spryker\Zed\ProductReviewCollector\Persistence\Search\Propel\ProductReviewSearchCollectorQuery;
use Spryker\Zed\ProductReviewCollector\Persistence\Storage\Propel\ProductAbstractReviewStorageCollectorQuery;
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
            $this->getStoreFacade(),
        );

        $storageProductReviewCollector->setTouchQueryContainer($this->getTouchQueryContainer());
        $storageProductReviewCollector->setQueryBuilder($this->createProductReviewSearchCollectorQuery());

        return $storageProductReviewCollector;
    }

    /**
     * @return \Spryker\Zed\ProductReviewCollector\Business\Collector\Storage\ProductAbstractReviewCollector
     */
    public function createStorageProductAbstractReviewCollector()
    {
        $storageProductReviewCollector = new ProductAbstractReviewStorageCollector(
            $this->getUtilDataReaderService(),
        );

        $storageProductReviewCollector->setTouchQueryContainer($this->getTouchQueryContainer());
        $storageProductReviewCollector->setQueryBuilder($this->createProductReviewStorageCollectorQuery());

        return $storageProductReviewCollector;
    }

    /**
     * @return \Spryker\Zed\ProductReviewCollector\Persistence\Search\Propel\ProductReviewSearchCollectorQuery
     */
    protected function createProductReviewSearchCollectorQuery()
    {
        return new ProductReviewSearchCollectorQuery();
    }

    /**
     * @return \Spryker\Zed\ProductReviewCollector\Persistence\Storage\Propel\ProductAbstractReviewStorageCollectorQuery
     */
    protected function createProductReviewStorageCollectorQuery()
    {
        return new ProductAbstractReviewStorageCollectorQuery();
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
     * @return \Spryker\Zed\ProductReviewCollector\Dependency\Facade\ProductReviewCollectorToCollectorInterface
     */
    public function getCollectorFacade()
    {
        return $this->getProvidedDependency(ProductReviewCollectorDependencyProvider::FACADE_COLLECTOR);
    }

    /**
     * @return \Spryker\Zed\ProductReviewCollector\Dependency\Facade\ProductReviewCollectorToSearchInterface
     */
    public function getSearchFacade(): ProductReviewCollectorToSearchInterface
    {
        return $this->getProvidedDependency(ProductReviewCollectorDependencyProvider::FACADE_SEARCH);
    }

    /**
     * @return \Spryker\Zed\ProductReviewCollector\Dependency\Facade\ProductReviewCollectorToStoreFacadeInterface
     */
    public function getStoreFacade(): ProductReviewCollectorToStoreFacadeInterface
    {
        return $this->getProvidedDependency(ProductReviewCollectorDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\ProductReviewCollector\Business\Collector\ProductReviewCollectorRunnerInterface
     */
    public function createSearchProductReviewCollectorRunner(): ProductReviewCollectorRunnerInterface
    {
        return new ProductReviewCollectorRunner(
            $this->createSearchProductReviewCollector(),
            $this->getCollectorFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductReviewCollector\Business\Collector\ProductReviewCollectorRunnerInterface
     */
    public function createStorageProductAbstractReviewCollectorRunner(): ProductReviewCollectorRunnerInterface
    {
        return new ProductReviewCollectorRunner(
            $this->createStorageProductAbstractReviewCollector(),
            $this->getCollectorFacade(),
        );
    }
}
