<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer;
use Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\MerchantRelationValidatorRuleInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\TerminationAwareValidatorRuleInterface;

class MerchantRelationRequestValidator implements MerchantRelationRequestValidatorInterface
{
    /**
     * @var list<\Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\MerchantRelationValidatorRuleInterface>
     */
    protected array $validatorRules;

    /**
     * @param list<\Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\MerchantRelationValidatorRuleInterface> $validatorRules
     */
    public function __construct(array $validatorRules)
    {
        $this->validatorRules = $validatorRules;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer
     */
    public function validate(
        MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
    ): MerchantRelationRequestCollectionResponseTransfer {
        foreach ($this->validatorRules as $validatorRule) {
            /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantRelationRequestTransfer> $merchantRelationRequestTransfers */
            $merchantRelationRequestTransfers = $merchantRelationRequestCollectionResponseTransfer->getMerchantRelationRequests();
            $errorCollectionTransfer = $validatorRule->validate($merchantRelationRequestTransfers);

            /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $initialErrorTransfers */
            $initialErrorTransfers = $merchantRelationRequestCollectionResponseTransfer->getErrors();

            $merchantRelationRequestCollectionResponseTransfer = $this->mergeErrors(
                $merchantRelationRequestCollectionResponseTransfer,
                $errorCollectionTransfer,
            );

            if ($this->isValidationTerminated($validatorRule, $initialErrorTransfers)) {
                break;
            }
        }

        return $merchantRelationRequestCollectionResponseTransfer;
    }

    /**
     * @param \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\MerchantRelationValidatorRuleInterface $validatorRule
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $initialErrorTransfers
     *
     * @return bool
     */
    protected function isValidationTerminated(
        MerchantRelationValidatorRuleInterface $validatorRule,
        ArrayObject $initialErrorTransfers
    ): bool {
        if (!$validatorRule instanceof TerminationAwareValidatorRuleInterface) {
            return false;
        }

        return (bool)$initialErrorTransfers->count();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer
     */
    protected function mergeErrors(
        MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): MerchantRelationRequestCollectionResponseTransfer {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $merchantRelationRequestCollectionResponseErrorTransfers */
        $merchantRelationRequestCollectionResponseErrorTransfers = $merchantRelationRequestCollectionResponseTransfer->getErrors();

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorCollectionErrorTransfers */
        $errorCollectionErrorTransfers = $errorCollectionTransfer->getErrors();

        $mergedErrorTransfers = array_merge(
            $merchantRelationRequestCollectionResponseErrorTransfers->getArrayCopy(),
            $errorCollectionErrorTransfers->getArrayCopy(),
        );

        return $merchantRelationRequestCollectionResponseTransfer->setErrors(
            new ArrayObject($mergedErrorTransfers),
        );
    }
}
