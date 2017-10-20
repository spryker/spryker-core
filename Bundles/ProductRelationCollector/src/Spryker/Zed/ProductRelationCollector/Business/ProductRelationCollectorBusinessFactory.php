<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationCollector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductRelationCollector\Business\Collector\Storage\ProductRelationCollector;
use Spryker\Zed\ProductRelationCollector\Persistence\Collector\Propel\ProductRelationCollectorQuery;
use Spryker\Zed\ProductRelationCollector\ProductRelationCollectorDependencyProvider;

/**
 * @method \Spryker\Zed\ProductRelationCollector\ProductRelationCollectorConfig getConfig()
 */
class ProductRelationCollectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductRelationCollector\Business\Collector\Storage\ProductRelationCollector
     */
    public function createStorageProductRelationCollector()
    {
        $productRelationCollector = new ProductRelationCollector(
            $this->getUtilDataReaderService(),
            $this->getProductImageQueryContainer(),
            $this->getPriceFacade(),
            $this->getProductRelationQueryContainer()
        );

        $productRelationCollector->setTouchQueryContainer(
            $this->getTouchQueryContainer()
        );
        $productRelationCollector->setQueryBuilder(
            $this->createStorageProductRelationCollectorPropelQuery()
        );

        return $productRelationCollector;
    }

    /**
     * @return \Spryker\Zed\ProductRelationCollector\Dependency\Facade\ProductRelationCollectorToPriceInterface
     */
    protected function getPriceFacade()
    {
        return $this->getProvidedDependency(ProductRelationCollectorDependencyProvider::FACADE_PRICE);
    }

    /**
     * @return \Spryker\Zed\ProductRelationCollector\Dependency\QueryContainer\ProductRelationCollectorToProductRelationInterface
     */
    protected function getProductRelationQueryContainer()
    {
        return $this->getProvidedDependency(ProductRelationCollectorDependencyProvider::QUERY_CONTAINER_PRODUCT_RELATION);
    }

    /**
     * @return \Spryker\Zed\ProductRelationCollector\Dependency\QueryContainer\ProductRelationCollectorToProductImageInterface
     */
    protected function getProductImageQueryContainer()
    {
        return $this->getProvidedDependency(ProductRelationCollectorDependencyProvider::QUERY_CONTAINER_PRODUCT_IMAGE);
    }

    /**
     * @return \Spryker\Zed\ProductRelationCollector\Persistence\Collector\Propel\ProductRelationCollectorQuery
     */
    public function createStorageProductRelationCollectorPropelQuery()
    {
        return new ProductRelationCollectorQuery();
    }

    /**
     * @return \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface
     */
    protected function getUtilDataReaderService()
    {
        return $this->getProvidedDependency(ProductRelationCollectorDependencyProvider::SERVICE_DATA_READER);
    }

    /**
     * @return \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected function getTouchQueryContainer()
    {
        return $this->getProvidedDependency(ProductRelationCollectorDependencyProvider::QUERY_CONTAINER_TOUCH);
    }

    /**
     * @return \Spryker\Zed\Collector\Business\CollectorFacadeInterface
     */
    public function getCollectorFacade()
    {
        return $this->getProvidedDependency(ProductRelationCollectorDependencyProvider::FACADE_COLLECTOR);
    }
}
