<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence;

use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorageQuery;
use Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\PriceProductMerchantRelationshipStorageDependencyProvider;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\PriceProductMerchantRelationshipStorageConfig getConfig()
 */
class PriceProductMerchantRelationshipStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery
     */
    public function getPropelPriceProductStoreQuery(): SpyPriceProductStoreQuery
    {
        return $this->getProvidedDependency(PriceProductMerchantRelationshipStorageDependencyProvider::QUERY_PROPEL_PRICE_PRODUCT_STORE);
    }

    /**
     * @return \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorageQuery
     */
    public function createPriceProductConcreteMerchantRelationshipStorageQuery(): SpyPriceProductConcreteMerchantRelationshipStorageQuery
    {
        return SpyPriceProductConcreteMerchantRelationshipStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorageQuery
     */
    public function createPriceProductAbstractMerchantRelationshipStorageQuery(): SpyPriceProductAbstractMerchantRelationshipStorageQuery
    {
        return SpyPriceProductAbstractMerchantRelationshipStorageQuery::create();
    }
}
