<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationSubscription;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionTransfer;
use Spryker\Zed\PushNotification\Business\Creator\ErrorCreatorInterface;
use Spryker\Zed\PushNotification\PushNotificationConfig;

class PushNotificationSubscriptionGroupNameAllowedValidatorRule implements PushNotificationSubscriptionValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_WRONG_GROUP_NAME = 'push_notification.validation.error.wrong_group_name';

    /**
     * @var \Spryker\Zed\PushNotification\PushNotificationConfig
     */
    protected PushNotificationConfig $pushNotificationConfig;

    /**
     * @var \Spryker\Zed\PushNotification\Business\Creator\ErrorCreatorInterface
     */
    protected ErrorCreatorInterface $errorCreator;

    /**
     * @param \Spryker\Zed\PushNotification\PushNotificationConfig $pushNotificationConfig
     * @param \Spryker\Zed\PushNotification\Business\Creator\ErrorCreatorInterface $errorCreator
     */
    public function __construct(
        PushNotificationConfig $pushNotificationConfig,
        ErrorCreatorInterface $errorCreator
    ) {
        $this->pushNotificationConfig = $pushNotificationConfig;
        $this->errorCreator = $errorCreator;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validateCollection(
        ArrayObject $pushNotificationSubscriptionTransfers
    ): ErrorCollectionTransfer {
        $errorCollectionTransfer = new ErrorCollectionTransfer();
        if ($this->pushNotificationConfig->getGroupNameAllowList() === []) {
            return $errorCollectionTransfer;
        }

        foreach ($pushNotificationSubscriptionTransfers as $i => $pushNotificationSubscriptionTransfer) {
            $errorCollectionTransfer = $this->validatePushNotificationSubscription(
                $pushNotificationSubscriptionTransfer,
                $errorCollectionTransfer,
                (string)$i,
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     * @param string $pushNotificationSubscriptionIdentifier
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validatePushNotificationSubscription(
        PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer,
        ErrorCollectionTransfer $errorCollectionTransfer,
        string $pushNotificationSubscriptionIdentifier
    ): ErrorCollectionTransfer {
        $groupTransfer = $pushNotificationSubscriptionTransfer->getGroup();
        if (!$groupTransfer) {
            $errorTransfer = $this->errorCreator->createErrorTransfer(
                $pushNotificationSubscriptionIdentifier,
                static::GLOSSARY_KEY_VALIDATION_WRONG_GROUP_NAME,
            );

            return $errorCollectionTransfer->addError($errorTransfer);
        }

        $groupName = $groupTransfer->getName();
        if (!$groupName || !in_array($groupName, $this->pushNotificationConfig->getGroupNameAllowList(), true)) {
            $errorTransfer = $this->errorCreator->createErrorTransfer(
                $pushNotificationSubscriptionIdentifier,
                static::GLOSSARY_KEY_VALIDATION_WRONG_GROUP_NAME,
            );

            return $errorCollectionTransfer->addError($errorTransfer);
        }

        return $errorCollectionTransfer;
    }
}
