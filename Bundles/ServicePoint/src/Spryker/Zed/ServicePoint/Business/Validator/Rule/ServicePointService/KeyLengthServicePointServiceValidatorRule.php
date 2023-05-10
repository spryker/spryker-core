<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\TerminationAwareValidatorRuleInterface;
use Spryker\Zed\ServicePoint\Business\Validator\Util\ErrorAdderInterface;

class KeyLengthServicePointServiceValidatorRule implements ServicePointServiceValidatorRuleInterface, TerminationAwareValidatorRuleInterface
{
    /**
     * @var int
     */
    protected const SERVICE_POINT_SERVICE_KEY_MIN_LENGTH = 1;

    /**
     * @var int
     */
    protected const SERVICE_POINT_SERVICE_KEY_MAX_LENGTH = 255;

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_SERVICE_KEY_WRONG_LENGTH = 'service_point.validation.service_point_service_key_wrong_length';

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
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointServiceTransfer> $servicePointServiceTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $servicePointServiceTransfers): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();

        foreach ($servicePointServiceTransfers as $entityIdentifier => $servicePointServiceTransfer) {
            if (!$this->isServicePointServiceKeyLengthValid($servicePointServiceTransfer->getKeyOrFail())) {
                $this->errorAdder->addError(
                    $errorCollectionTransfer,
                    $entityIdentifier,
                    static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_SERVICE_KEY_WRONG_LENGTH,
                    [
                        static::GLOSSARY_KEY_PARAMETER_MIN => static::SERVICE_POINT_SERVICE_KEY_MIN_LENGTH,
                        static::GLOSSARY_KEY_PARAMETER_MAX => static::SERVICE_POINT_SERVICE_KEY_MAX_LENGTH,
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
     * @param string $key
     *
     * @return bool
     */
    protected function isServicePointServiceKeyLengthValid(string $key): bool
    {
        return mb_strlen($key) >= static::SERVICE_POINT_SERVICE_KEY_MIN_LENGTH
            && mb_strlen($key) <= static::SERVICE_POINT_SERVICE_KEY_MAX_LENGTH;
    }
}
