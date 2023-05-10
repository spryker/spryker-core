<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\ServicePointServiceCollectionResponseTransfer;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\ServicePointServiceValidatorRuleInterface;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\TerminationAwareValidatorRuleInterface;

class ServicePointServiceValidator implements ServicePointServiceValidatorInterface
{
    /**
     * @var list<\Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\ServicePointServiceValidatorRuleInterface>
     */
    protected array $servicePointServiceValidatorRules;

    /**
     * @param list<\Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\ServicePointServiceValidatorRuleInterface> $servicePointServiceValidatorRules
     */
    public function __construct(array $servicePointServiceValidatorRules)
    {
        $this->servicePointServiceValidatorRules = $servicePointServiceValidatorRules;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointServiceCollectionResponseTransfer $servicePointServiceCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointServiceCollectionResponseTransfer
     */
    public function validate(
        ServicePointServiceCollectionResponseTransfer $servicePointServiceCollectionResponseTransfer
    ): ServicePointServiceCollectionResponseTransfer {
        foreach ($this->servicePointServiceValidatorRules as $servicePointServiceValidatorRule) {
            /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointServiceTransfer> $servicePointServiceTransfers */
            $servicePointServiceTransfers = $servicePointServiceCollectionResponseTransfer->getServicePointServices();
            $errorCollectionTransfer = $servicePointServiceValidatorRule->validate($servicePointServiceTransfers);

            /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $initialErrorTransfers */
            $initialErrorTransfers = $servicePointServiceCollectionResponseTransfer->getErrors();

            /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $postValidationErrorTransfers */
            $postValidationErrorTransfers = $errorCollectionTransfer->getErrors();

            $servicePointServiceCollectionResponseTransfer = $this->mergeErrors(
                $servicePointServiceCollectionResponseTransfer,
                $errorCollectionTransfer,
            );

            if (
                $this->isValidationTerminated(
                    $servicePointServiceValidatorRule,
                    $initialErrorTransfers,
                    $postValidationErrorTransfers,
                )
            ) {
                break;
            }
        }

        return $servicePointServiceCollectionResponseTransfer;
    }

    /**
     * @param \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService\ServicePointServiceValidatorRuleInterface $servicePointServiceValidatorRule
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $initialErrorTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $postValidationErrorTransfers
     *
     * @return bool
     */
    protected function isValidationTerminated(
        ServicePointServiceValidatorRuleInterface $servicePointServiceValidatorRule,
        ArrayObject $initialErrorTransfers,
        ArrayObject $postValidationErrorTransfers
    ): bool {
        if (!$servicePointServiceValidatorRule instanceof TerminationAwareValidatorRuleInterface) {
            return false;
        }

        return $servicePointServiceValidatorRule->isTerminated($initialErrorTransfers, $postValidationErrorTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointServiceCollectionResponseTransfer $servicePointServiceCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointServiceCollectionResponseTransfer
     */
    protected function mergeErrors(
        ServicePointServiceCollectionResponseTransfer $servicePointServiceCollectionResponseTransfer,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): ServicePointServiceCollectionResponseTransfer {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $servicePointServiceCollectionResponseErrorTransfers */
        $servicePointServiceCollectionResponseErrorTransfers = $servicePointServiceCollectionResponseTransfer->getErrors();

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorCollectionErrorTransfers */
        $errorCollectionErrorTransfers = $errorCollectionTransfer->getErrors();

        $mergedErrorTransfers = array_merge(
            $servicePointServiceCollectionResponseErrorTransfers->getArrayCopy(),
            $errorCollectionErrorTransfers->getArrayCopy(),
        );

        return $servicePointServiceCollectionResponseTransfer->setErrors(
            new ArrayObject($mergedErrorTransfers),
        );
    }
}
