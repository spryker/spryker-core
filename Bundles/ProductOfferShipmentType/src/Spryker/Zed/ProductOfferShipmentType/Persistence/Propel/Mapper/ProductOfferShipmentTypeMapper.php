<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Orm\Zed\ProductOfferShipmentType\Persistence\Map\SpyProductOfferShipmentTypeTableMap;
use Propel\Runtime\Collection\ArrayCollection;
use Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeRepository;

class ProductOfferShipmentTypeMapper
{
    /**
     * @param \Propel\Runtime\Collection\ArrayCollection<array<string, int|string>> $productOfferShipmentTypeDataCollection
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer
     */
    public function mapProductOfferShipmentTypeDataCollectionToProductOfferShipmentTypeCollectionTransfer(
        ArrayCollection $productOfferShipmentTypeDataCollection,
        ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer
    ): ProductOfferShipmentTypeCollectionTransfer {
        foreach ($productOfferShipmentTypeDataCollection as $productOfferShipmentTypeData) {
            $productOfferShipmentTypeTransfer = $this->mapProductOfferShipmentTypeDataToProductOfferShipmentTypeTransfer(
                $productOfferShipmentTypeData,
                new ProductOfferShipmentTypeTransfer(),
            );

            $productOfferShipmentTypeCollectionTransfer->addProductOfferShipmentType($productOfferShipmentTypeTransfer);
        }

        return $productOfferShipmentTypeCollectionTransfer;
    }

    /**
     * @param array<string, int|string> $productOfferShipmentData
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer $productOfferShipmentTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer
     */
    protected function mapProductOfferShipmentTypeDataToProductOfferShipmentTypeTransfer(
        array $productOfferShipmentData,
        ProductOfferShipmentTypeTransfer $productOfferShipmentTypeTransfer
    ): ProductOfferShipmentTypeTransfer {
        $productOfferShipmentTypeTransfer->setProductOffer(
            (new ProductOfferTransfer())
                ->setIdProductOffer(
                    (int)$productOfferShipmentData[SpyProductOfferShipmentTypeTableMap::COL_FK_PRODUCT_OFFER],
                )
                ->setProductOfferReference((string)$productOfferShipmentData[SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE]),
        );

        if (!isset($productOfferShipmentData[ProductOfferShipmentTypeRepository::SHIPMENT_TYPE_IDS_GROUPED])) {
            return $productOfferShipmentTypeTransfer
                ->setIdProductOfferShipmentType((int)$productOfferShipmentData[SpyProductOfferShipmentTypeTableMap::COL_ID_PRODUCT_OFFER_SHIPMENT_TYPE])
                ->addShipmentType((new ShipmentTypeTransfer())->setIdShipmentType((int)$productOfferShipmentData[SpyProductOfferShipmentTypeTableMap::COL_FK_SHIPMENT_TYPE]));
        }

        $shipmentTypeIds = (string)$productOfferShipmentData[ProductOfferShipmentTypeRepository::SHIPMENT_TYPE_IDS_GROUPED];
        foreach (explode(',', $shipmentTypeIds) as $idShipmentType) {
            $productOfferShipmentTypeTransfer->addShipmentType((new ShipmentTypeTransfer())->setIdShipmentType((int)$idShipmentType));
        }

        return $productOfferShipmentTypeTransfer;
    }
}
