<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelCollector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductLabelCollector\Business\Collector\Storage\LabelDictionaryCollector;
use Spryker\Zed\ProductLabelCollector\Business\Collector\Storage\ProductAbstractRelationCollector;
use Spryker\Zed\ProductLabelCollector\Persistence\Collector\Propel\LabelDictionaryCollectorQuery;
use Spryker\Zed\ProductLabelCollector\Persistence\Collector\Propel\ProductAbstractRelationCollectorQuery;
use Spryker\Zed\ProductLabelCollector\ProductLabelCollectorDependencyProvider;

class ProductLabelCollectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductLabelCollector\Dependency\Facade\ProductLabelCollectorToCollectorInterface
     */
    public function getCollectorFacade()
    {
        return $this->getProvidedDependency(ProductLabelCollectorDependencyProvider::FACADE_COLLECTOR);
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Collector\DatabaseCollectorInterface
     */
    public function createLabelDictionaryStorageCollector()
    {
        $collector = new LabelDictionaryCollector(
            $this->getDataReaderService(),
            $this->getProductLabelFacade()
        );

        $collector->setTouchQueryContainer($this->getTouchQueryContainer());
        $collector->setQueryBuilder($this->createLabelDictionaryCollectorQuery());

        return $collector;
    }

    /**
     * @return \Spryker\Zed\Collector\Business\Collector\DatabaseCollectorInterface
     */
    public function createAbstractProductRelationStorageCollector()
    {
        $collector = new ProductAbstractRelationCollector($this->getDataReaderService());

        $collector->setTouchQueryContainer($this->getTouchQueryContainer());
        $collector->setQueryBuilder($this->createAbstractProductRelationCollectorQuery());

        return $collector;
    }

    /**
     * @return \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface
     */
    protected function getDataReaderService()
    {
        return $this->getProvidedDependency(ProductLabelCollectorDependencyProvider::SERVICE_DATA_READER);
    }

    /**
     * @return \Spryker\Zed\ProductLabelCollector\Dependency\Facade\ProductLabelCollectorToProductLabelInterface
     */
    protected function getProductLabelFacade()
    {
        return $this->getProvidedDependency(ProductLabelCollectorDependencyProvider::FACADE_PRODUCT_LABEL);
    }

    /**
     * @return \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected function getTouchQueryContainer()
    {
        return $this->getProvidedDependency(ProductLabelCollectorDependencyProvider::QUERY_CONTAINER_TOUCH);
    }

    /**
     * @return \Spryker\Zed\Collector\Persistence\Collector\AbstractCollectorQuery
     */
    protected function createLabelDictionaryCollectorQuery()
    {
        return new LabelDictionaryCollectorQuery();
    }

    /**
     * @return \Spryker\Zed\Collector\Persistence\Collector\AbstractCollectorQuery
     */
    protected function createAbstractProductRelationCollectorQuery()
    {
        return new ProductAbstractRelationCollectorQuery();
    }
}
