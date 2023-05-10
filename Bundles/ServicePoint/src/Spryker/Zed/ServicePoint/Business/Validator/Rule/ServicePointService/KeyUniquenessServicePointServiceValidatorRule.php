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

class KeyUniquenessServicePointServiceValidatorRule implements ServicePointServiceValidatorRuleInterface, TerminationAwareValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_SERVICE_KEY_IS_NOT_UNIQUE = 'service_point.validation.service_point_service_key_is_not_unique';

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
        $keysIndex = [];

        foreach ($servicePointServiceTransfers as $entityIdentifier => $servicePointServiceTransfer) {
            $key = $servicePointServiceTransfer->getKeyOrFail();

            if (!isset($keysIndex[$key])) {
                $keysIndex[$key] = true;

                continue;
            }

            $this->errorAdder->addError(
                $errorCollectionTransfer,
                $entityIdentifier,
                static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_SERVICE_KEY_IS_NOT_UNIQUE,
            );
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
}
