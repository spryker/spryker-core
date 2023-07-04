<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ProductOfferShipmentType\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Orm\Zed\ProductOfferShipmentType\Persistence\SpyProductOfferShipmentType;
use Orm\Zed\ProductOfferShipmentType\Persistence\SpyProductOfferShipmentTypeQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class ProductOfferShipmentTypeHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer
     */
    public function haveProductOfferShipmentType(
        ProductOfferTransfer $productOfferTransfer,
        ShipmentTypeTransfer $shipmentTypeTransfer
    ): ProductOfferShipmentTypeTransfer {
        $productOfferShipmentTypeEntity = new SpyProductOfferShipmentType();
        $productOfferShipmentTypeEntity->setFkProductOffer($productOfferTransfer->getIdProductOfferOrFail());
        $productOfferShipmentTypeEntity->setFkShipmentType($shipmentTypeTransfer->getIdShipmentTypeOrFail());

        $productOfferShipmentTypeEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productOfferShipmentTypeEntity): void {
            $this->deleteProductOfferShipmentType($productOfferShipmentTypeEntity->getIdProductOfferShipmentType());
        });

        return (new ProductOfferShipmentTypeTransfer())
            ->setIdProductOfferShipmentType($productOfferShipmentTypeEntity->getIdProductOfferShipmentType())
            ->setProductOffer($productOfferTransfer)
            ->addShipmentType($shipmentTypeTransfer);
    }

    /**
     * @param int $idProductOfferShipmentType
     *
     * @return void
     */
    protected function deleteProductOfferShipmentType(int $idProductOfferShipmentType): void
    {
        $this
            ->getProductOfferShipmentTypeQuery()
            ->filterByIdProductOfferShipmentType($idProductOfferShipmentType)
            ->delete();
    }

    /**
     * @return \Orm\Zed\ProductOfferShipmentType\Persistence\SpyProductOfferShipmentTypeQuery
     */
    protected function getProductOfferShipmentTypeQuery(): SpyProductOfferShipmentTypeQuery
    {
        return SpyProductOfferShipmentTypeQuery::create();
    }
}
