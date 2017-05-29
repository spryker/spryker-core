<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetCollector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductSetCollector\Business\Collector\Storage\ProductSetCollector;
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
        $storageProductSetCollector = new ProductSetCollector(
            $this->getUtilDataReaderService()
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
