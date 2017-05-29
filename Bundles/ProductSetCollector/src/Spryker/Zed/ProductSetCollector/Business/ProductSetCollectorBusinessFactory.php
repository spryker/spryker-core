<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetCollector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductSetCollector\Business\Collector\Search\ProductSetCollector as ProductSetSearchCollector;
use Spryker\Zed\ProductSetCollector\Business\Collector\Storage\ProductSetCollector as ProductSetStorageCollector;
use Spryker\Zed\ProductSetCollector\Business\Map\ProductSetPageMapBuilder;
use Spryker\Zed\ProductSetCollector\Persistence\Storage\Propel\ProductSetCollectorQuery;
use Spryker\Zed\ProductSetCollector\ProductSetCollectorDependencyProvider;
use Spryker\Zed\Search\Business\SearchFacade;

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
            $this->getUtilDataReaderService()
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
            new ProductSetPageMapBuilder(),
            new SearchFacade()
        );

        $storageProductSetCollector->setTouchQueryContainer($this->getTouchQueryContainer());
        $storageProductSetCollector->setQueryBuilder($this->createProductSetCollectorQuery());

        return $storageProductSetCollector;
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
     * @return \Spryker\Zed\ProductSetCollector\Persistence\Storage\Propel\ProductSetCollectorQuery
     */
    protected function createProductSetCollectorQuery()
    {
        return new ProductSetCollectorQuery();
    }

    /**
     * @return \Spryker\Zed\Collector\Business\CollectorFacadeInterface
     */
    public function getCollectorFacade()
    {
        return $this->getProvidedDependency(ProductSetCollectorDependencyProvider::FACADE_COLLECTOR);
    }

}
