<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\ServicePointAddressCollectionResponseTransfer;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\ServicePointAddressValidatorRuleInterface;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\TerminationAwareValidatorRuleInterface;

class ServicePointAddressValidator implements ServicePointAddressValidatorInterface
{
    /**
     * @var list<\Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\ServicePointAddressValidatorRuleInterface>
     */
    protected array $servicePointAddressValidatorRules;

    /**
     * @param list<\Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\ServicePointAddressValidatorRuleInterface> $servicePointAddressValidatorRules
     */
    public function __construct(array $servicePointAddressValidatorRules)
    {
        $this->servicePointAddressValidatorRules = $servicePointAddressValidatorRules;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointAddressCollectionResponseTransfer $servicePointAddressCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointAddressCollectionResponseTransfer
     */
    public function validate(
        ServicePointAddressCollectionResponseTransfer $servicePointAddressCollectionResponseTransfer
    ): ServicePointAddressCollectionResponseTransfer {
        foreach ($this->servicePointAddressValidatorRules as $servicePointAddressValidatorRule) {
            $errorCollectionTransfer = $servicePointAddressValidatorRule->validate(
                $servicePointAddressCollectionResponseTransfer->getServicePointAddresses(),
            );

            $initialErrorTransfers = $servicePointAddressCollectionResponseTransfer->getErrors();
            $postValidationErrorTransfers = $errorCollectionTransfer->getErrors();

            $servicePointAddressCollectionResponseTransfer = $this->mergeErrors(
                $servicePointAddressCollectionResponseTransfer,
                $errorCollectionTransfer,
            );

            if ($this->isValidationTerminated($servicePointAddressValidatorRule, $initialErrorTransfers, $postValidationErrorTransfers)) {
                break;
            }
        }

        return $servicePointAddressCollectionResponseTransfer;
    }

    /**
     * @param \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\ServicePointAddressValidatorRuleInterface $servicePointAddressValidatorRule
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $initialErrorTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $postValidationErrorTransfers
     *
     * @return bool
     */
    protected function isValidationTerminated(
        ServicePointAddressValidatorRuleInterface $servicePointAddressValidatorRule,
        ArrayObject $initialErrorTransfers,
        ArrayObject $postValidationErrorTransfers
    ): bool {
        if (!$servicePointAddressValidatorRule instanceof TerminationAwareValidatorRuleInterface) {
            return false;
        }

        return $servicePointAddressValidatorRule->isTerminated($initialErrorTransfers, $postValidationErrorTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointAddressCollectionResponseTransfer $servicePointAddressCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointAddressCollectionResponseTransfer
     */
    protected function mergeErrors(
        ServicePointAddressCollectionResponseTransfer $servicePointAddressCollectionResponseTransfer,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): ServicePointAddressCollectionResponseTransfer {
        $mergedErrorTransfers = array_merge(
            $servicePointAddressCollectionResponseTransfer->getErrors()->getArrayCopy(),
            $errorCollectionTransfer->getErrors()->getArrayCopy(),
        );

        return $servicePointAddressCollectionResponseTransfer->setErrors(
            new ArrayObject($mergedErrorTransfers),
        );
    }
}
