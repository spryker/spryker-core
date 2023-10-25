<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestShipmentsTransfer;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Creator\RestErrorMessageCreatorInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Extractor\RestCheckoutRequestAttributesExtractorInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ShipmentTypeStorageReaderInterface;

class MultiShippingAddressValidator implements ShippingAddressValidatorInterface
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
     * @var \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Creator\RestErrorMessageCreatorInterface
     */
    protected RestErrorMessageCreatorInterface $restErrorMessageCreator;

    /**
     * @param \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ShipmentTypeStorageReaderInterface $shipmentTypeStorageReader
     * @param \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Extractor\RestCheckoutRequestAttributesExtractorInterface $restCheckoutRequestAttributesExtractor
     * @param \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Creator\RestErrorMessageCreatorInterface $restErrorMessageCreator
     */
    public function __construct(
        ShipmentTypeStorageReaderInterface $shipmentTypeStorageReader,
        RestCheckoutRequestAttributesExtractorInterface $restCheckoutRequestAttributesExtractor,
        RestErrorMessageCreatorInterface $restErrorMessageCreator
    ) {
        $this->shipmentTypeStorageReader = $shipmentTypeStorageReader;
        $this->restCheckoutRequestAttributesExtractor = $restCheckoutRequestAttributesExtractor;
        $this->restErrorMessageCreator = $restErrorMessageCreator;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    public function validate(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): RestErrorCollectionTransfer
    {
        $restErrorCollectionTransfer = new RestErrorCollectionTransfer();

        $shipmentMethodIds = $this
            ->restCheckoutRequestAttributesExtractor
            ->extractShipmentMethodIdsFromRestCheckoutRequestAttributesTransfer($restCheckoutRequestAttributesTransfer);

        $applicableShipmentTypeStorageTransfersIndexedByShipmentMethodId = $this
            ->shipmentTypeStorageReader
            ->getApplicableShipmentTypeStorageTransfersIndexedByIdShipmentMethod($shipmentMethodIds);

        foreach ($restCheckoutRequestAttributesTransfer->getShipments() as $restShipmentsTransfer) {
            if ($this->isApplicable($restShipmentsTransfer, $applicableShipmentTypeStorageTransfersIndexedByShipmentMethodId)) {
                continue;
            }
            if ($this->isValidShipment($restShipmentsTransfer)) {
                continue;
            }
            $restErrorCollectionTransfer->addRestError(
                $this->restErrorMessageCreator->createItemShippingAddressMissingErrorMessage(
                    $restShipmentsTransfer->getItems(),
                ),
            );
        }

        return $restErrorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestShipmentsTransfer $restShipmentsTransfer
     * @param array<int, \Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $applicableShipmentTypeStorageTransfersIndexedByShipmentMethodId
     *
     * @return bool
     */
    protected function isApplicable(
        RestShipmentsTransfer $restShipmentsTransfer,
        array $applicableShipmentTypeStorageTransfersIndexedByShipmentMethodId
    ): bool {
        return array_key_exists(
            $restShipmentsTransfer->getIdShipmentMethodOrFail(),
            $applicableShipmentTypeStorageTransfersIndexedByShipmentMethodId,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RestShipmentsTransfer $restShipmentsTransfer
     *
     * @return bool
     */
    protected function isValidShipment(RestShipmentsTransfer $restShipmentsTransfer): bool
    {
        return $restShipmentsTransfer->getShippingAddress() !== null;
    }
}
