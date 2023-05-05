<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Business\Validator\Rule;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\ShipmentType\Business\Validator\ErrorCreator\ValidationErrorCreatorInterface;

class ShipmentTypeKeyLengthShipmentTypeValidatorRule implements ShipmentTypeValidatorRuleInterface
{
    /**
     * @var int
     */
    protected const SHIPMENT_TYPE_KEY_LENGTH_MIN = 1;

    /**
     * @var int
     */
    protected const SHIPMENT_TYPE_KEY_LENGTH_MAX = 255;

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_KEY_INVALID_LENGTH = 'shipment_type.validation.shipment_type_key_invalid_length';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_MIN = '%min%';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_MAX = '%max%';

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
        foreach ($shipmentTypeTransfers as $entityIdentifier => $shipmentTypeTransfer) {
            if ($this->isShipmentTypeKeyLengthValid($shipmentTypeTransfer->getKeyOrFail())) {
                continue;
            }

            $errorCollectionTransfer->addError(
                $this->validationErrorCreator->createValidationError(
                    $entityIdentifier,
                    static::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_KEY_INVALID_LENGTH,
                    [
                        static::GLOSSARY_KEY_PARAMETER_MIN => static::SHIPMENT_TYPE_KEY_LENGTH_MIN,
                        static::GLOSSARY_KEY_PARAMETER_MAX => static::SHIPMENT_TYPE_KEY_LENGTH_MAX,
                    ],
                ),
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    protected function isShipmentTypeKeyLengthValid(string $key): bool
    {
        $keyLength = mb_strlen($key);

        return $keyLength >= static::SHIPMENT_TYPE_KEY_LENGTH_MIN && $keyLength <= static::SHIPMENT_TYPE_KEY_LENGTH_MAX;
    }
}
