<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer;
use Generated\Shared\Transfer\ShipmentTypeCollectionTransfer;
use Spryker\Zed\ProductOfferShipmentType\Business\Indexer\ShipmentTypeIndexerInterface;

class ShipmentTypeProductOfferShipmentTypeCollectionFilter implements ShipmentTypeProductOfferShipmentTypeCollectionFilterInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Indexer\ShipmentTypeIndexerInterface
     */
    protected ShipmentTypeIndexerInterface $shipmentTypeIndexer;

    /**
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Indexer\ShipmentTypeIndexerInterface $shipmentTypeIndexer
     */
    public function __construct(ShipmentTypeIndexerInterface $shipmentTypeIndexer)
    {
        $this->shipmentTypeIndexer = $shipmentTypeIndexer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer
     * @param \Generated\Shared\Transfer\ShipmentTypeCollectionTransfer $shipmentTypeCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer
     */
    public function filterProductOfferShipmentTypeCollectionTransfersByShipmentTypeCollectionTransfer(
        ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer,
        ShipmentTypeCollectionTransfer $shipmentTypeCollectionTransfer
    ): ProductOfferShipmentTypeCollectionTransfer {
        $indexedShipmentTypeTransfers = $this->shipmentTypeIndexer->getShipmentTypeTransfersIndexedByIdShipmentType(
            $shipmentTypeCollectionTransfer->getShipmentTypes(),
        );

        foreach ($productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes() as $productOfferShipmentTypeTransfer) {
            $this->filterProductOfferShipmentTypeTransferByShipmentTypeTransfers(
                $productOfferShipmentTypeTransfer,
                $indexedShipmentTypeTransfers,
            );
        }

        return $productOfferShipmentTypeCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer $productOfferShipmentTypeTransfer
     * @param array<int, \Generated\Shared\Transfer\ShipmentTypeTransfer> $indexedShipmentTypeTransfers
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer
     */
    protected function filterProductOfferShipmentTypeTransferByShipmentTypeTransfers(
        ProductOfferShipmentTypeTransfer $productOfferShipmentTypeTransfer,
        array $indexedShipmentTypeTransfers
    ): ProductOfferShipmentTypeTransfer {
        $filteredShipmenTypeTransfers = new ArrayObject();
        foreach ($productOfferShipmentTypeTransfer->getShipmentTypes() as $shipmentTypeTransfer) {
            $idShipmentType = $shipmentTypeTransfer->getIdShipmentTypeOrFail();
            if (!isset($indexedShipmentTypeTransfers[$idShipmentType])) {
                continue;
            }

            $filteredShipmenTypeTransfers->append($indexedShipmentTypeTransfers[$idShipmentType]);
        }

        return $productOfferShipmentTypeTransfer->setShipmentTypes($filteredShipmenTypeTransfers);
    }
}
