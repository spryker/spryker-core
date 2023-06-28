<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Creator;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer;
use Generated\Shared\Transfer\PushNotificationTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\PushNotification\Business\Filter\PushNotificationFilterInterface;
use Spryker\Zed\PushNotification\Business\Reader\PushNotificationProviderReaderInterface;
use Spryker\Zed\PushNotification\Business\Validator\PushNotificationValidatorInterface;
use Spryker\Zed\PushNotification\Persistence\PushNotificationEntityManagerInterface;

class PushNotificationCreator implements PushNotificationCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\PushNotification\Persistence\PushNotificationEntityManagerInterface
     */
    protected PushNotificationEntityManagerInterface $pushNotificationEntityManager;

    /**
     * @var \Spryker\Zed\PushNotification\Business\Validator\PushNotificationValidatorInterface
     */
    protected PushNotificationValidatorInterface $pushNotificationValidator;

    /**
     * @var \Spryker\Zed\PushNotification\Business\Filter\PushNotificationFilterInterface
     */
    protected PushNotificationFilterInterface $pushNotificationFilter;

    /**
     * @var \Spryker\Zed\PushNotification\Business\Reader\PushNotificationProviderReaderInterface
     */
    protected PushNotificationProviderReaderInterface $pushNotificationProviderReader;

    /**
     * @param \Spryker\Zed\PushNotification\Persistence\PushNotificationEntityManagerInterface $pushNotificationEntityManager
     * @param \Spryker\Zed\PushNotification\Business\Validator\PushNotificationValidatorInterface $pushNotificationValidator
     * @param \Spryker\Zed\PushNotification\Business\Filter\PushNotificationFilterInterface $pushNotificationFilter
     * @param \Spryker\Zed\PushNotification\Business\Reader\PushNotificationProviderReaderInterface $pushNotificationProviderReader
     */
    public function __construct(
        PushNotificationEntityManagerInterface $pushNotificationEntityManager,
        PushNotificationValidatorInterface $pushNotificationValidator,
        PushNotificationFilterInterface $pushNotificationFilter,
        PushNotificationProviderReaderInterface $pushNotificationProviderReader
    ) {
        $this->pushNotificationEntityManager = $pushNotificationEntityManager;
        $this->pushNotificationValidator = $pushNotificationValidator;
        $this->pushNotificationFilter = $pushNotificationFilter;
        $this->pushNotificationProviderReader = $pushNotificationProviderReader;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer $pushNotificationCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer
     */
    public function createPushNotificationCollection(
        PushNotificationCollectionRequestTransfer $pushNotificationCollectionRequestTransfer
    ): PushNotificationCollectionResponseTransfer {
        /** @var \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationTransfer> $pushNotificationTransfers */
        $pushNotificationTransfers = $pushNotificationCollectionRequestTransfer->getPushNotifications();
        $errorCollectionTransfer = $this->pushNotificationValidator->validateCollection($pushNotificationTransfers);

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers */
        $errorTransfers = $errorCollectionTransfer->getErrors();
        $pushNotificationCollectionResponseTransfer = $this->createPushNotificationCollectionResponseTransfer(
            $pushNotificationTransfers,
            $errorTransfers,
        );

        if (
            $pushNotificationCollectionRequestTransfer->getIsTransactional()
            && $errorTransfers->count() !== 0
        ) {
            return $pushNotificationCollectionResponseTransfer;
        }

        $pushNotificationTransfers = $this->executeCreatePushNotificationCollection(
            $pushNotificationTransfers,
            $errorCollectionTransfer,
        );

        return $pushNotificationCollectionResponseTransfer->setPushNotifications($pushNotificationTransfers);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationTransfer> $pushNotificationTransfers
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \ArrayObject<\Generated\Shared\Transfer\PushNotificationTransfer>
     */
    protected function executeCreatePushNotificationCollection(
        ArrayObject $pushNotificationTransfers,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): ArrayObject {
        $validPushNotificationTransfers = $this->pushNotificationFilter->filterOutInvalidPushNotifications(
            $pushNotificationTransfers,
            $errorCollectionTransfer,
        );
        $invalidPushNotificationTransfers = $this->pushNotificationFilter->filterOutValidPushNotifications(
            $pushNotificationTransfers,
            $errorCollectionTransfer,
        );

        $persistedPushNotifications = $this->getTransactionHandler()->handleTransaction(
            function () use ($validPushNotificationTransfers): ArrayObject {
                return $this->executeCreatePushNotificationCollectionTransaction($validPushNotificationTransfers);
            },
        );

        return $this->mergePushNotificationTransfers($persistedPushNotifications, $invalidPushNotificationTransfers);
    }

    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\PushNotificationTransfer> $validPushNotificationTransfers
     * @param \ArrayObject<\Generated\Shared\Transfer\PushNotificationTransfer> $invalidPushNotificationTransfers
     *
     * @return \ArrayObject<\Generated\Shared\Transfer\PushNotificationTransfer>
     */
    protected function mergePushNotificationTransfers(
        ArrayObject $validPushNotificationTransfers,
        ArrayObject $invalidPushNotificationTransfers
    ): ArrayObject {
        foreach ($invalidPushNotificationTransfers as $pushNotificationTransfer) {
            $validPushNotificationTransfers->append($pushNotificationTransfer);
        }

        return $validPushNotificationTransfers;
    }

    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\PushNotificationTransfer> $pushNotificationTransfers
     *
     * @return \ArrayObject<\Generated\Shared\Transfer\PushNotificationTransfer>
     */
    protected function executeCreatePushNotificationCollectionTransaction(
        ArrayObject $pushNotificationTransfers
    ): ArrayObject {
        $persistedPushNotifications = new ArrayObject();
        $pushNotificationProviderTransfersIndexedByName = $this->pushNotificationProviderReader
            ->getPushNotificationProviderTransfersIndexedByName();

        foreach ($pushNotificationTransfers as $pushNotificationTransfer) {
            $pushNotificationTransfer = $this->executeCreatePushNotificationTransaction(
                $pushNotificationTransfer,
                $pushNotificationProviderTransfersIndexedByName,
            );
            $persistedPushNotifications->append($pushNotificationTransfer);
        }

        return $persistedPushNotifications;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationTransfer $pushNotificationTransfer
     * @param array<string, \Generated\Shared\Transfer\PushNotificationProviderTransfer> $pushNotificationProviderTransfersIndexedByName
     *
     * @return \Generated\Shared\Transfer\PushNotificationTransfer
     */
    protected function executeCreatePushNotificationTransaction(
        PushNotificationTransfer $pushNotificationTransfer,
        array $pushNotificationProviderTransfersIndexedByName
    ): PushNotificationTransfer {
        $pushNotificationGroupTransfer = $this->pushNotificationEntityManager->createPushNotificationGroup(
            $pushNotificationTransfer->getGroupOrFail(),
        );
        $pushNotificationTransfer->setGroup($pushNotificationGroupTransfer);

        $pushNotificationProviderName = $pushNotificationTransfer->getProviderOrFail()->getNameOrFail();
        $pushNotificationProviderTransfer = $pushNotificationProviderTransfersIndexedByName[$pushNotificationProviderName];
        $pushNotificationTransfer->setProvider($pushNotificationProviderTransfer);

        return $this->pushNotificationEntityManager
            ->createPushNotification($pushNotificationTransfer);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationTransfer> $pushNotificationTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     *
     * @return \Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer
     */
    protected function createPushNotificationCollectionResponseTransfer(
        ArrayObject $pushNotificationTransfers,
        ArrayObject $errorTransfers
    ): PushNotificationCollectionResponseTransfer {
        return (new PushNotificationCollectionResponseTransfer())
            ->setPushNotifications($pushNotificationTransfers)
            ->setErrors($errorTransfers);
    }
}
