<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductResourceAliasStorage\Persistence;

use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorageQuery;
use Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductConcreteStorageQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\PriceProductResourceAliasStorage\PriceProductResourceAliasStorageDependencyProvider;

class PriceProductResourceAliasStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorageQuery
     */
    public function getPriceProductAbstractPropelQuery(): SpyPriceProductAbstractStorageQuery
    {
        return $this->getProvidedDependency(PriceProductResourceAliasStorageDependencyProvider::PROPEL_QUERY_PRICE_PRODUCT_ABSTRACT_STORAGE);
    }

    /**
     * @return \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductConcreteStorageQuery
     */
    public function getPriceProductConcretePropelQuery(): SpyPriceProductConcreteStorageQuery
    {
        return $this->getProvidedDependency(PriceProductResourceAliasStorageDependencyProvider::PROPEL_QUERY_PRICE_PRODUCT_CONCRETE_STORAGE);
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function getProductPropelQuery(): SpyProductQuery
    {
        return $this->getProvidedDependency(PriceProductResourceAliasStorageDependencyProvider::PROPEL_QUERY_PRODUCT);
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function getProductAbstractPropelQuery(): SpyProductAbstractQuery
    {
        return $this->getProvidedDependency(PriceProductResourceAliasStorageDependencyProvider::PROPEL_QUERY_PRODUCT_ABSTRACT);
    }

    /**
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery
     */
    public function getPriceProductStorePropelQuery(): SpyPriceProductStoreQuery
    {
        return $this->getProvidedDependency(PriceProductResourceAliasStorageDependencyProvider::PROPEL_QUERY_PRICE_PRODUCT_STORE);
    }
}
