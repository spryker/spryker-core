<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Persistence;

use Orm\Zed\ProductOfferShipmentType\Persistence\SpyProductOfferShipmentType;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypePersistenceFactory getFactory()
 */
class ProductOfferShipmentTypeEntityManager extends AbstractEntityManager implements ProductOfferShipmentTypeEntityManagerInterface
{
    /**
     * @param int $idProductOffer
     * @param int $idShipmentType
     *
     * @return void
     */
    public function createProductOfferShipmentType(int $idProductOffer, int $idShipmentType): void
    {
        $productOfferShipmentTypeEntity = (new SpyProductOfferShipmentType())
            ->setFkProductOffer($idProductOffer)
            ->setFkShipmentType($idShipmentType);

        $productOfferShipmentTypeEntity->save();
    }

    /**
     * @param int $idProductOffer
     * @param array<int> $shipmentTypeIds
     *
     * @return void
     */
    public function deleteProductOfferShipmentTypes(int $idProductOffer, array $shipmentTypeIds): void
    {
        $this->getFactory()
            ->createProductOfferShipmentTypeQuery()
            ->filterByFkProductOffer($idProductOffer)
            ->filterByFkShipmentType_In($shipmentTypeIds)
            ->find()
            ->delete();
    }
}
