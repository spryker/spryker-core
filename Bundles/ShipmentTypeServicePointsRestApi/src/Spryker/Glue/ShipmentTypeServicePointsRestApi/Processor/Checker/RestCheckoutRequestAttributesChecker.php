<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Checker;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Extractor\RestCheckoutRequestAttributesExtractorInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ShipmentTypeStorageReaderInterface;

class RestCheckoutRequestAttributesChecker implements RestCheckoutRequestAttributesCheckerInterface
{
    /**
     * @var \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ShipmentTypeStorageReaderInterface
     */
    protected ShipmentTypeStorageReaderInterface $shipmentTypeStorageReader;

    /**
     * @var \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Extractor\RestCheckoutRequestAttributesExtractorInterface
     */
    protected RestCheckoutRequestAttributesExtractorInterface $restCheckoutRequestAttributesExtractor;

    /**
     * @param \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ShipmentTypeStorageReaderInterface $shipmentTypeStorageReader
     * @param \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Extractor\RestCheckoutRequestAttributesExtractorInterface $restCheckoutRequestAttributesExtractor
     */
    public function __construct(
        ShipmentTypeStorageReaderInterface $shipmentTypeStorageReader,
        RestCheckoutRequestAttributesExtractorInterface $restCheckoutRequestAttributesExtractor
    ) {
        $this->shipmentTypeStorageReader = $shipmentTypeStorageReader;
        $this->restCheckoutRequestAttributesExtractor = $restCheckoutRequestAttributesExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return bool
     */
    public function hasApplicableShipmentTypes(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): bool
    {
        $shipmentMethodIds = $restCheckoutRequestAttributesTransfer->getShipments()->count() > 0
            ? $this->extractShipmentMethodIdsFromMultiShipmentRequest($restCheckoutRequestAttributesTransfer)
            : $this->extractShipmentMethodIdsFromSingleShipmentRequest($restCheckoutRequestAttributesTransfer);

        $applicableShipmentTypeStorageTransfers = $this
            ->shipmentTypeStorageReader
            ->getApplicableShipmentTypeStorageTransfersIndexedByIdShipmentMethod($shipmentMethodIds);

        return $applicableShipmentTypeStorageTransfers !== [];
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return list<int>
     */
    protected function extractShipmentMethodIdsFromSingleShipmentRequest(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): array {
        return [$restCheckoutRequestAttributesTransfer->getShipmentOrFail()->getIdShipmentMethodOrFail()];
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return list<int>
     */
    protected function extractShipmentMethodIdsFromMultiShipmentRequest(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): array {
        return $this
            ->restCheckoutRequestAttributesExtractor
            ->extractShipmentMethodIdsFromRestCheckoutRequestAttributesTransfer($restCheckoutRequestAttributesTransfer);
    }
}
