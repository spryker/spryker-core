<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Expander;

use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ShipmentTypeCollectionTransfer;
use Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ProductOfferExtractorInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\Reader\ShipmentTypeReaderInterface;

class ShipmentTypeProductOfferShipmentTypeCollectionRequestExpander implements ShipmentTypeProductOfferShipmentTypeCollectionRequestExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ProductOfferExtractorInterface
     */
    protected ProductOfferExtractorInterface $productOfferExtractor;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Reader\ShipmentTypeReaderInterface
     */
    protected ShipmentTypeReaderInterface $shipmentTypeReader;

    /**
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ProductOfferExtractorInterface $productOfferExtractor
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Reader\ShipmentTypeReaderInterface $shipmentTypeReader
     */
    public function __construct(
        ProductOfferExtractorInterface $productOfferExtractor,
        ShipmentTypeReaderInterface $shipmentTypeReader
    ) {
        $this->productOfferExtractor = $productOfferExtractor;
        $this->shipmentTypeReader = $shipmentTypeReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionRequestTransfer
     */
    public function expandWithShipmentTypeIds(
        ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
    ): ProductOfferShipmentTypeCollectionRequestTransfer {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers */
        $productOfferTransfers = $productOfferShipmentTypeCollectionRequestTransfer->getProductOffers();

        $shipmentTypeUuids = $this->productOfferExtractor->extractShipmentTypeUuidsFromProductOfferTransfers($productOfferTransfers);
        $shipmentTypeCollectionTransfer = $this->shipmentTypeReader->getShipmentTypeCollectionByShipmentTypeUuids($shipmentTypeUuids);
        $shipmentTypeTransfersIndexedByShipmentTypeUuid = $this->getShipmentTypeTransfersIndexedByShipmentTypeUuid($shipmentTypeCollectionTransfer);

        foreach ($productOfferTransfers as $productOfferTransfer) {
            $this->expandProductOfferShipmentTypesWithShipmentTypeIds($productOfferTransfer, $shipmentTypeTransfersIndexedByShipmentTypeUuid);
        }

        return $productOfferShipmentTypeCollectionRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeCollectionTransfer $shipmentTypeCollectionTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\ShipmentTypeTransfer>
     */
    protected function getShipmentTypeTransfersIndexedByShipmentTypeUuid(
        ShipmentTypeCollectionTransfer $shipmentTypeCollectionTransfer
    ): array {
        $shipmentTypeTransfersIndexedByShipmentTypeUuid = [];
        foreach ($shipmentTypeCollectionTransfer->getShipmentTypes() as $shipmentTypeTransfer) {
            $shipmentTypeTransfersIndexedByShipmentTypeUuid[$shipmentTypeTransfer->getUuidOrFail()] = $shipmentTypeTransfer;
        }

        return $shipmentTypeTransfersIndexedByShipmentTypeUuid;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     * @param array<string, \Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfersIndexedByShipmentTypeUuid
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    protected function expandProductOfferShipmentTypesWithShipmentTypeIds(
        ProductOfferTransfer $productOfferTransfer,
        array $shipmentTypeTransfersIndexedByShipmentTypeUuid
    ): ProductOfferTransfer {
        foreach ($productOfferTransfer->getShipmentTypes() as $shipmentTypeTransfer) {
            if (!isset($shipmentTypeTransfersIndexedByShipmentTypeUuid[$shipmentTypeTransfer->getUuidOrFail()])) {
                continue;
            }

            $shipmentTypeTransfer->setIdShipmentType($shipmentTypeTransfersIndexedByShipmentTypeUuid[$shipmentTypeTransfer->getUuidOrFail()]->getIdShipmentTypeOrFail());
        }

        return $productOfferTransfer;
    }
}
