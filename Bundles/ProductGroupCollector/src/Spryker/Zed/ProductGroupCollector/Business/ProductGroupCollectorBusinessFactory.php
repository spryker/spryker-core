<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroupCollector\Business;

use Spryker\Shared\ProductGroup\KeyBuilder\ProductGroupKeyBuilder;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductGroupCollector\Business\Collector\Storage\ProductGroupCollector;
use Spryker\Zed\ProductGroupCollector\ProductGroupCollectorDependencyProvider;
use Spryker\Zed\ProductGroupCollector\Persistence\Collector\Propel\ProductGroupCollectorQuery;

/**
 * @method \Spryker\Zed\ProductGroupCollector\ProductGroupCollectorConfig getConfig()
 */
class ProductGroupCollectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ProductGroupCollector\Business\Collector\Storage\ProductGroupCollector
     */
    public function createStorageProductGroupCollector()
    {
        $storageProductGroupCollector = new ProductGroupCollector(
            $this->getUtilDataReaderService()
        );

        $storageProductGroupCollector->setTouchQueryContainer($this->getTouchQueryContainer());
        $storageProductGroupCollector->setQueryBuilder($this->createProductGroupCollectorQuery());

        return $storageProductGroupCollector;
    }

    /**
     * @return \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected function createProductGroupKeyBuilder()
    {
        return new ProductGroupKeyBuilder();
    }

    /**
     * @return \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface
     */
    protected function getUtilDataReaderService()
    {
        return $this->getProvidedDependency(ProductGroupCollectorDependencyProvider::SERVICE_DATA_READER);
    }

    /**
     * @return \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected function getTouchQueryContainer()
    {
        return $this->getProvidedDependency(ProductGroupCollectorDependencyProvider::QUERY_CONTAINER_TOUCH);
    }

    /**
     * @return \Spryker\Zed\ProductGroupCollector\Persistence\Collector\Propel\ProductGroupCollectorQuery
     */
    protected function createProductGroupCollectorQuery()
    {
        return new ProductGroupCollectorQuery();
    }

    /**
     * @return \Spryker\Zed\Collector\Business\CollectorFacadeInterface
     */
    public function getCollectorFacade()
    {
        return $this->getProvidedDependency(ProductGroupCollectorDependencyProvider::FACADE_COLLECTOR);
    }

}
