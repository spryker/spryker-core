<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Persistence;

use Orm\Zed\ProductOfferShipmentType\Persistence\Map\SpyProductOfferShipmentTypeTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypePersistenceFactory getFactory()
 */
class ProductOfferShipmentTypeRepository extends AbstractRepository implements ProductOfferShipmentTypeRepositoryInterface
{
    /**
     * @param string $productOfferReference
     *
     * @return array<string>
     */
    public function getShipmentTypeUuidsByProductOfferReference(string $productOfferReference): array
    {
        return $this->getFactory()
            ->createProductOfferShipmentTypeQuery()
            ->filterByProductOfferReference($productOfferReference)
            ->select([SpyProductOfferShipmentTypeTableMap::COL_SHIPMENT_TYPE_UUID])
            ->find()
            ->getData();
    }
}
