<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationProvider;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationProviderConditionsTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCriteriaTransfer;
use Generated\Shared\Transfer\PushNotificationProviderTransfer;
use Spryker\Zed\PushNotification\Business\Validator\Util\ErrorAdderInterface;
use Spryker\Zed\PushNotification\Persistence\PushNotificationRepositoryInterface;

class NameExistencePushNotificationProviderValidatorRule implements PushNotificationProviderValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NAME_EXISTS = 'push_notification.validation.push_notification_provider_name_exists';

    /**
     * @var \Spryker\Zed\PushNotification\Persistence\PushNotificationRepositoryInterface
     */
    protected PushNotificationRepositoryInterface $pushNotificationRepository;

    /**
     * @var \Spryker\Zed\PushNotification\Business\Validator\Util\ErrorAdderInterface
     */
    protected ErrorAdderInterface $errorAdder;

    /**
     * @param \Spryker\Zed\PushNotification\Persistence\PushNotificationRepositoryInterface $pushNotificationRepository
     * @param \Spryker\Zed\PushNotification\Business\Validator\Util\ErrorAdderInterface $errorAdder
     */
    public function __construct(
        PushNotificationRepositoryInterface $pushNotificationRepository,
        ErrorAdderInterface $errorAdder
    ) {
        $this->pushNotificationRepository = $pushNotificationRepository;
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
            if ($this->hasPushNotificationProviderWithName($pushNotificationProviderTransfer)) {
                $this->errorAdder->addError(
                    $errorCollectionTransfer,
                    $entityIdentifier,
                    static::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NAME_EXISTS,
                );
            }
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationProviderTransfer $pushNotificationProviderTransfer
     *
     * @return bool
     */
    protected function hasPushNotificationProviderWithName(PushNotificationProviderTransfer $pushNotificationProviderTransfer): bool
    {
        $pushNotificationProviderConditionsTransfer = (new PushNotificationProviderConditionsTransfer())
            ->addName($pushNotificationProviderTransfer->getNameOrFail());

        if ($pushNotificationProviderTransfer->getUuid()) {
            $pushNotificationProviderConditionsTransfer
                ->addUuid($pushNotificationProviderTransfer->getUuidOrFail())
                ->setIsUuidsConditionInversed(true);
        }

        $pushNotificationProviderCriteriaTransfer = (new PushNotificationProviderCriteriaTransfer())
            ->setPushNotificationProviderConditions($pushNotificationProviderConditionsTransfer);

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\PushNotificationProviderTransfer> $pushNotificationProviderTransfers */
        $pushNotificationProviderTransfers = $this->pushNotificationRepository
            ->getPushNotificationProviderCollection($pushNotificationProviderCriteriaTransfer)
            ->getPushNotificationProviders();

        return $pushNotificationProviderTransfers->count() > 0;
    }
}
