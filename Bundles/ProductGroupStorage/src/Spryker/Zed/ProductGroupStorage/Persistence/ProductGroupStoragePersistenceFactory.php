<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroupStorage\Persistence;

use Orm\Zed\ProductGroupStorage\Persistence\SpyProductAbstractGroupStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductGroupStorage\ProductGroupStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductGroupStorage\ProductGroupStorageConfig getConfig()
 * @method \Spryker\Zed\ProductGroupStorage\Persistence\ProductGroupStorageQueryContainerInterface getQueryContainer()
 */
class ProductGroupStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\ProductGroupStorage\Dependency\QueryContainer\ProductGroupStorageToProductGroupQueryContainerInterface
     */
    public function getProductAbstractGroupQuery()
    {
        return $this->getProvidedDependency(ProductGroupStorageDependencyProvider::QUERY_CONTAINER_PRODUCT_GROUP);
    }

    /**
     * @return \Spryker\Zed\ProductGroupStorage\Dependency\QueryContainer\ProductGroupStorageToProductQueryContainerInterface
     */
    public function getProductQueryContainer()
    {
        return $this->getProvidedDependency(ProductGroupStorageDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }

    /**
     * @return \Orm\Zed\ProductGroupStorage\Persistence\SpyProductAbstractGroupStorageQuery
     */
    public function createSpyProductAbstractGroupStorageQuery()
    {
        return SpyProductAbstractGroupStorageQuery::create();
    }
}
