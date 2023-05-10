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
            /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointAddressTransfer> $servicePointAddressTransfers */
            $servicePointAddressTransfers = $servicePointAddressCollectionResponseTransfer->getServicePointAddresses();
            $errorCollectionTransfer = $servicePointAddressValidatorRule->validate($servicePointAddressTransfers);

            /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $initialErrorTransfers */
            $initialErrorTransfers = $servicePointAddressCollectionResponseTransfer->getErrors();

            /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $postValidationErrorTransfers */
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
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $servicePointAddressCollectionResponseErrorTransfers */
        $servicePointAddressCollectionResponseErrorTransfers = $servicePointAddressCollectionResponseTransfer->getErrors();

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorCollectionErrorTransfers */
        $errorCollectionErrorTransfers = $errorCollectionTransfer->getErrors();

        $mergedErrorTransfers = array_merge(
            $servicePointAddressCollectionResponseErrorTransfers->getArrayCopy(),
            $errorCollectionErrorTransfers->getArrayCopy(),
        );

        return $servicePointAddressCollectionResponseTransfer->setErrors(
            new ArrayObject($mergedErrorTransfers),
        );
    }
}
