<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Expander;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\ServicePointStorageCollectionTransfer;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Extractor\RestCheckoutRequestAttributesExtractorInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ServicePointReaderInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ShipmentTypeStorageReaderInterface;

class MultiShipmentServicePointAddressExpander extends AbstractServicePointAddressExpander
{
    /**
     * @var \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ShipmentTypeStorageReaderInterface
     */
    protected ShipmentTypeStorageReaderInterface $shipmentTypeStorageReader;

    /**
     * @var \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ServicePointReaderInterface
     */
    protected ServicePointReaderInterface $servicePointReader;

    /**
     * @var \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Extractor\RestCheckoutRequestAttributesExtractorInterface
     */
    protected RestCheckoutRequestAttributesExtractorInterface $restCheckoutRequestAttributesExtractor;

    /**
     * @param \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ShipmentTypeStorageReaderInterface $shipmentTypeStorageReader
     * @param \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ServicePointReaderInterface $servicePointReader
     * @param \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Extractor\RestCheckoutRequestAttributesExtractorInterface $restCheckoutRequestAttributesExtractor
     */
    public function __construct(
        ShipmentTypeStorageReaderInterface $shipmentTypeStorageReader,
        ServicePointReaderInterface $servicePointReader,
        RestCheckoutRequestAttributesExtractorInterface $restCheckoutRequestAttributesExtractor
    ) {
        $this->shipmentTypeStorageReader = $shipmentTypeStorageReader;
        $this->servicePointReader = $servicePointReader;
        $this->restCheckoutRequestAttributesExtractor = $restCheckoutRequestAttributesExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function expandRestCheckoutRequestAttributesTransfer(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestCheckoutRequestAttributesTransfer {
        $shipmentMethodIds = $this->restCheckoutRequestAttributesExtractor->extractShipmentMethodIdsFromRestCheckoutRequestAttributesTransfer(
            $restCheckoutRequestAttributesTransfer,
        );
        $applicableShipmentTypeStorageTransfers = $this->shipmentTypeStorageReader->getApplicableShipmentTypeStorageTransfersIndexedByIdShipmentMethod(
            $shipmentMethodIds,
        );
        $restShipmentsTransfersWithApplicableShipmentMethod = $this->getRestShipmentsTransfersWithApplicableShipmentMethod(
            $restCheckoutRequestAttributesTransfer,
            $applicableShipmentTypeStorageTransfers,
        );

        $itemGroupKeysGroupedByServicePointUuid = $this->getItemGroupKeysGroupedByServicePointUuid($restCheckoutRequestAttributesTransfer);
        $servicePointStorageCollectionTransfer = $this->servicePointReader->getServicePointStorageTransfersByUuids(
            array_keys($itemGroupKeysGroupedByServicePointUuid),
        );

        $servicePointStorageTransfersIndexedByUuid = $this->getServicePointStorageTransfersIndexedByUuid(
            $servicePointStorageCollectionTransfer,
        );

        return $this->replaceShippingAddressesWithServicePointAddresses(
            $restCheckoutRequestAttributesTransfer,
            $restShipmentsTransfersWithApplicableShipmentMethod,
            $itemGroupKeysGroupedByServicePointUuid,
            $servicePointStorageTransfersIndexedByUuid,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param list<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $applicableShipmentTypeTransfers
     *
     * @return list<\Generated\Shared\Transfer\RestShipmentsTransfer>
     */
    protected function getRestShipmentsTransfersWithApplicableShipmentMethod(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        array $applicableShipmentTypeTransfers
    ): array {
        $restShipmentTransfersWithApplicableShipmentMethod = [];
        foreach ($restCheckoutRequestAttributesTransfer->getShipments() as $restShipmentTransfer) {
            if (
                $restShipmentTransfer->getIdShipmentMethod()
                && $this->isApplicableShipmentMethod($restShipmentTransfer->getIdShipmentMethodOrFail(), $applicableShipmentTypeTransfers)
            ) {
                $restShipmentTransfersWithApplicableShipmentMethod[] = $restShipmentTransfer;
            }
        }

        return $restShipmentTransfersWithApplicableShipmentMethod;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointStorageCollectionTransfer $servicePointStorageCollectionTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\ServicePointStorageTransfer>
     */
    protected function getServicePointStorageTransfersIndexedByUuid(
        ServicePointStorageCollectionTransfer $servicePointStorageCollectionTransfer
    ): array {
        $servicePointStorageTransfersIndexedByUuid = [];
        foreach ($servicePointStorageCollectionTransfer->getServicePointStorages() as $servicePointStorageTransfer) {
            $servicePointStorageTransfersIndexedByUuid[$servicePointStorageTransfer->getUuidOrFail()] = $servicePointStorageTransfer;
        }

        return $servicePointStorageTransfersIndexedByUuid;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return array<string, list<string>>
     */
    protected function getItemGroupKeysGroupedByServicePointUuid(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): array {
        $itemGroupKeysGroupedByServicePointUuid = [];
        foreach ($restCheckoutRequestAttributesTransfer->getServicePoints() as $restServicePointTransfer) {
            $itemGroupKeysGroupedByServicePointUuid[$restServicePointTransfer->getIdServicePointOrFail()] = $restServicePointTransfer->getItems();
        }

        return $itemGroupKeysGroupedByServicePointUuid;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param list<\Generated\Shared\Transfer\RestShipmentsTransfer> $restShipmentsTransfersWithApplicableShipmentMethod
     * @param array<string, list<string>> $itemGroupKeysGroupedByServicePointUuid
     * @param array<string, \Generated\Shared\Transfer\ServicePointStorageTransfer> $servicePointStorageTransfersIndexedByUuid
     *
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    protected function replaceShippingAddressesWithServicePointAddresses(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        array $restShipmentsTransfersWithApplicableShipmentMethod,
        array $itemGroupKeysGroupedByServicePointUuid,
        array $servicePointStorageTransfersIndexedByUuid
    ): RestCheckoutRequestAttributesTransfer {
        $restCustomerTransfer = $restCheckoutRequestAttributesTransfer->getCustomerOrFail();
        foreach ($restShipmentsTransfersWithApplicableShipmentMethod as $restShipmentsTransfer) {
            $servicePointUuid = $this->findServicePointUuidForItems($restShipmentsTransfer->getItems(), $itemGroupKeysGroupedByServicePointUuid);
            if (!$servicePointUuid) {
                continue;
            }

            $restShipmentsTransfer->setShippingAddress($this->createRestShippingAddressTransfer(
                $restCustomerTransfer,
                $servicePointStorageTransfersIndexedByUuid[$servicePointUuid]->getAddressOrFail(),
            ));
        }

        return $restCheckoutRequestAttributesTransfer;
    }

    /**
     * @param list<string> $itemGroupKeys
     * @param array<string, list<string>> $itemGroupKeysGroupedByServicePointUuid
     *
     * @return string|null
     */
    protected function findServicePointUuidForItems(array $itemGroupKeys, array $itemGroupKeysGroupedByServicePointUuid): ?string
    {
        foreach ($itemGroupKeysGroupedByServicePointUuid as $servicePointUuid => $servicePointItemGroupKeys) {
            if (array_diff($itemGroupKeys, $servicePointItemGroupKeys) === []) {
                return $servicePointUuid;
            }
        }

        return null;
    }
}
