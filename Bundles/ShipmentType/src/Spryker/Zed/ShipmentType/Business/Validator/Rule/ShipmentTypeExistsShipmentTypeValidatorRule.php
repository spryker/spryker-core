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
use Spryker\Zed\ShipmentType\Business\Validator\ErrorCreator\ValidationErrorCreatorInterface;
use Spryker\Zed\ShipmentType\Persistence\ShipmentTypeRepositoryInterface;

class ShipmentTypeExistsShipmentTypeValidatorRule implements ShipmentTypeValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_ENTITY_NOT_FOUND = 'shipment_type.validation.shipment_type_entity_not_found';

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
    public function __construct(
        ShipmentTypeRepositoryInterface $shipmentTypeRepository,
        ValidationErrorCreatorInterface $validationErrorCreator
    ) {
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
        $shipmentTypeUuids = $this->extractShipmentTypeUuidsFromShipmentTypeTransfers($shipmentTypeTransfers);
        $shipmentTypeCriteriaTransfer = $this->createShipmentTypeCriteriaTransfer($shipmentTypeUuids);
        $shipmentTypeCollectionTransfer = $this->shipmentTypeRepository->getShipmentTypeCollection($shipmentTypeCriteriaTransfer);

        if ($shipmentTypeTransfers->count() === $shipmentTypeCollectionTransfer->getShipmentTypes()->count()) {
            return $errorCollectionTransfer;
        }

        $existingShipmentTypeUuids = $this->extractShipmentTypeUuidsFromShipmentTypeTransfers($shipmentTypeCollectionTransfer->getShipmentTypes());
        foreach ($shipmentTypeTransfers as $entityIdentifier => $shipmentTypeTransfer) {
            if (isset($existingShipmentTypeUuids[$shipmentTypeTransfer->getUuidOrFail()])) {
                continue;
            }

            $errorCollectionTransfer->addError(
                $this->validationErrorCreator->createValidationError(
                    $entityIdentifier,
                    static::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_ENTITY_NOT_FOUND,
                ),
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfers
     *
     * @return array<string, string>
     */
    protected function extractShipmentTypeUuidsFromShipmentTypeTransfers(ArrayObject $shipmentTypeTransfers): array
    {
        $shipmentTypeUuids = [];
        foreach ($shipmentTypeTransfers as $shipmentTypeTransfer) {
            $shipmentTypeUuids[$shipmentTypeTransfer->getUuidOrFail()] = $shipmentTypeTransfer->getUuidOrFail();
        }

        return $shipmentTypeUuids;
    }

    /**
     * @param array<string, string> $shipmentTypeUuids
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer
     */
    protected function createShipmentTypeCriteriaTransfer(array $shipmentTypeUuids): ShipmentTypeCriteriaTransfer
    {
        $shipmentTypeConditionsTransfer = (new ShipmentTypeConditionsTransfer())->setUuids($shipmentTypeUuids);

        return (new ShipmentTypeCriteriaTransfer())->setShipmentTypeConditions($shipmentTypeConditionsTransfer);
    }
}
