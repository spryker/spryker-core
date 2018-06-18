<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Persistence;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductAlternative\Persistence\Mapper\ProductAlternativeMapper;
use Spryker\Zed\ProductAlternative\Persistence\Mapper\ProductAlternativeMapperInterface;
use Spryker\Zed\ProductAlternative\ProductAlternativeDependencyProvider;

/**
 * @method \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductAlternative\ProductAlternativeConfig getConfig()
 */
class ProductAlternativePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery
     */
    public function createProductAlternativeQuery(): SpyProductAlternativeQuery
    {
        return SpyProductAlternativeQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductAlternative\Persistence\Mapper\ProductAlternativeMapperInterface
     */
    public function createProductAlternativeMapper(): ProductAlternativeMapperInterface
    {
        return new ProductAlternativeMapper();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function createProductQuery(): SpyProductQuery
    {
        return $this->getProvidedDependency(ProductAlternativeDependencyProvider::QUERY_PRODUCT);
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function createProductAbstractQuery(): SpyProductAbstractQuery
    {
        return $this->getProvidedDependency(ProductAlternativeDependencyProvider::QUERY_PRODUCT_ABSTRACT);
    }
}
