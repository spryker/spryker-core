<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterCollector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductCategoryFilterCollector\Business\Collector\ProductCategoryFilterCollectorRunner;
use Spryker\Zed\ProductCategoryFilterCollector\Business\Collector\ProductCategoryFilterCollectorRunnerInterface;
use Spryker\Zed\ProductCategoryFilterCollector\Business\Collector\Storage\ProductCategoryFilterCollector;
use Spryker\Zed\ProductCategoryFilterCollector\Persistence\Collector\Propel\ProductCategoryFilterCollectorQuery;
use Spryker\Zed\ProductCategoryFilterCollector\ProductCategoryFilterCollectorDependencyProvider;

/**
 * @method \Spryker\Zed\ProductCategoryFilterCollector\ProductCategoryFilterCollectorConfig getConfig()
 */
class ProductCategoryFilterCollectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Collector\Business\Collector\DatabaseCollectorInterface
     */
    public function createProductCategoryFilterCollector()
    {
        $collector = new ProductCategoryFilterCollector($this->getUtilDataReaderService());

        $collector->setTouchQueryContainer($this->getTouchQueryContainer());
        $collector->setQueryBuilder($this->createProductCategoryFilterCollectorQuery());

        return $collector;
    }

    /**
     * @return \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface
     */
    protected function getUtilDataReaderService()
    {
        return $this->getProvidedDependency(ProductCategoryFilterCollectorDependencyProvider::SERVICE_DATA_READER);
    }

    /**
     * @return \Spryker\Zed\ProductCategoryFilterCollector\Dependency\Facade\ProductCategoryFilterCollectorToCollectorFacadeInterface
     */
    public function getCollectorFacade()
    {
        return $this->getProvidedDependency(ProductCategoryFilterCollectorDependencyProvider::FACADE_COLLECTOR);
    }

    /**
     * @return \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected function getTouchQueryContainer()
    {
        return $this->getProvidedDependency(ProductCategoryFilterCollectorDependencyProvider::QUERY_CONTAINER_TOUCH);
    }

    /**
     * @return \Spryker\Zed\ProductCategoryFilterCollector\Persistence\Collector\Propel\ProductCategoryFilterCollectorQuery
     */
    protected function createProductCategoryFilterCollectorQuery()
    {
        return new ProductCategoryFilterCollectorQuery();
    }

    /**
     * @return \Spryker\Zed\ProductCategoryFilterCollector\Business\Collector\ProductCategoryFilterCollectorRunnerInterface
     */
    public function createProductCategoryFilterCollectorRunner(): ProductCategoryFilterCollectorRunnerInterface
    {
        return new ProductCategoryFilterCollectorRunner(
            $this->createProductCategoryFilterCollector(),
            $this->getCollectorFacade()
        );
    }
}
