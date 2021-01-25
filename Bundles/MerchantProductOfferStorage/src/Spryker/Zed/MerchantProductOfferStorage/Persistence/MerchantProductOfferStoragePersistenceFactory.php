<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Persistence;

use Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorageQuery;
use Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductOfferStorageQuery;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\MerchantProductOfferStorage\MerchantProductOfferStorageDependencyProvider;
use Spryker\Zed\MerchantProductOfferStorage\Persistence\Propel\Mapper\ProductOfferStorageMapper;

/**
 * @method \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantProductOfferStorage\MerchantProductOfferStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageRepositoryInterface getRepository()
 */
class MerchantProductOfferStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorageQuery
     */
    public function createProductConcreteProductOffersStoragePropelQuery(): SpyProductConcreteProductOffersStorageQuery
    {
        return SpyProductConcreteProductOffersStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductOfferStorageQuery
     */
    public function createProductOfferStoragePropelQuery(): SpyProductOfferStorageQuery
    {
        return SpyProductOfferStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\MerchantProductOfferStorage\Persistence\Propel\Mapper\ProductOfferStorageMapper
     */
    public function createProductOfferStorageMapper(): ProductOfferStorageMapper
    {
        return new ProductOfferStorageMapper();
    }

    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    public function getProductOfferPropelQuery(): SpyProductOfferQuery
    {
        return $this->getProvidedDependency(MerchantProductOfferStorageDependencyProvider::PROPEL_QUERY_PRODUCT_OFFER);
    }
}
