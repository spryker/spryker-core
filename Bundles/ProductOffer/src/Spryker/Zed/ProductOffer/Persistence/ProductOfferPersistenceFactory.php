<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Persistence;

use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferStoreQuery;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductOffer\Persistence\Propel\Mapper\ProductOfferMapper;
use Spryker\Zed\ProductOffer\ProductOfferDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOffer\ProductOfferConfig getConfig()
 * @method \Spryker\Zed\ProductOffer\Persistence\ProductOfferEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface getRepository()
 */
class ProductOfferPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    public function createProductOfferPropelQuery(): SpyProductOfferQuery
    {
        return SpyProductOfferQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferStoreQuery
     */
    public function createProductOfferStoreQuery(): SpyProductOfferStoreQuery
    {
        return SpyProductOfferStoreQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductOffer\Persistence\Propel\Mapper\ProductOfferMapper
     */
    public function createProductOfferMapper(): ProductOfferMapper
    {
        return new ProductOfferMapper();
    }

    /**
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery
     */
    public function getStorePropelQuery(): SpyStoreQuery
    {
        return $this->getProvidedDependency(ProductOfferDependencyProvider::PROPEL_QUERY_STORE);
    }
}
