<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\ServiceCollectionResponseTransfer;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\Service\ServiceValidatorRuleInterface;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\TerminationAwareValidatorRuleInterface;

class ServiceValidator implements ServiceValidatorInterface
{
    /**
     * @var list<\Spryker\Zed\ServicePoint\Business\Validator\Rule\Service\ServiceValidatorRuleInterface>
     */
    protected array $serviceValidatorRules;

    /**
     * @param list<\Spryker\Zed\ServicePoint\Business\Validator\Rule\Service\ServiceValidatorRuleInterface> $serviceValidatorRules
     */
    public function __construct(array $serviceValidatorRules)
    {
        $this->serviceValidatorRules = $serviceValidatorRules;
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceCollectionResponseTransfer $serviceCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceCollectionResponseTransfer
     */
    public function validate(
        ServiceCollectionResponseTransfer $serviceCollectionResponseTransfer
    ): ServiceCollectionResponseTransfer {
        foreach ($this->serviceValidatorRules as $serviceValidatorRule) {
            /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTransfer> $serviceTransfers */
            $serviceTransfers = $serviceCollectionResponseTransfer->getServices();
            $errorCollectionTransfer = $serviceValidatorRule->validate($serviceTransfers);

            /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $initialErrorTransfers */
            $initialErrorTransfers = $serviceCollectionResponseTransfer->getErrors();

            /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $postValidationErrorTransfers */
            $postValidationErrorTransfers = $errorCollectionTransfer->getErrors();

            $serviceCollectionResponseTransfer = $this->mergeErrors(
                $serviceCollectionResponseTransfer,
                $errorCollectionTransfer,
            );

            if (
                $this->isValidationTerminated(
                    $serviceValidatorRule,
                    $initialErrorTransfers,
                    $postValidationErrorTransfers,
                )
            ) {
                break;
            }
        }

        return $serviceCollectionResponseTransfer;
    }

    /**
     * @param \Spryker\Zed\ServicePoint\Business\Validator\Rule\Service\ServiceValidatorRuleInterface $serviceValidatorRule
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $initialErrorTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $postValidationErrorTransfers
     *
     * @return bool
     */
    protected function isValidationTerminated(
        ServiceValidatorRuleInterface $serviceValidatorRule,
        ArrayObject $initialErrorTransfers,
        ArrayObject $postValidationErrorTransfers
    ): bool {
        if (!$serviceValidatorRule instanceof TerminationAwareValidatorRuleInterface) {
            return false;
        }

        return $serviceValidatorRule->isTerminated($initialErrorTransfers, $postValidationErrorTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceCollectionResponseTransfer $serviceCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceCollectionResponseTransfer
     */
    protected function mergeErrors(
        ServiceCollectionResponseTransfer $serviceCollectionResponseTransfer,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): ServiceCollectionResponseTransfer {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $serviceCollectionResponseErrorTransfers */
        $serviceCollectionResponseErrorTransfers = $serviceCollectionResponseTransfer->getErrors();

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorCollectionErrorTransfers */
        $errorCollectionErrorTransfers = $errorCollectionTransfer->getErrors();

        $mergedErrorTransfers = array_merge(
            $serviceCollectionResponseErrorTransfers->getArrayCopy(),
            $errorCollectionErrorTransfers->getArrayCopy(),
        );

        return $serviceCollectionResponseTransfer->setErrors(
            new ArrayObject($mergedErrorTransfers),
        );
    }
}
