<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Persistence;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery;
use Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductAlternativeStorageQuery;
use Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductReplacementStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductAlternativeStorage\Persistence\Mapper\ProductAlternativeStorageMapper;
use Spryker\Zed\ProductAlternativeStorage\Persistence\Mapper\ProductAlternativeStorageMapperInterface;
use Spryker\Zed\ProductAlternativeStorage\ProductAlternativeStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductAlternativeStorage\ProductAlternativeStorageConfig getConfig()
 */
class ProductAlternativeStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductAlternativeStorageQuery
     */
    public function createProductAlternativeStorageQuery(): SpyProductAlternativeStorageQuery
    {
        return SpyProductAlternativeStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductReplacementStorageQuery
     */
    public function createProductReplacementStorageQuery(): SpyProductReplacementStorageQuery
    {
        return SpyProductReplacementStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery
     */
    public function getProductAlternativeQuery(): SpyProductAlternativeQuery
    {
        return $this->getProvidedDependency(ProductAlternativeStorageDependencyProvider::PROPEL_QUERY_PRODUCT_ALTERNATIVE);
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function getProductQuery(): SpyProductQuery
    {
        return $this->getProvidedDependency(ProductAlternativeStorageDependencyProvider::PROPEL_QUERY_PRODUCT);
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function getProductAbstractQuery(): SpyProductAbstractQuery
    {
        return $this->getProvidedDependency(ProductAlternativeStorageDependencyProvider::PROPEL_QUERY_PRODUCT_ABSTRACT);
    }
}
