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
     * @param string $productOfferReference
     * @param string $shipmentTypeUuid
     *
     * @return void
     */
    public function createProductOfferShipmentType(string $productOfferReference, string $shipmentTypeUuid): void
    {
        $productOfferShipmentTypeEntity = (new SpyProductOfferShipmentType())
            ->setProductOfferReference($productOfferReference)
            ->setShipmentTypeUuid($shipmentTypeUuid);

        $productOfferShipmentTypeEntity->save();
    }

    /**
     * @param string $productOfferReference
     * @param array<string> $shipmentTypeUuids
     *
     * @return void
     */
    public function deleteProductOfferShipmentTypes(string $productOfferReference, array $shipmentTypeUuids): void
    {
        $this->getFactory()
            ->createProductOfferShipmentTypeQuery()
            ->filterByProductOfferReference($productOfferReference)
            ->filterByShipmentTypeUuid_In($shipmentTypeUuids)
            ->find()
            ->delete();
    }
}
