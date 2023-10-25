<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\Rule\ShipmentTypeServicePoint;

use ArrayObject;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestShipmentsTransfer;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Creator\RestErrorMessageCreatorInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Extractor\RestCheckoutRequestAttributesExtractorInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ShipmentTypeStorageReaderInterface;

class MultiShipmentServicePointShipmentTypeServicePointValidatorRule implements ShipmentTypeServicePointValidatorRuleInterface
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
    public function validate(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestErrorCollectionTransfer {
        $applicableShipmentTypeStorageTransfers = $this->getApplicableShipmentTypeStorageTransfers($restCheckoutRequestAttributesTransfer);

        return $this->validateApplicableShipmentTypeStorageTransfers(
            $restCheckoutRequestAttributesTransfer,
            $applicableShipmentTypeStorageTransfers,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return list<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer>
     */
    protected function getApplicableShipmentTypeStorageTransfers(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): array {
        $shipmentMethodIds = $this
            ->restCheckoutRequestAttributesExtractor
            ->extractShipmentMethodIdsFromRestCheckoutRequestAttributesTransfer(
                $restCheckoutRequestAttributesTransfer,
            );

        return $this
            ->shipmentTypeStorageReader
            ->getApplicableShipmentTypeStorageTransfersIndexedByIdShipmentMethod($shipmentMethodIds);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param array<int, \Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $applicableShipmentTypeStorageTransfersIndexedByIdShipmentMethod
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    protected function validateApplicableShipmentTypeStorageTransfers(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        array $applicableShipmentTypeStorageTransfersIndexedByIdShipmentMethod
    ): RestErrorCollectionTransfer {
        $restErrorCollectionTransfer = new RestErrorCollectionTransfer();
        foreach ($restCheckoutRequestAttributesTransfer->getShipments() as $restShipmentsTransfer) {
            if (!$this->isApplicable($restShipmentsTransfer, $applicableShipmentTypeStorageTransfersIndexedByIdShipmentMethod)) {
                continue;
            }
            $restErrorCollectionTransfer = $this->validateShipments(
                $restShipmentsTransfer,
                $restCheckoutRequestAttributesTransfer->getServicePoints(),
                $restErrorCollectionTransfer,
            );
        }

        return $restErrorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestShipmentsTransfer $restShipmentsTransfer
     * @param array<int, \Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $applicableShipmentTypeTransfersIndexedByIdShipmentMethod
     *
     * @return bool
     */
    protected function isApplicable(
        RestShipmentsTransfer $restShipmentsTransfer,
        array $applicableShipmentTypeTransfersIndexedByIdShipmentMethod
    ): bool {
        return array_key_exists($restShipmentsTransfer->getIdShipmentMethodOrFail(), $applicableShipmentTypeTransfersIndexedByIdShipmentMethod);
    }

    /**
     * @param \Generated\Shared\Transfer\RestShipmentsTransfer $restShipmentsTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\RestServicePointTransfer> $restServicePointTransfers
     * @param \Generated\Shared\Transfer\RestErrorCollectionTransfer $restErrorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    protected function validateShipments(
        RestShipmentsTransfer $restShipmentsTransfer,
        ArrayObject $restServicePointTransfers,
        RestErrorCollectionTransfer $restErrorCollectionTransfer
    ): RestErrorCollectionTransfer {
        foreach ($restShipmentsTransfer->getItems() as $itemGroupKey) {
            if ($this->hasServicePoint($itemGroupKey, $restServicePointTransfers)) {
                continue;
            }
            $restErrorCollectionTransfer->addRestError(
                $this->restErrorMessageCreator->createServicePointNotProvidedErrorMessage(),
            );
        }

        return $restErrorCollectionTransfer;
    }

    /**
     * @param string $itemGroupKey
     * @param \ArrayObject<int, \Generated\Shared\Transfer\RestServicePointTransfer> $restServicePointTransfers
     *
     * @return bool
     */
    protected function hasServicePoint(string $itemGroupKey, ArrayObject $restServicePointTransfers): bool
    {
        foreach ($restServicePointTransfers as $restServicePointTransfer) {
            if (in_array($itemGroupKey, $restServicePointTransfer->getItems(), true)) {
                return true;
            }
        }

        return false;
    }
}
