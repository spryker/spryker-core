<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationProvider;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\PushNotification\Business\Creator\ErrorCreatorInterface;
use Spryker\Zed\PushNotification\Business\Reader\PushNotificationProviderReaderInterface;

class PushNotificationProviderExistsValidatorRule implements PushNotificationProviderValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_ALREADY_EXISTS = 'push_notification.validation.error.push_notification_provider_already_exists';

    /**
     * @var \Spryker\Zed\PushNotification\Business\Reader\PushNotificationProviderReaderInterface
     */
    protected PushNotificationProviderReaderInterface $pushNotificationProviderReader;

    /**
     * @var \Spryker\Zed\PushNotification\Business\Creator\ErrorCreatorInterface
     */
    protected ErrorCreatorInterface $errorCreator;

    /**
     * @param \Spryker\Zed\PushNotification\Business\Reader\PushNotificationProviderReaderInterface $pushNotificationProviderReader
     * @param \Spryker\Zed\PushNotification\Business\Creator\ErrorCreatorInterface $errorCreator
     */
    public function __construct(
        PushNotificationProviderReaderInterface $pushNotificationProviderReader,
        ErrorCreatorInterface $errorCreator
    ) {
        $this->pushNotificationProviderReader = $pushNotificationProviderReader;
        $this->errorCreator = $errorCreator;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationProviderTransfer> $pushNotificationProviderTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validateCollection(ArrayObject $pushNotificationProviderTransfers): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();
        $pushNotificationProviderTransfersIndexedByName = $this
            ->pushNotificationProviderReader
            ->getPushNotificationProviderTransfersIndexedByName();
        foreach ($pushNotificationProviderTransfers as $i => $pushNotificationProviderTransfer) {
            if (!array_key_exists($pushNotificationProviderTransfer->getNameOrFail(), $pushNotificationProviderTransfersIndexedByName)) {
                continue;
            }
            $errorTransfer = $this->errorCreator->createErrorTransfer(
                (string)$i,
                static::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_ALREADY_EXISTS,
            );
            $errorCollectionTransfer->addError($errorTransfer);
        }

        return $errorCollectionTransfer;
    }
}
