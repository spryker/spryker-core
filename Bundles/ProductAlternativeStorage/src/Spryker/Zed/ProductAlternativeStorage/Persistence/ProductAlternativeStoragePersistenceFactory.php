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
use Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductReplacementForStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductAlternativeStorage\Persistence\Propel\Mapper\ProductAlternativeStorageMapper;
use Spryker\Zed\ProductAlternativeStorage\Persistence\Propel\Mapper\ProductAlternativeStorageMapperInterface;
use Spryker\Zed\ProductAlternativeStorage\Persistence\Propel\Mapper\ProductReplacementStorageMapper;
use Spryker\Zed\ProductAlternativeStorage\Persistence\Propel\Mapper\ProductReplacementStorageMapperInterface;
use Spryker\Zed\ProductAlternativeStorage\ProductAlternativeStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductAlternativeStorage\ProductAlternativeStorageConfig getConfig()
 * @method \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageRepositoryInterface getRepository()
 */
class ProductAlternativeStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductAlternativeStorageQuery
     */
    public function createProductAlternativeStoragePropelQuery(): SpyProductAlternativeStorageQuery
    {
        return SpyProductAlternativeStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductAlternativeStorage\Persistence\SpyProductReplacementForStorageQuery
     */
    public function createProductReplacementForStoragePropelQuery(): SpyProductReplacementForStorageQuery
    {
        return SpyProductReplacementForStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery
     */
    public function getProductAlternativePropelQuery(): SpyProductAlternativeQuery
    {
        return $this->getProvidedDependency(ProductAlternativeStorageDependencyProvider::PROPEL_QUERY_PRODUCT_ALTERNATIVE);
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function getProductPropelQuery(): SpyProductQuery
    {
        return $this->getProvidedDependency(ProductAlternativeStorageDependencyProvider::PROPEL_QUERY_PRODUCT);
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function getProductAbstractPropelQuery(): SpyProductAbstractQuery
    {
        return $this->getProvidedDependency(ProductAlternativeStorageDependencyProvider::PROPEL_QUERY_PRODUCT_ABSTRACT);
    }

    /**
     * @return \Spryker\Zed\ProductAlternativeStorage\Persistence\Propel\Mapper\ProductAlternativeStorageMapperInterface
     */
    public function createProductAlternativeStorageMapper(): ProductAlternativeStorageMapperInterface
    {
        return new ProductAlternativeStorageMapper();
    }

    /**
     * @return \Spryker\Zed\ProductAlternativeStorage\Persistence\Propel\Mapper\ProductReplacementStorageMapperInterface
     */
    public function createProductReplacementStorageMapper(): ProductReplacementStorageMapperInterface
    {
        return new ProductReplacementStorageMapper();
    }
}
