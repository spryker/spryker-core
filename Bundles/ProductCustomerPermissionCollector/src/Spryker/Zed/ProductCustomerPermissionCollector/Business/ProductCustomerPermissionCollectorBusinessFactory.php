<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCustomerPermissionCollector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductCustomerPermissionCollector\Business\Search\ProductCustomerPermissionSearchCollector;
use Spryker\Zed\ProductCustomerPermissionCollector\Business\Storage\ProductCustomerPermissionStorageCollector;
use Spryker\Zed\ProductCustomerPermissionCollector\Persistence\Search\Propel\ProductCustomerPermissionSearchCollectorQuery;
use Spryker\Zed\ProductCustomerPermissionCollector\ProductCustomerPermissionCollectorDependencyProvider;

/**
 * @method \Spryker\Zed\ProductCustomerPermissionCollector\ProductCustomerPermissionCollectorConfig getConfig()
 */
class ProductCustomerPermissionCollectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductCustomerPermissionCollector\Business\Search\ProductCustomerPermissionSearchCollector
     */
    public function createSearchProductCustomerPermissionCollector()
    {
        $searchCollector = new ProductCustomerPermissionSearchCollector(
            $this->getUtilDataReaderService()
        );
        $searchCollector->setTouchQueryContainer($this->getTouchQueryContainer());
        $searchCollector->setQueryBuilder($this->createProductCustomerPermissionSearchCollectorQuery());

        return $searchCollector;
    }

    /**
     * @return \Spryker\Zed\ProductRelationCollector\Business\Collector\Storage\ProductRelationCollector
     */
    public function createStorageProductCustomerPermissionCollector()
    {
        $storageCollector = new ProductCustomerPermissionStorageCollector(
            $this->getUtilDataReaderService()
        );

        $storageCollector->setTouchQueryContainer($this->getTouchQueryContainer());
        $storageCollector->setQueryBuilder($this->createProductCustomerPermissionSearchCollectorQuery());

        return $storageCollector;
    }

    /**
     * @return \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface
     */
    protected function getUtilDataReaderService()
    {
        return $this->getProvidedDependency(ProductCustomerPermissionCollectorDependencyProvider::SERVICE_DATA_READER);
    }

    /**
     * @return \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected function getTouchQueryContainer()
    {
        return $this->getProvidedDependency(ProductCustomerPermissionCollectorDependencyProvider::QUERY_CONTAINER_TOUCH);
    }

    /**
     * @return \Spryker\Zed\ProductCustomerPermissionCollector\Persistence\Search\Propel\ProductCustomerPermissionSearchCollectorQuery
     */
    protected function createProductCustomerPermissionSearchCollectorQuery()
    {
        return new ProductCustomerPermissionSearchCollectorQuery();
    }

    /**
     * @return \Spryker\Zed\Collector\Business\CollectorFacadeInterface
     */
    public function getCollectorFacade()
    {
        return $this->getProvidedDependency(ProductCustomerPermissionCollectorDependencyProvider::FACADE_COLLECTOR);
    }
}
