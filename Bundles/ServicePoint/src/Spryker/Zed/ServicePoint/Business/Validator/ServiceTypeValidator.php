<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\ServiceTypeCollectionResponseTransfer;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType\ServiceTypeValidatorRuleInterface;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\TerminationAwareValidatorRuleInterface;

class ServiceTypeValidator implements ServiceTypeValidatorInterface
{
    /**
     * @var list<\Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType\ServiceTypeValidatorRuleInterface>
     */
    protected array $serviceTypeValidatorRules;

    /**
     * @param list<\Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType\ServiceTypeValidatorRuleInterface> $serviceTypeValidatorRules
     */
    public function __construct(array $serviceTypeValidatorRules)
    {
        $this->serviceTypeValidatorRules = $serviceTypeValidatorRules;
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceTypeCollectionResponseTransfer $serviceTypeCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypeCollectionResponseTransfer
     */
    public function validate(
        ServiceTypeCollectionResponseTransfer $serviceTypeCollectionResponseTransfer
    ): ServiceTypeCollectionResponseTransfer {
        foreach ($this->serviceTypeValidatorRules as $serviceTypeValidatorRule) {
            /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTypeTransfer> $serviceTypeTransfers */
            $serviceTypeTransfers = $serviceTypeCollectionResponseTransfer->getServiceTypes();
            $errorCollectionTransfer = $serviceTypeValidatorRule->validate($serviceTypeTransfers);

            /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $initialErrorTransfers */
            $initialErrorTransfers = $serviceTypeCollectionResponseTransfer->getErrors();

            /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $postValidationErrorTransfers */
            $postValidationErrorTransfers = $errorCollectionTransfer->getErrors();

            $serviceTypeCollectionResponseTransfer = $this->mergeErrors(
                $serviceTypeCollectionResponseTransfer,
                $errorCollectionTransfer,
            );

            if ($this->isValidationTerminated($serviceTypeValidatorRule, $initialErrorTransfers, $postValidationErrorTransfers)) {
                break;
            }
        }

        return $serviceTypeCollectionResponseTransfer;
    }

    /**
     * @param \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType\ServiceTypeValidatorRuleInterface $serviceTypeValidatorRule
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $initialErrorTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $postValidationErrorTransfers
     *
     * @return bool
     */
    protected function isValidationTerminated(
        ServiceTypeValidatorRuleInterface $serviceTypeValidatorRule,
        ArrayObject $initialErrorTransfers,
        ArrayObject $postValidationErrorTransfers
    ): bool {
        if (!$serviceTypeValidatorRule instanceof TerminationAwareValidatorRuleInterface) {
            return false;
        }

        return $serviceTypeValidatorRule->isTerminated($initialErrorTransfers, $postValidationErrorTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceTypeCollectionResponseTransfer $serviceTypeCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypeCollectionResponseTransfer
     */
    protected function mergeErrors(
        ServiceTypeCollectionResponseTransfer $serviceTypeCollectionResponseTransfer,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): ServiceTypeCollectionResponseTransfer {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $serviceTypeCollectionResponseErrorTransfers */
        $serviceTypeCollectionResponseErrorTransfers = $serviceTypeCollectionResponseTransfer->getErrors();

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorCollectionErrorTransfers */
        $errorCollectionErrorTransfers = $errorCollectionTransfer->getErrors();

        $mergedErrorTransfers = array_merge(
            $serviceTypeCollectionResponseErrorTransfers->getArrayCopy(),
            $errorCollectionErrorTransfers->getArrayCopy(),
        );

        return $serviceTypeCollectionResponseTransfer->setErrors(
            new ArrayObject($mergedErrorTransfers),
        );
    }
}
