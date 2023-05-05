<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Business\Validator\Rule;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\ShipmentType\Business\Validator\ErrorCreator\ValidationErrorCreatorInterface;

class ShipmentTypeKeyUniqueShipmentTypeValidatorRule implements ShipmentTypeValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_KEY_IS_NOT_UNIQUE = 'shipment_type.validation.shipment_type_key_is_not_unique';

    /**
     * @var \Spryker\Zed\ShipmentType\Business\Validator\ErrorCreator\ValidationErrorCreatorInterface
     */
    protected ValidationErrorCreatorInterface $validationErrorCreator;

    /**
     * @param \Spryker\Zed\ShipmentType\Business\Validator\ErrorCreator\ValidationErrorCreatorInterface $validationErrorCreator
     */
    public function __construct(ValidationErrorCreatorInterface $validationErrorCreator)
    {
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
        $shipmentTypeKeysIndex = [];
        foreach ($shipmentTypeTransfers as $entityIdentifier => $shipmentTypeTransfer) {
            $shipmentTypeKey = $shipmentTypeTransfer->getKeyOrFail();
            if (!isset($shipmentTypeKeysIndex[$shipmentTypeKey])) {
                $shipmentTypeKeysIndex[$shipmentTypeKey] = true;

                continue;
            }

            $errorCollectionTransfer->addError(
                $this->validationErrorCreator->createValidationError(
                    $entityIdentifier,
                    static::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_KEY_IS_NOT_UNIQUE,
                ),
            );
        }

        return $errorCollectionTransfer;
    }
}
