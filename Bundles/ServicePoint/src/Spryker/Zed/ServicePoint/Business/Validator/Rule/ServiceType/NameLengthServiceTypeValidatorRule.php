<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\TerminationAwareValidatorRuleInterface;
use Spryker\Zed\ServicePoint\Business\Validator\Util\ErrorAdderInterface;

class NameLengthServiceTypeValidatorRule implements ServiceTypeValidatorRuleInterface, TerminationAwareValidatorRuleInterface
{
    /**
     * @var int
     */
    protected const SERVICE_TYPE_NAME_MIN_LENGTH = 1;

    /**
     * @var int
     */
    protected const SERVICE_TYPE_NAME_MAX_LENGTH = 255;

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_NAME_WRONG_LENGTH = 'service_point.validation.service_type_name_wrong_length';

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
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTypeTransfer> $serviceTypeTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $serviceTypeTransfers): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();
        foreach ($serviceTypeTransfers as $entityIdentifier => $serviceTypeTransfer) {
            if (!$this->isServiceTypeNameLengthValid($serviceTypeTransfer->getNameOrFail())) {
                $this->errorAdder->addError(
                    $errorCollectionTransfer,
                    $entityIdentifier,
                    static::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_NAME_WRONG_LENGTH,
                    [
                        static::GLOSSARY_KEY_PARAMETER_MIN => static::SERVICE_TYPE_NAME_MIN_LENGTH,
                        static::GLOSSARY_KEY_PARAMETER_MAX => static::SERVICE_TYPE_NAME_MAX_LENGTH,
                    ],
                );
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
     * @param string $name
     *
     * @return bool
     */
    protected function isServiceTypeNameLengthValid(string $name): bool
    {
        return mb_strlen($name) >= static::SERVICE_TYPE_NAME_MIN_LENGTH
            && mb_strlen($name) <= static::SERVICE_TYPE_NAME_MAX_LENGTH;
    }
}
