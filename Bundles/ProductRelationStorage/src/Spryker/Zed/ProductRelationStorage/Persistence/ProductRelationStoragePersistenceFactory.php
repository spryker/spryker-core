<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage\Persistence;

use Orm\Zed\ProductRelation\Persistence\SpyProductRelationProductAbstractQuery;
use Orm\Zed\ProductRelationStorage\Persistence\SpyProductAbstractRelationStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductRelationStorage\ProductRelationStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductRelationStorage\ProductRelationStorageConfig getConfig()
 * @method \Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStorageQueryContainerInterface getQueryContainer()
 */
class ProductRelationStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\ProductRelationStorage\Dependency\QueryContainer\ProductRelationStorageToProductRelationQueryContainerInterface
     */
    public function getProductRelationQuery()
    {
        return $this->getProvidedDependency(ProductRelationStorageDependencyProvider::QUERY_CONTAINER_PRODUCT_RELATION);
    }

    /**
     * @return \Spryker\Zed\ProductRelationStorage\Dependency\QueryContainer\ProductRelationStorageToProductQueryContainerInterface
     */
    public function getProductQueryContainer()
    {
        return $this->getProvidedDependency(ProductRelationStorageDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }

    /**
     * @return \Orm\Zed\ProductRelationStorage\Persistence\SpyProductAbstractRelationStorageQuery
     */
    public function createSpyProductAbstractRelationStorageQuery()
    {
        return SpyProductAbstractRelationStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationProductAbstractQuery
     */
    public function getProductRelationProductAbstractQuery(): SpyProductRelationProductAbstractQuery
    {
        return $this->getProvidedDependency(ProductRelationStorageDependencyProvider::PROPEL_QUERY_PRODUCT_RELATION_PRODUCT_ABSTRACT);
    }
}
