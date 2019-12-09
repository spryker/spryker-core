<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Persistence;

use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductOffer\Persistence\Propel\Mapper\ProductOfferMapper;

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
     * @return \Spryker\Zed\ProductOffer\Persistence\Propel\Mapper\ProductOfferMapper
     */
    public function createPropelProductOfferMapper(): ProductOfferMapper
    {
        return new ProductOfferMapper();
    }
}
