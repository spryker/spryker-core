<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStorage\Persistence;

use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Orm\Zed\ProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorageQuery;
use Orm\Zed\ProductOfferStorage\Persistence\SpyProductOfferStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductOfferStorage\Persistence\Propel\Mapper\ProductOfferStorageMapper;
use Spryker\Zed\ProductOfferStorage\ProductOfferStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductOfferStorage\ProductOfferStorageConfig getConfig()
 * @method \Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStorageRepositoryInterface getRepository()
 */
class ProductOfferStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorageQuery
     */
    public function createProductConcreteProductOffersStoragePropelQuery(): SpyProductConcreteProductOffersStorageQuery
    {
        return SpyProductConcreteProductOffersStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductOfferStorage\Persistence\SpyProductOfferStorageQuery
     */
    public function createProductOfferStoragePropelQuery(): SpyProductOfferStorageQuery
    {
        return SpyProductOfferStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductOfferStorage\Persistence\Propel\Mapper\ProductOfferStorageMapper
     */
    public function createProductOfferStorageMapper(): ProductOfferStorageMapper
    {
        return new ProductOfferStorageMapper(
            $this->getProductOfferStorageMapperPlugins(),
        );
    }

    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    public function getProductOfferPropelQuery(): SpyProductOfferQuery
    {
        return $this->getProvidedDependency(ProductOfferStorageDependencyProvider::PROPEL_QUERY_PRODUCT_OFFER);
    }

    /**
     * @return array<\Spryker\Zed\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageMapperPluginInterface>
     */
    public function getProductOfferStorageMapperPlugins(): array
    {
        return $this->getProvidedDependency(ProductOfferStorageDependencyProvider::PLUGINS_PRODUCT_OFFER_STORAGE_MAPPER);
    }
}
