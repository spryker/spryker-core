<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeCollectionTransfer;
use Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ShipmentTypeExtractorInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\Indexer\ShipmentTypeIndexerInterface;

class ShipmentTypeProductOfferShipmentTypeCollectionExpander implements ShipmentTypeProductOfferShipmentTypeCollectionExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Indexer\ShipmentTypeIndexerInterface
     */
    protected ShipmentTypeIndexerInterface $shipmentTypeIndexer;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ShipmentTypeExtractorInterface
     */
    protected ShipmentTypeExtractorInterface $shipmentTypeExtractor;

    /**
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Indexer\ShipmentTypeIndexerInterface $shipmentTypeIndexer
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ShipmentTypeExtractorInterface $shipmentTypeExtractor
     */
    public function __construct(
        ShipmentTypeIndexerInterface $shipmentTypeIndexer,
        ShipmentTypeExtractorInterface $shipmentTypeExtractor
    ) {
        $this->shipmentTypeIndexer = $shipmentTypeIndexer;
        $this->shipmentTypeExtractor = $shipmentTypeExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer
     * @param \Generated\Shared\Transfer\ShipmentTypeCollectionTransfer $shipmentTypeCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer
     */
    public function expandProductOfferShipmentTypeCollectionWithShipmentTypes(
        ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer,
        ShipmentTypeCollectionTransfer $shipmentTypeCollectionTransfer
    ): ProductOfferShipmentTypeCollectionTransfer {
        $shipmentTypeIdsGroupedByProductOfferId = $this->getShipmentTypeIdsGroupedByProductOfferId(
            $productOfferShipmentTypeCollectionTransfer,
        );

        $indexedShipmentTypeTransfers = $this->shipmentTypeIndexer->getShipmentTypeTransfersIndexedByIdShipmentType(
            $shipmentTypeCollectionTransfer->getShipmentTypes(),
        );
        foreach ($productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes() as $productOfferShipmentTypeTransfer) {
            $idProductOffer = $productOfferShipmentTypeTransfer->getProductOfferOrFail()->getIdProductOfferOrFail();
            $shipmentTypeIds = $shipmentTypeIdsGroupedByProductOfferId[$idProductOffer];
            $shipmentTypeTransfers = array_values(array_intersect_key($indexedShipmentTypeTransfers, array_flip($shipmentTypeIds)));
            if ($shipmentTypeTransfers === []) {
                continue;
            }

            $productOfferShipmentTypeTransfer->setShipmentTypes(new ArrayObject($shipmentTypeTransfers));
        }

        return $productOfferShipmentTypeCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer
     *
     * @return array<int, list<int>>
     */
    protected function getShipmentTypeIdsGroupedByProductOfferId(
        ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer
    ): array {
        $shipmentTypeIdsGroupedByProductOfferId = [];
        foreach ($productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes() as $productOfferShipmentTypeTransfer) {
            $idProductOffer = $productOfferShipmentTypeTransfer->getProductOfferOrFail()->getIdProductOfferOrFail();
            $shipmentTypeIdsGroupedByProductOfferId[$idProductOffer] = $this->shipmentTypeExtractor->extractShipmentTypeIdsFromShipmentTypeTransfers(
                $productOfferShipmentTypeTransfer->getShipmentTypes(),
            );
        }

        return $shipmentTypeIdsGroupedByProductOfferId;
    }
}
