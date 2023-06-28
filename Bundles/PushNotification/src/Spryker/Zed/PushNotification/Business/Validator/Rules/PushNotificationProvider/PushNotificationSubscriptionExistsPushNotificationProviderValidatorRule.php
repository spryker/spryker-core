<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationProvider;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationProviderTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionConditionsTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCriteriaTransfer;
use Spryker\Zed\PushNotification\Business\Validator\Util\ErrorAdderInterface;
use Spryker\Zed\PushNotification\Persistence\PushNotificationRepositoryInterface;

class PushNotificationSubscriptionExistsPushNotificationProviderValidatorRule implements PushNotificationProviderValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_PUSH_NOTIFICATION_SUBSCRIPTION_EXISTS = 'push_notification.validation.push_notification_subscription_exists';

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
            if ($this->hasPushNotificationSubscription($pushNotificationProviderTransfer)) {
                $this->errorAdder->addError(
                    $errorCollectionTransfer,
                    $entityIdentifier,
                    static::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_PUSH_NOTIFICATION_SUBSCRIPTION_EXISTS,
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
    protected function hasPushNotificationSubscription(PushNotificationProviderTransfer $pushNotificationProviderTransfer): bool
    {
        $pushNotificationSubscriptionConditionsTransfer = (new PushNotificationSubscriptionConditionsTransfer())
            ->addIdPushNotificationProvider($pushNotificationProviderTransfer->getIdPushNotificationProviderOrFail());

        $pushNotificationSubscriptionCriteriaTransfer = (new PushNotificationSubscriptionCriteriaTransfer())
            ->setPushNotificationSubscriptionConditions($pushNotificationSubscriptionConditionsTransfer);

        return $this->pushNotificationRepository->pushNotificationSubscriptionExists($pushNotificationSubscriptionCriteriaTransfer);
    }
}
