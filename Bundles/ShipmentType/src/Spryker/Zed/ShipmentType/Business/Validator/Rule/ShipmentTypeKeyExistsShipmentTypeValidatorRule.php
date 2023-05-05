<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Business\Validator\Rule;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeConditionsTransfer;
use Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Spryker\Zed\ShipmentType\Business\Validator\ErrorCreator\ValidationErrorCreatorInterface;
use Spryker\Zed\ShipmentType\Persistence\ShipmentTypeRepositoryInterface;

class ShipmentTypeKeyExistsShipmentTypeValidatorRule implements ShipmentTypeValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_KEY_EXISTS = 'shipment_type.validation.shipment_type_key_exists';

    /**
     * @var \Spryker\Zed\ShipmentType\Persistence\ShipmentTypeRepositoryInterface
     */
    protected ShipmentTypeRepositoryInterface $shipmentTypeRepository;

    /**
     * @var \Spryker\Zed\ShipmentType\Business\Validator\ErrorCreator\ValidationErrorCreatorInterface
     */
    protected ValidationErrorCreatorInterface $validationErrorCreator;

    /**
     * @param \Spryker\Zed\ShipmentType\Persistence\ShipmentTypeRepositoryInterface $shipmentTypeRepository
     * @param \Spryker\Zed\ShipmentType\Business\Validator\ErrorCreator\ValidationErrorCreatorInterface $validationErrorCreator
     */
    public function __construct(ShipmentTypeRepositoryInterface $shipmentTypeRepository, ValidationErrorCreatorInterface $validationErrorCreator)
    {
        $this->shipmentTypeRepository = $shipmentTypeRepository;
        $this->validationErrorCreator = $validationErrorCreator;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfers
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $shipmentTypeTransfers, ErrorCollectionTransfer $errorCollectionTransfer): ErrorCollectionTransfer
    {
        $shipmentTypeKeys = $this->extractShipmentTypeKeysFromShipmentTypeTransfers($shipmentTypeTransfers);
        $shipmentTypeCriteriaTransfer = $this->createShipmentTypeCriteriaTransfer($shipmentTypeKeys);
        $shipmentTypeCollectionTransfer = $this->shipmentTypeRepository->getShipmentTypeCollection($shipmentTypeCriteriaTransfer);
        if ($shipmentTypeCollectionTransfer->getShipmentTypes()->count() === 0) {
            return $errorCollectionTransfer;
        }

        $persistedShipmentTypeKeysIndexedByUuid = $this->getShipmentTypeKeysIndexedByShipmentTypeUuid(
            $shipmentTypeCollectionTransfer->getShipmentTypes(),
        );
        foreach ($shipmentTypeTransfers as $entityIdentifier => $shipmentTypeTransfer) {
            if (!$this->shipmentTypeKeyExists($shipmentTypeTransfer, $persistedShipmentTypeKeysIndexedByUuid)) {
                continue;
            }

            $errorCollectionTransfer->addError(
                $this->validationErrorCreator->createValidationError(
                    $entityIdentifier,
                    static::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_KEY_EXISTS,
                ),
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     * @param array<string, string> $persistedShipmentTypeKeysIndexedByUuid
     *
     * @return bool
     */
    protected function shipmentTypeKeyExists(ShipmentTypeTransfer $shipmentTypeTransfer, array $persistedShipmentTypeKeysIndexedByUuid): bool
    {
        if (!in_array($shipmentTypeTransfer->getKeyOrFail(), $persistedShipmentTypeKeysIndexedByUuid, true)) {
            return false;
        }

        if ($shipmentTypeTransfer->getUuid() === null) {
            return true;
        }

        $shipmentTypeKey = $persistedShipmentTypeKeysIndexedByUuid[$shipmentTypeTransfer->getUuidOrFail()] ?? null;
        if ($shipmentTypeTransfer->getKeyOrFail() === $shipmentTypeKey) {
            return false;
        }

        return true;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfers
     *
     * @return list<string>
     */
    protected function extractShipmentTypeKeysFromShipmentTypeTransfers(ArrayObject $shipmentTypeTransfers): array
    {
        $shipmentTypeKeys = [];
        foreach ($shipmentTypeTransfers as $shipmentTypeTransfer) {
            $shipmentTypeKeys[] = $shipmentTypeTransfer->getKeyOrFail();
        }

        return $shipmentTypeKeys;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfers
     *
     * @return array<string, string>
     */
    protected function getShipmentTypeKeysIndexedByShipmentTypeUuid(ArrayObject $shipmentTypeTransfers): array
    {
        $indexedShipmentTypeKeys = [];
        foreach ($shipmentTypeTransfers as $shipmentTypeTransfer) {
            $indexedShipmentTypeKeys[$shipmentTypeTransfer->getUuidOrFail()] = $shipmentTypeTransfer->getKeyOrFail();
        }

        return $indexedShipmentTypeKeys;
    }

    /**
     * @param list<string> $shipmentTypeKeys
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer
     */
    protected function createShipmentTypeCriteriaTransfer(array $shipmentTypeKeys): ShipmentTypeCriteriaTransfer
    {
        $shipmentTypeConditionsTransfer = (new ShipmentTypeConditionsTransfer())->setKeys($shipmentTypeKeys);

        return (new ShipmentTypeCriteriaTransfer())->setShipmentTypeConditions($shipmentTypeConditionsTransfer);
    }
}
