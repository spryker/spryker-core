<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Persistence;

use Orm\Zed\ProductOfferServicePoint\Persistence\SpyProductOfferServiceQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductOfferServicePoint\Persistence\Propel\Mapper\ProductOfferServiceMapper;

/**
 * @method \Spryker\Zed\ProductOfferServicePoint\ProductOfferServicePointConfig getConfig()
 * @method \Spryker\Zed\ProductOfferServicePoint\Persistence\ProductOfferServicePointRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferServicePoint\Persistence\ProductOfferServicePointEntityManagerInterface getEntityManager()
 */
class ProductOfferServicePointPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductOfferServicePoint\Persistence\SpyProductOfferServiceQuery
     */
    public function getProductOfferServiceQuery(): SpyProductOfferServiceQuery
    {
        return SpyProductOfferServiceQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePoint\Persistence\Propel\Mapper\ProductOfferServiceMapper
     */
    public function createProductOfferServiceMapper(): ProductOfferServiceMapper
    {
        return new ProductOfferServiceMapper();
    }
}
