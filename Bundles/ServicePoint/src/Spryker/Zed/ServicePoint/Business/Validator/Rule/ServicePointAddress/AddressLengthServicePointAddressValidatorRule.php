<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\ServicePoint\Business\Validator\Util\ErrorAdderInterface;

class AddressLengthServicePointAddressValidatorRule implements ServicePointAddressValidatorRuleInterface
{
    /**
     * @var int
     */
    protected const ADDRESS_MIN_LENGTH = 1;

    /**
     * @var int
     */
    protected const ADDRESS_MAX_LENGTH = 255;

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_ADDRESS1_WRONG_LENGTH = 'service_point.validation.service_point_address_address1_wrong_length';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_ADDRESS2_WRONG_LENGTH = 'service_point.validation.service_point_address_address2_wrong_length';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_ADDRESS3_WRONG_LENGTH = 'service_point.validation.service_point_address_address3_wrong_length';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_MIN = '%min%';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_MAX = '%max%';

    /**
     * @var \Spryker\Zed\ServicePoint\Business\Validator\Util\ErrorAdderInterface
     */
    protected ErrorAdderInterface $errorAdder;

    /**
     * @param \Spryker\Zed\ServicePoint\Business\Validator\Util\ErrorAdderInterface $errorAdder
     */
    public function __construct(ErrorAdderInterface $errorAdder)
    {
        $this->errorAdder = $errorAdder;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointAddressTransfer> $servicePointAddressTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $servicePointAddressTransfers): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();

        foreach ($servicePointAddressTransfers as $entityIdentifier => $servicePointAddressTransfer) {
            if (!$this->isServicePointAddressAttributeLengthValid($servicePointAddressTransfer->getAddress1OrFail())) {
                $this->addError($errorCollectionTransfer, $entityIdentifier, static::GLOSSARY_KEY_VALIDATION_ADDRESS1_WRONG_LENGTH);
            }

            if (!$this->isServicePointAddressAttributeLengthValid($servicePointAddressTransfer->getAddress2OrFail())) {
                $this->addError($errorCollectionTransfer, $entityIdentifier, static::GLOSSARY_KEY_VALIDATION_ADDRESS2_WRONG_LENGTH);
            }

            if ($servicePointAddressTransfer->getAddress3() === null) {
                continue;
            }

            if (!$this->isServicePointAddressAttributeLengthValid($servicePointAddressTransfer->getAddress3OrFail())) {
                $this->addError($errorCollectionTransfer, $entityIdentifier, static::GLOSSARY_KEY_VALIDATION_ADDRESS3_WRONG_LENGTH);
            }
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $initialErrorTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $postValidationErrorTransfers
     *
     * @return bool
     */
    public function isTerminated(
        ArrayObject $initialErrorTransfers,
        ArrayObject $postValidationErrorTransfers
    ): bool {
        return $postValidationErrorTransfers->count() > $initialErrorTransfers->count();
    }

    /**
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     * @param string $entityIdentifier
     * @param string $glossaryKey
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    protected function addError(
        ErrorCollectionTransfer $errorCollectionTransfer,
        string $entityIdentifier,
        string $glossaryKey
    ): ErrorCollectionTransfer {
        return $this->errorAdder->addError(
            $errorCollectionTransfer,
            $entityIdentifier,
            $glossaryKey,
            [
                static::GLOSSARY_KEY_PARAMETER_MIN => static::ADDRESS_MIN_LENGTH,
                static::GLOSSARY_KEY_PARAMETER_MAX => static::ADDRESS_MAX_LENGTH,
            ],
        );
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    protected function isServicePointAddressAttributeLengthValid(string $value): bool
    {
        return mb_strlen($value) >= static::ADDRESS_MIN_LENGTH
            && mb_strlen($value) <= static::ADDRESS_MAX_LENGTH;
    }
}
