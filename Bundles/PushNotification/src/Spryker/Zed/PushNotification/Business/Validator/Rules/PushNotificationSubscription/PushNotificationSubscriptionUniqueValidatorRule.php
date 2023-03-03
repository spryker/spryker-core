<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationSubscription;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\PushNotificationGroupTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionConditionsTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCriteriaTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionTransfer;
use Spryker\Zed\PushNotification\Business\Creator\ErrorCreatorInterface;
use Spryker\Zed\PushNotification\Business\Generator\PushNotificationSubscriptionCheckSumGeneratorInterface;
use Spryker\Zed\PushNotification\Business\Reader\PushNotificationGroupReaderInterface;
use Spryker\Zed\PushNotification\Business\Reader\PushNotificationProviderReaderInterface;
use Spryker\Zed\PushNotification\Business\Reader\PushNotificationSubscriptionReaderInterface;

class PushNotificationSubscriptionUniqueValidatorRule implements PushNotificationSubscriptionValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_ALREADY_EXISTS = 'push_notification.validation.error.push_notification_already_exists';

    /**
     * @var \Spryker\Zed\PushNotification\Business\Reader\PushNotificationSubscriptionReaderInterface
     */
    protected PushNotificationSubscriptionReaderInterface $pushNotificationSubscriptionReader;

    /**
     * @var \Spryker\Zed\PushNotification\Business\Reader\PushNotificationProviderReaderInterface
     */
    protected PushNotificationProviderReaderInterface $pushNotificationProviderReader;

    /**
     * @var \Spryker\Zed\PushNotification\Business\Reader\PushNotificationGroupReaderInterface
     */
    protected PushNotificationGroupReaderInterface $pushNotificationGroupReader;

    /**
     * @var \Spryker\Zed\PushNotification\Business\Generator\PushNotificationSubscriptionCheckSumGeneratorInterface
     */
    protected PushNotificationSubscriptionCheckSumGeneratorInterface $pushNotificationSubscriptionCheckSumGenerator;

    /**
     * @var \Spryker\Zed\PushNotification\Business\Creator\ErrorCreatorInterface
     */
    protected ErrorCreatorInterface $errorCreator;

    /**
     * @param \Spryker\Zed\PushNotification\Business\Reader\PushNotificationSubscriptionReaderInterface $pushNotificationSubscriptionReader
     * @param \Spryker\Zed\PushNotification\Business\Reader\PushNotificationProviderReaderInterface $pushNotificationProviderReader
     * @param \Spryker\Zed\PushNotification\Business\Reader\PushNotificationGroupReaderInterface $pushNotificationGroupReader
     * @param \Spryker\Zed\PushNotification\Business\Generator\PushNotificationSubscriptionCheckSumGeneratorInterface $pushNotificationSubscriptionCheckSumGenerator
     * @param \Spryker\Zed\PushNotification\Business\Creator\ErrorCreatorInterface $errorCreator
     */
    public function __construct(
        PushNotificationSubscriptionReaderInterface $pushNotificationSubscriptionReader,
        PushNotificationProviderReaderInterface $pushNotificationProviderReader,
        PushNotificationGroupReaderInterface $pushNotificationGroupReader,
        PushNotificationSubscriptionCheckSumGeneratorInterface $pushNotificationSubscriptionCheckSumGenerator,
        ErrorCreatorInterface $errorCreator
    ) {
        $this->pushNotificationSubscriptionReader = $pushNotificationSubscriptionReader;
        $this->pushNotificationProviderReader = $pushNotificationProviderReader;
        $this->pushNotificationGroupReader = $pushNotificationGroupReader;
        $this->pushNotificationSubscriptionCheckSumGenerator = $pushNotificationSubscriptionCheckSumGenerator;
        $this->errorCreator = $errorCreator;
    }

    /**
     * /**
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validateCollection(ArrayObject $pushNotificationSubscriptionTransfers): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();
        foreach ($pushNotificationSubscriptionTransfers as $i => $pushNotificationSubscriptionTransfer) {
            if ($this->isUnique($pushNotificationSubscriptionTransfer)) {
                continue;
            }
            $errorTransfer = $this->errorCreator->createErrorTransfer(
                (string)$i,
                static::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_ALREADY_EXISTS,
            );
            $errorCollectionTransfer->addError($errorTransfer);
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     *
     * @return bool
     */
    protected function isUnique(PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer): bool
    {
        if ($this->isNewGroup($pushNotificationSubscriptionTransfer->getGroupOrFail())) {
            return true;
        }

        $pushNotificationSubscriptionCriteriaTransfer = $this->createPushNotificationSubscriptionCriteriaTransfer(
            $pushNotificationSubscriptionTransfer,
        );

        $pushNotificationSubscriptionCollectionTransfer = $this
            ->pushNotificationSubscriptionReader
            ->getPushNotificationSubscriptionCollection($pushNotificationSubscriptionCriteriaTransfer);

        return $pushNotificationSubscriptionCollectionTransfer->getPushNotificationSubscriptions()->count() === 0;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionCriteriaTransfer
     */
    protected function createPushNotificationSubscriptionCriteriaTransfer(
        PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
    ): PushNotificationSubscriptionCriteriaTransfer {
        $idPushNotificationProvider = $this->getPushNotificationProviderId($pushNotificationSubscriptionTransfer);
        $idPushNotificationGroup = $this->getPushNotificationGroupId($pushNotificationSubscriptionTransfer);
        $payloadCheckSum = $this->pushNotificationSubscriptionCheckSumGenerator->generatePayloadChecksum(
            $pushNotificationSubscriptionTransfer,
        );
        $pushNotificationSubscriptionConditionsTransfer = (new PushNotificationSubscriptionConditionsTransfer())
            ->addIdPushNotificationProvider($idPushNotificationProvider)
            ->addIdPushNotificationGroup($idPushNotificationGroup)
            ->addPayloadChecksum($payloadCheckSum);

        $paginationTransfer = (new PaginationTransfer())
            ->setPage(1)
            ->setMaxPerPage(1);

        return (new PushNotificationSubscriptionCriteriaTransfer())
            ->setPushNotificationSubscriptionConditions($pushNotificationSubscriptionConditionsTransfer)
            ->setPagination($paginationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     *
     * @return int
     */
    protected function getPushNotificationProviderId(PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer): int
    {
        $pushNotificationProviderTransfersIndexedByName = $this
            ->pushNotificationProviderReader
            ->getPushNotificationProviderTransfersIndexedByName();

        $pushNotificationProviderName = $pushNotificationSubscriptionTransfer->getProviderOrFail()->getNameOrFail();
        $pushNotificationProviderTransfer = $pushNotificationProviderTransfersIndexedByName[$pushNotificationProviderName];

        return $pushNotificationProviderTransfer->getIdPushNotificationProviderOrFail();
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     *
     * @return int
     */
    protected function getPushNotificationGroupId(PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer): int
    {
        /** @var \Generated\Shared\Transfer\PushNotificationGroupTransfer $pushNotificationGroupTransfer */
        $pushNotificationGroupTransfer = $this->pushNotificationGroupReader->findPushNotificationGroupByNameAndIdentifier(
            $pushNotificationSubscriptionTransfer->getGroupOrFail()->getNameOrFail(),
            $pushNotificationSubscriptionTransfer->getGroupOrFail()->getIdentifier(),
        );

        return $pushNotificationGroupTransfer->getIdPushNotificationGroupOrFail();
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationGroupTransfer $pushNotificationGroupTransfer
     *
     * @return bool
     */
    protected function isNewGroup(PushNotificationGroupTransfer $pushNotificationGroupTransfer): bool
    {
        $pushNotificationGroupTransfer = $this
            ->pushNotificationGroupReader
            ->findPushNotificationGroupByNameAndIdentifier(
                $pushNotificationGroupTransfer->getNameOrFail(),
                $pushNotificationGroupTransfer->getIdentifier(),
            );

        return $pushNotificationGroupTransfer === null;
    }
}
