<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationProvider;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\PushNotification\Business\Validator\Rules\TerminationAwareValidatorRuleInterface;
use Spryker\Zed\PushNotification\Business\Validator\Util\ErrorAdderInterface;

class NameLengthPushNotificationProviderValidatorRule implements PushNotificationProviderValidatorRuleInterface, TerminationAwareValidatorRuleInterface
{
    /**
     * @var int
     */
    protected const PUSH_NOTIFICATION_PROVIDER_NAME_MIN_LENGTH = 1;

    /**
     * @var int
     */
    protected const PUSH_NOTIFICATION_PROVIDER_NAME_MAX_LENGTH = 255;

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NAME_WRONG_LENGTH = 'push_notification.validation.push_notification_provider_name_wrong_length';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_MIN = '%min%';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_MAX = '%max%';

    /**
     * @var \Spryker\Zed\PushNotification\Business\Validator\Util\ErrorAdderInterface
     */
    protected ErrorAdderInterface $errorAdder;

    /**
     * @param \Spryker\Zed\PushNotification\Business\Validator\Util\ErrorAdderInterface $errorAdder
     */
    public function __construct(ErrorAdderInterface $errorAdder)
    {
        $this->errorAdder = $errorAdder;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\PushNotificationProviderTransfer> $pushNotificationProviderTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $pushNotificationProviderTransfers): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();

        foreach ($pushNotificationProviderTransfers as $entityIdentifier => $pushNotificationProviderTransfer) {
            if (!$this->isPushNotificationProviderNameLengthValid($pushNotificationProviderTransfer->getNameOrFail())) {
                $this->errorAdder->addError(
                    $errorCollectionTransfer,
                    $entityIdentifier,
                    static::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NAME_WRONG_LENGTH,
                    [
                        static::GLOSSARY_KEY_PARAMETER_MIN => static::PUSH_NOTIFICATION_PROVIDER_NAME_MIN_LENGTH,
                        static::GLOSSARY_KEY_PARAMETER_MAX => static::PUSH_NOTIFICATION_PROVIDER_NAME_MAX_LENGTH,
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
    protected function isPushNotificationProviderNameLengthValid(string $name): bool
    {
        return mb_strlen($name) >= static::PUSH_NOTIFICATION_PROVIDER_NAME_MIN_LENGTH
            && mb_strlen($name) <= static::PUSH_NOTIFICATION_PROVIDER_NAME_MAX_LENGTH;
    }
}
