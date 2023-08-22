<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\GlueFilterTransfer;
use Generated\Shared\Transfer\ShipmentTypeConditionsTransfer;
use Generated\Shared\Transfer\ShipmentTypesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;

class ShipmentTypeMapper implements ShipmentTypeMapperInterface
{
    /**
     * @var array<string, string>
     */
    protected const SHIPMENT_TYPES_ATTRIBUTES_SHIPMENT_TYPE_CONDITIONS_FIELD_MAP = [
        ShipmentTypesBackendApiAttributesTransfer::KEY => ShipmentTypeConditionsTransfer::KEYS,
        ShipmentTypesBackendApiAttributesTransfer::NAME => ShipmentTypeConditionsTransfer::NAMES,
        ShipmentTypesBackendApiAttributesTransfer::STORES => ShipmentTypeConditionsTransfer::STORE_NAMES,
    ];

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     * @param \Generated\Shared\Transfer\ShipmentTypesBackendApiAttributesTransfer $shipmentTypesBackendApiAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypesBackendApiAttributesTransfer
     */
    public function mapShipmentTypeTransferToShipmentTypesBackendApiAttributesTransfer(
        ShipmentTypeTransfer $shipmentTypeTransfer,
        ShipmentTypesBackendApiAttributesTransfer $shipmentTypesBackendApiAttributesTransfer
    ): ShipmentTypesBackendApiAttributesTransfer {
        $shipmentTypesBackendApiAttributesTransfer->fromArray($shipmentTypeTransfer->toArray(), true);
        foreach ($shipmentTypeTransfer->getStoreRelationOrFail()->getStores() as $storeTransfer) {
            $shipmentTypesBackendApiAttributesTransfer->addStore($storeTransfer->getNameOrFail());
        }

        return $shipmentTypesBackendApiAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypesBackendApiAttributesTransfer $shipmentTypesBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeTransfer
     */
    public function mapShipmentTypesBackendApiAttributesTransferToShipmentTypeTransfer(
        ShipmentTypesBackendApiAttributesTransfer $shipmentTypesBackendApiAttributesTransfer,
        ShipmentTypeTransfer $shipmentTypeTransfer
    ): ShipmentTypeTransfer {
        $shipmentTypeTransfer->fromArray($shipmentTypesBackendApiAttributesTransfer->modifiedToArray(), true);

        if ($shipmentTypesBackendApiAttributesTransfer->getStores() !== []) {
            $storeRelationTransfer = $this->mapStoreNamesToStoreRelationTransfer(
                $shipmentTypesBackendApiAttributesTransfer->getStores(),
                new StoreRelationTransfer(),
            );

            $shipmentTypeTransfer->setStoreRelation($storeRelationTransfer);
        }

        return $shipmentTypeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueFilterTransfer $glueFilterTransfer
     * @param \Generated\Shared\Transfer\ShipmentTypeConditionsTransfer $shipmentTypeConditionsTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeConditionsTransfer
     */
    public function mapGlueFilterTransferToShipmentTypeConditionsTransfer(
        GlueFilterTransfer $glueFilterTransfer,
        ShipmentTypeConditionsTransfer $shipmentTypeConditionsTransfer
    ): ShipmentTypeConditionsTransfer {
        if ($glueFilterTransfer->getFieldOrFail() === ShipmentTypesBackendApiAttributesTransfer::IS_ACTIVE) {
            return $shipmentTypeConditionsTransfer->setIsActive(
                $this->getBoolValue($glueFilterTransfer),
            );
        }

        foreach (static::SHIPMENT_TYPES_ATTRIBUTES_SHIPMENT_TYPE_CONDITIONS_FIELD_MAP as $shipmentTypeAttributeField => $shipmentTypeConditionsField) {
            if ($glueFilterTransfer->getFieldOrFail() === $shipmentTypeAttributeField) {
                $setterMethod = $this->getSetterMethod($shipmentTypeConditionsField);
                $shipmentTypeConditionsField = $this->getArrayValue($glueFilterTransfer);

                return $shipmentTypeConditionsTransfer->$setterMethod($shipmentTypeConditionsField);
            }
        }

        return $shipmentTypeConditionsTransfer;
    }

    /**
     * @param string $fieldName
     *
     * @return string
     */
    protected function getSetterMethod(string $fieldName): string
    {
        return 'set' . ucfirst($fieldName);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueFilterTransfer $glueFilterTransfer
     *
     * @return list<string>
     */
    protected function getArrayValue(GlueFilterTransfer $glueFilterTransfer): array
    {
        /** @var array<string>|string $filterValue */
        $filterValue = $glueFilterTransfer->getValueOrFail();
        if (is_array($filterValue)) {
            return $filterValue;
        }

        return [$filterValue];
    }

    /**
     * @param \Generated\Shared\Transfer\GlueFilterTransfer $glueFilterTransfer
     *
     * @return bool
     */
    protected function getBoolValue(GlueFilterTransfer $glueFilterTransfer): bool
    {
        return filter_var($glueFilterTransfer->getValueOrFail(), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @param list<string> $storeNames
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    protected function mapStoreNamesToStoreRelationTransfer(
        array $storeNames,
        StoreRelationTransfer $storeRelationTransfer
    ): StoreRelationTransfer {
        foreach ($storeNames as $storeName) {
            $storeRelationTransfer->addStores(
                (new StoreTransfer())->setName($storeName),
            );
        }

        return $storeRelationTransfer;
    }
}
