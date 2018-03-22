<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetCollector\Business;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductSetCollector\Business\Collector\Search\ProductSetCollector as ProductSetSearchCollector;
use Spryker\Zed\ProductSetCollector\Business\Collector\Storage\ProductSetCollector as ProductSetStorageCollector;
use Spryker\Zed\ProductSetCollector\Business\Image\StorageProductImageReader;
use Spryker\Zed\ProductSetCollector\Business\Map\ProductSetPageMapBuilder;
use Spryker\Zed\ProductSetCollector\Persistence\Storage\Propel\ProductSetCollectorQuery;
use Spryker\Zed\ProductSetCollector\ProductSetCollectorDependencyProvider;

/**
 * @method \Spryker\Zed\ProductSetCollector\ProductSetCollectorConfig getConfig()
 */
class ProductSetCollectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductSetCollector\Business\Collector\Storage\ProductSetCollector
     */
    public function createStorageProductSetCollector()
    {
        $storageProductSetCollector = new ProductSetStorageCollector(
            $this->getUtilDataReaderService(),
            $this->createStorageProductImageReader()
        );

        $storageProductSetCollector->setTouchQueryContainer($this->getTouchQueryContainer());
        $storageProductSetCollector->setQueryBuilder($this->createProductSetCollectorQuery());

        return $storageProductSetCollector;
    }

    /**
     * @return \Spryker\Zed\ProductSetCollector\Business\Collector\Search\ProductSetCollector
     */
    public function createSearchProductSetCollector()
    {
        $storageProductSetCollector = new ProductSetSearchCollector(
            $this->getUtilDataReaderService(),
            $this->createProductSetPageMapBuilder(),
            $this->getSearchFacade()
        );

        $storageProductSetCollector->setTouchQueryContainer($this->getTouchQueryContainer());
        $storageProductSetCollector->setQueryBuilder($this->createProductSetCollectorQuery());

        return $storageProductSetCollector;
    }

    /**
     * @return \Spryker\Zed\ProductSetCollector\Business\Map\ProductSetPageMapBuilder
     */
    protected function createProductSetPageMapBuilder()
    {
        return new ProductSetPageMapBuilder($this->createStorageProductImageReader(), $this->getCurrentStore());
    }

    /**
     * @return \Spryker\Zed\ProductSetCollector\Persistence\Storage\Propel\ProductSetCollectorQuery
     */
    protected function createProductSetCollectorQuery()
    {
        return new ProductSetCollectorQuery();
    }

    /**
     * @return \Spryker\Zed\ProductSetCollector\Business\Image\StorageProductImageReader
     */
    protected function createStorageProductImageReader()
    {
        return new StorageProductImageReader($this->getProductSetFacade());
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getCurrentStore()
    {
        return Store::getInstance();
    }

    /**
     * @return \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface
     */
    protected function getUtilDataReaderService()
    {
        return $this->getProvidedDependency(ProductSetCollectorDependencyProvider::SERVICE_DATA_READER);
    }

    /**
     * @return \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected function getTouchQueryContainer()
    {
        return $this->getProvidedDependency(ProductSetCollectorDependencyProvider::QUERY_CONTAINER_TOUCH);
    }

    /**
     * @return \Spryker\Zed\Collector\Business\CollectorFacadeInterface
     */
    public function getCollectorFacade()
    {
        return $this->getProvidedDependency(ProductSetCollectorDependencyProvider::FACADE_COLLECTOR);
    }

    /**
     * @return \Spryker\Zed\ProductSetCollector\Dependency\Facade\ProductSetCollectorToSearchInterface
     */
    protected function getSearchFacade()
    {
        return $this->getProvidedDependency(ProductSetCollectorDependencyProvider::FACADE_SEARCH);
    }

    /**
     * @return \Spryker\Zed\ProductSetCollector\Dependency\Facade\ProductSetCollectorToProductSetInterface
     */
    protected function getProductSetFacade()
    {
        return $this->getProvidedDependency(ProductSetCollectorDependencyProvider::FACADE_PRODUCT_SET);
    }
}
