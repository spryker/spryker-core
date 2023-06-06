<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Persistence;

use Orm\Zed\ProductOfferShipmentType\Persistence\SpyProductOfferShipmentTypeQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\ProductOfferShipmentType\ProductOfferShipmentTypeConfig getConfig()
 * @method \Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeEntityManagerInterface getEntityManager()
 */
class ProductOfferShipmentTypePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductOfferShipmentType\Persistence\SpyProductOfferShipmentTypeQuery
     */
    public function createProductOfferShipmentTypeQuery(): SpyProductOfferShipmentTypeQuery
    {
        return SpyProductOfferShipmentTypeQuery::create();
    }
}
