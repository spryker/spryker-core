<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCustomerPermissionCollector\Business;

use Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductCustomerPermissionCollector\Business\Search\ProductCustomerPermissionSearchCollector;
use Spryker\Zed\ProductCustomerPermissionCollector\Business\Storage\ProductCustomerPermissionStorageCollector;
use Spryker\Zed\ProductCustomerPermissionCollector\Dependency\Facade\ProductCustomerPermissionCollectorToCollectorFacadeInterface;
use Spryker\Zed\ProductCustomerPermissionCollector\Dependency\Facade\ProductCustomerPermissionCollectorToStoreFacadeInterface;
use Spryker\Zed\ProductCustomerPermissionCollector\Persistence\Search\Propel\ProductCustomerPermissionSearchCollectorQuery;
use Spryker\Zed\ProductCustomerPermissionCollector\ProductCustomerPermissionCollectorDependencyProvider;
use Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface;

/**
 * @method \Spryker\Zed\ProductCustomerPermissionCollector\ProductCustomerPermissionCollectorConfig getConfig()
 */
class ProductCustomerPermissionCollectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductCustomerPermissionCollector\Business\Search\ProductCustomerPermissionSearchCollector
     */
    public function createSearchProductCustomerPermissionCollector(): ProductCustomerPermissionSearchCollector
    {
        $searchCollector = new ProductCustomerPermissionSearchCollector(
            $this->getUtilDataReaderService(),
            $this->getStoreFacade()
        );
        $searchCollector->setTouchQueryContainer($this->getTouchQueryContainer());
        $searchCollector->setQueryBuilder($this->createProductCustomerPermissionSearchCollectorQuery());

        return $searchCollector;
    }

    /**
     * @return \Spryker\Zed\ProductCustomerPermissionCollector\Business\Storage\ProductCustomerPermissionStorageCollector
     */
    public function createStorageProductCustomerPermissionCollector(): ProductCustomerPermissionStorageCollector
    {
        $storageCollector = new ProductCustomerPermissionStorageCollector(
            $this->getUtilDataReaderService()
        );

        $storageCollector->setTouchQueryContainer($this->getTouchQueryContainer());
        $storageCollector->setQueryBuilder($this->createProductCustomerPermissionSearchCollectorQuery());

        return $storageCollector;
    }

    /**
     * @return \Spryker\Zed\ProductCustomerPermissionCollector\Dependency\Facade\ProductCustomerPermissionCollectorToStoreFacadeInterface
     */
    protected function getStoreFacade(): ProductCustomerPermissionCollectorToStoreFacadeInterface
    {
        return $this->getProvidedDependency(ProductCustomerPermissionCollectorDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface
     */
    protected function getUtilDataReaderService(): UtilDataReaderServiceInterface
    {
        return $this->getProvidedDependency(ProductCustomerPermissionCollectorDependencyProvider::SERVICE_DATA_READER);
    }

    /**
     * @return \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected function getTouchQueryContainer(): TouchQueryContainerInterface
    {
        return $this->getProvidedDependency(ProductCustomerPermissionCollectorDependencyProvider::QUERY_CONTAINER_TOUCH);
    }

    /**
     * @return \Spryker\Zed\ProductCustomerPermissionCollector\Persistence\Search\Propel\ProductCustomerPermissionSearchCollectorQuery
     */
    protected function createProductCustomerPermissionSearchCollectorQuery(): ProductCustomerPermissionSearchCollectorQuery
    {
        return new ProductCustomerPermissionSearchCollectorQuery();
    }

    /**
     * @return \Spryker\Zed\ProductCustomerPermissionCollector\Dependency\Facade\ProductCustomerPermissionCollectorToCollectorFacadeInterface
     */
    public function getCollectorFacade(): ProductCustomerPermissionCollectorToCollectorFacadeInterface
    {
        return $this->getProvidedDependency(ProductCustomerPermissionCollectorDependencyProvider::FACADE_COLLECTOR);
    }
}
