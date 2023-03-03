<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotification;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationConditionsTransfer;
use Generated\Shared\Transfer\PushNotificationCriteriaTransfer;
use Spryker\Zed\PushNotification\Business\Creator\ErrorCreatorInterface;
use Spryker\Zed\PushNotification\Persistence\PushNotificationRepositoryInterface;

class PushNotificationExistsValidatorRule implements PushNotificationValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_NOT_FOUND = 'push_notification.validation.error.push_notification_not_found';

    /**
     * @var \Spryker\Zed\PushNotification\Persistence\PushNotificationRepositoryInterface
     */
    protected PushNotificationRepositoryInterface $pushNotificationRepository;

    /**
     * @var \Spryker\Zed\PushNotification\Business\Creator\ErrorCreatorInterface
     */
    protected ErrorCreatorInterface $errorCreator;

    /**
     * @param \Spryker\Zed\PushNotification\Persistence\PushNotificationRepositoryInterface $pushNotificationRepository
     * @param \Spryker\Zed\PushNotification\Business\Creator\ErrorCreatorInterface $errorCreator
     */
    public function __construct(
        PushNotificationRepositoryInterface $pushNotificationRepository,
        ErrorCreatorInterface $errorCreator
    ) {
        $this->pushNotificationRepository = $pushNotificationRepository;
        $this->errorCreator = $errorCreator;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationTransfer> $pushNotificationTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validateCollection(
        ArrayObject $pushNotificationTransfers
    ): ErrorCollectionTransfer {
        $persistedPushNotificationIds = $this->getPersistedPushNotificationIds($pushNotificationTransfers);
        $errorCollectionTransfer = new ErrorCollectionTransfer();
        foreach ($pushNotificationTransfers as $i => $pushNotificationTransfer) {
            $idPushNotification = $pushNotificationTransfer->getIdPushNotification();
            if (in_array($idPushNotification, $persistedPushNotificationIds)) {
                continue;
            }
            $errorTransfer = $this->errorCreator->createErrorTransfer(
                (string)$i,
                static::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_NOT_FOUND,
            );
            $errorCollectionTransfer->addError($errorTransfer);
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationTransfer> $pushNotificationTransfers
     *
     * @return array<int>
     */
    protected function getPushNotificationIds(ArrayObject $pushNotificationTransfers): array
    {
        $pushNotificationIds = [];
        foreach ($pushNotificationTransfers as $pushNotificationTransfer) {
            $idPushNotification = $pushNotificationTransfer->getIdPushNotification();
            if ($idPushNotification) {
                $pushNotificationIds[] = $idPushNotification;
            }
        }

        return $pushNotificationIds;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationTransfer> $pushNotificationTransfers
     *
     * @return array<int>
     */
    protected function getPersistedPushNotificationIds(ArrayObject $pushNotificationTransfers): array
    {
        $pushNotificationCriteriaTransfer = $this->createPushNotificationCriteriaTransfer($pushNotificationTransfers);
        $persistedPushNotificationTransfers = $this->pushNotificationRepository->getPushNotificationCollection(
            $pushNotificationCriteriaTransfer,
        );

        /**
         * @var \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationTransfer> $pushNotificationTransfers
         */
        $pushNotificationTransfers = $persistedPushNotificationTransfers->getPushNotifications();

        return $this->getPushNotificationIds($pushNotificationTransfers);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationTransfer> $pushNotificationTransfers
     *
     * @return \Generated\Shared\Transfer\PushNotificationCriteriaTransfer
     */
    protected function createPushNotificationCriteriaTransfer(ArrayObject $pushNotificationTransfers): PushNotificationCriteriaTransfer
    {
        $pushNotificationIds = $this->getPushNotificationIds($pushNotificationTransfers);

        return (new PushNotificationCriteriaTransfer())
            ->setPushNotificationConditions(
                (new PushNotificationConditionsTransfer())
                    ->setPushNotificationIds($pushNotificationIds),
            );
    }
}
