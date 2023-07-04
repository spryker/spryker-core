<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeStorage\Persistence;

use Orm\Zed\ProductOfferShipmentTypeStorage\Persistence\SpyProductOfferShipmentTypeStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\ProductOfferShipmentTypeStorage\ProductOfferShipmentTypeStorageConfig getConfig()
 * @method \Spryker\Zed\ProductOfferShipmentTypeStorage\Persistence\ProductOfferShipmentTypeStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductOfferShipmentTypeStorage\Persistence\ProductOfferShipmentTypeStorageRepositoryInterface getRepository()
 */
class ProductOfferShipmentTypeStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductOfferShipmentTypeStorage\Persistence\SpyProductOfferShipmentTypeStorageQuery
     */
    public function createProductOfferShipmentTypeStorageQuery(): SpyProductOfferShipmentTypeStorageQuery
    {
        return SpyProductOfferShipmentTypeStorageQuery::create();
    }
}
