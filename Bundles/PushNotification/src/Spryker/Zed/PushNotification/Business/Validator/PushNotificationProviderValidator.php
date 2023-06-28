<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCollectionResponseTransfer;
use Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationProvider\PushNotificationProviderValidatorRuleInterface;
use Spryker\Zed\PushNotification\Business\Validator\Rules\TerminationAwareValidatorRuleInterface;

class PushNotificationProviderValidator implements PushNotificationProviderValidatorInterface
{
    /**
     * @var list<\Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationProvider\PushNotificationProviderValidatorRuleInterface>
     */
    protected array $pushNotificationProviderValidatorRules;

    /**
     * @param list<\Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationProvider\PushNotificationProviderValidatorRuleInterface> $pushNotificationProviderValidatorRules
     */
    public function __construct(array $pushNotificationProviderValidatorRules)
    {
        $this->pushNotificationProviderValidatorRules = $pushNotificationProviderValidatorRules;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationProviderCollectionResponseTransfer $pushNotificationProviderCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderCollectionResponseTransfer
     */
    public function validate(
        PushNotificationProviderCollectionResponseTransfer $pushNotificationProviderCollectionResponseTransfer
    ): PushNotificationProviderCollectionResponseTransfer {
        foreach ($this->pushNotificationProviderValidatorRules as $pushNotificationProviderValidatorRule) {
            /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\PushNotificationProviderTransfer> $pushNotificationProviderTransfers */
            $pushNotificationProviderTransfers = $pushNotificationProviderCollectionResponseTransfer->getPushNotificationProviders();
            $errorCollectionTransfer = $pushNotificationProviderValidatorRule->validate($pushNotificationProviderTransfers);

            /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $initialErrorTransfers */
            $initialErrorTransfers = $pushNotificationProviderCollectionResponseTransfer->getErrors();

            /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $postValidationErrorTransfers */
            $postValidationErrorTransfers = $errorCollectionTransfer->getErrors();

            $pushNotificationProviderCollectionResponseTransfer = $this->mergeErrors(
                $pushNotificationProviderCollectionResponseTransfer,
                $errorCollectionTransfer,
            );

            if ($this->isValidationTerminated($pushNotificationProviderValidatorRule, $initialErrorTransfers, $postValidationErrorTransfers)) {
                break;
            }
        }

        return $pushNotificationProviderCollectionResponseTransfer;
    }

    /**
     * @param \Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationProvider\PushNotificationProviderValidatorRuleInterface $pushNotificationProviderValidatorRule
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $initialErrorTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $postValidationErrorTransfers
     *
     * @return bool
     */
    protected function isValidationTerminated(
        PushNotificationProviderValidatorRuleInterface $pushNotificationProviderValidatorRule,
        ArrayObject $initialErrorTransfers,
        ArrayObject $postValidationErrorTransfers
    ): bool {
        if (!$pushNotificationProviderValidatorRule instanceof TerminationAwareValidatorRuleInterface) {
            return false;
        }

        return $pushNotificationProviderValidatorRule->isTerminated($initialErrorTransfers, $postValidationErrorTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationProviderCollectionResponseTransfer $pushNotificationProviderCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderCollectionResponseTransfer
     */
    protected function mergeErrors(
        PushNotificationProviderCollectionResponseTransfer $pushNotificationProviderCollectionResponseTransfer,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): PushNotificationProviderCollectionResponseTransfer {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $pushNotificationProviderCollectionResponseErrorTransfers */
        $pushNotificationProviderCollectionResponseErrorTransfers = $pushNotificationProviderCollectionResponseTransfer->getErrors();

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorCollectionErrorTransfers */
        $errorCollectionErrorTransfers = $errorCollectionTransfer->getErrors();

        $mergedErrorTransfers = array_merge(
            $pushNotificationProviderCollectionResponseErrorTransfers->getArrayCopy(),
            $errorCollectionErrorTransfers->getArrayCopy(),
        );

        return $pushNotificationProviderCollectionResponseTransfer->setErrors(
            new ArrayObject($mergedErrorTransfers),
        );
    }
}
