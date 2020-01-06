<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailabilityStorage\Persistence;

use Orm\Zed\ProductOfferAvailabilityStorage\Persistence\SpyProductOfferAvailabilityStorageQuery;
use Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStockQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductOfferAvailabilityStorage\Persistence\Propel\Mapper\ProductOfferAvailabilityStorageMapper;
use Spryker\Zed\ProductOfferAvailabilityStorage\Persistence\Propel\Mapper\ProductOfferAvailabilityStorageMapperInterface;
use Spryker\Zed\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageConfig getConfig()
 * @method \Spryker\Zed\ProductOfferAvailabilityStorage\Persistence\ProductOfferAvailabilityStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductOfferAvailabilityStorage\Persistence\ProductOfferAvailabilityStorageRepositoryInterface getRepository()
 */
class ProductOfferAvailabilityStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStockQuery
     */
    public function getProductOfferStockPropelQuery(): SpyProductOfferStockQuery
    {
        return $this->getProvidedDependency(ProductOfferAvailabilityStorageDependencyProvider::PROPEL_QUERY_PRODUCT_OFFER_STOCK);
    }

    /**
     * @return \Spryker\Zed\ProductOfferAvailabilityStorage\Persistence\Propel\Mapper\ProductOfferAvailabilityStorageMapperInterface
     */
    public function createProductOfferAvailabilityStorageMapper(): ProductOfferAvailabilityStorageMapperInterface
    {
        return new ProductOfferAvailabilityStorageMapper();
    }

    /**
     * @return \Orm\Zed\ProductOfferAvailabilityStorage\Persistence\SpyProductOfferAvailabilityStorageQuery
     */
    public function getProductOfferAvailabilityStoragePropelQuery(): SpyProductOfferAvailabilityStorageQuery
    {
        return SpyProductOfferAvailabilityStorageQuery::create();
    }
}
