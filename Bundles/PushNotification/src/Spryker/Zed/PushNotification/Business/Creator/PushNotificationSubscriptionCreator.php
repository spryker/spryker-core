<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Creator;

use ArrayObject;
use DateInterval;
use DateTime;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionResponseTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\PushNotification\Business\Filter\PushNotificationSubscriptionFilterInterface;
use Spryker\Zed\PushNotification\Business\Generator\PushNotificationSubscriptionCheckSumGeneratorInterface;
use Spryker\Zed\PushNotification\Business\Validator\PushNotificationSubscriptionValidatorInterface;
use Spryker\Zed\PushNotification\Persistence\PushNotificationEntityManagerInterface;
use Spryker\Zed\PushNotification\PushNotificationConfig;

class PushNotificationSubscriptionCreator implements PushNotificationSubscriptionCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\PushNotification\Persistence\PushNotificationEntityManagerInterface
     */
    protected PushNotificationEntityManagerInterface $pushNotificationEntityManager;

    /**
     * @var \Spryker\Zed\PushNotification\Business\Validator\PushNotificationSubscriptionValidatorInterface
     */
    protected PushNotificationSubscriptionValidatorInterface $pushNotificationSubscriptionValidator;

    /**
     * @var \Spryker\Zed\PushNotification\PushNotificationConfig
     */
    protected PushNotificationConfig $pushNotificationConfig;

    /**
     * @var \Spryker\Zed\PushNotification\Business\Filter\PushNotificationSubscriptionFilterInterface
     */
    protected PushNotificationSubscriptionFilterInterface $pushNotificationSubscriptionFilter;

    /**
     * @var \Spryker\Zed\PushNotification\Business\Generator\PushNotificationSubscriptionCheckSumGeneratorInterface
     */
    protected PushNotificationSubscriptionCheckSumGeneratorInterface $pushNotificationSubscriptionCheckSumGenerator;

    /**
     * @var list<\Spryker\Zed\PushNotification\Business\Expander\PushNotificationSubscriptionExpanderInterface>
     */
    protected array $pushNotificationSubscriptionExpanders;

    /**
     * @param \Spryker\Zed\PushNotification\Persistence\PushNotificationEntityManagerInterface $pushNotificationEntityManager
     * @param \Spryker\Zed\PushNotification\Business\Validator\PushNotificationSubscriptionValidatorInterface $pushNotificationSubscriptionValidator
     * @param \Spryker\Zed\PushNotification\PushNotificationConfig $pushNotificationConfig
     * @param \Spryker\Zed\PushNotification\Business\Filter\PushNotificationSubscriptionFilterInterface $pushNotificationSubscriptionFilter
     * @param \Spryker\Zed\PushNotification\Business\Generator\PushNotificationSubscriptionCheckSumGeneratorInterface $pushNotificationSubscriptionCheckSumGenerator
     * @param list<\Spryker\Zed\PushNotification\Business\Expander\PushNotificationSubscriptionExpanderInterface> $pushNotificationSubscriptionExpanders
     */
    public function __construct(
        PushNotificationEntityManagerInterface $pushNotificationEntityManager,
        PushNotificationSubscriptionValidatorInterface $pushNotificationSubscriptionValidator,
        PushNotificationConfig $pushNotificationConfig,
        PushNotificationSubscriptionFilterInterface $pushNotificationSubscriptionFilter,
        PushNotificationSubscriptionCheckSumGeneratorInterface $pushNotificationSubscriptionCheckSumGenerator,
        array $pushNotificationSubscriptionExpanders
    ) {
        $this->pushNotificationEntityManager = $pushNotificationEntityManager;
        $this->pushNotificationSubscriptionValidator = $pushNotificationSubscriptionValidator;
        $this->pushNotificationConfig = $pushNotificationConfig;
        $this->pushNotificationSubscriptionFilter = $pushNotificationSubscriptionFilter;
        $this->pushNotificationSubscriptionCheckSumGenerator = $pushNotificationSubscriptionCheckSumGenerator;
        $this->pushNotificationSubscriptionExpanders = $pushNotificationSubscriptionExpanders;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionCollectionRequestTransfer $pushNotificationSubscriptionCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionCollectionResponseTransfer
     */
    public function createPushNotificationSubscriptionCollection(
        PushNotificationSubscriptionCollectionRequestTransfer $pushNotificationSubscriptionCollectionRequestTransfer
    ): PushNotificationSubscriptionCollectionResponseTransfer {
        /**
         * @var \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
         */
        $pushNotificationSubscriptionTransfers = $pushNotificationSubscriptionCollectionRequestTransfer->getPushNotificationSubscriptions();
        $pushNotificationSubscriptionTransfers = $this->generateCheckSum($pushNotificationSubscriptionTransfers);
        $errorCollectionTransfer = $this->pushNotificationSubscriptionValidator
            ->validateCollection($pushNotificationSubscriptionTransfers);
        /**
         * @var \ArrayObject<\Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
         */
        $errorTransfers = $errorCollectionTransfer->getErrors();

        $pushNotificationSubscriptionCollectionResponseTransfer = $this->createPushNotificationSubscriptionCollectionResponseTransfer(
            $pushNotificationSubscriptionTransfers,
            $errorTransfers,
        );

        if (
            $pushNotificationSubscriptionCollectionRequestTransfer->getIsTransactional()
            && $errorTransfers->count() !== 0
        ) {
            return $pushNotificationSubscriptionCollectionResponseTransfer;
        }

        $pushNotificationSubscriptionTransfers = $this->executeCreatePushNotificationSubscriptionCollection(
            $pushNotificationSubscriptionTransfers,
            $errorCollectionTransfer,
        );

        return $pushNotificationSubscriptionCollectionResponseTransfer->setPushNotificationSubscriptions(
            $pushNotificationSubscriptionTransfers,
        );
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \ArrayObject<\Generated\Shared\Transfer\PushNotificationSubscriptionTransfer>
     */
    protected function executeCreatePushNotificationSubscriptionCollection(
        ArrayObject $pushNotificationSubscriptionTransfers,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): ArrayObject {
        $validPushNotificationSubscriptionTransfers = $this->pushNotificationSubscriptionFilter
            ->filterOutInvalidPushNotificationSubscriptions(
                $pushNotificationSubscriptionTransfers,
                $errorCollectionTransfer,
            );

        $invalidPushNotificationSubscriptionTransfers = $this->pushNotificationSubscriptionFilter
            ->filterOutValidPushNotificationSubscriptions(
                $pushNotificationSubscriptionTransfers,
                $errorCollectionTransfer,
            );

        $validPushNotificationSubscriptionTransfers =
            $this->expandPushNotificationSubscriptionsWithRelations($validPushNotificationSubscriptionTransfers);
        $persistedPushNotificationSubscriptions = $this->getTransactionHandler()->handleTransaction(
            function () use ($validPushNotificationSubscriptionTransfers): ArrayObject {
                return $this->executeCreatePushNotificationSubscriptionCollectionTransaction(
                    $validPushNotificationSubscriptionTransfers,
                );
            },
        );

        return $this->mergePushNotificationSubscriptionTransfers(
            $persistedPushNotificationSubscriptions,
            $invalidPushNotificationSubscriptionTransfers,
        );
    }

    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $validPushNotificationSubscriptionTransfers
     * @param \ArrayObject<\Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $invalidPushNotificationSubscriptionTransfers
     *
     * @return \ArrayObject<\Generated\Shared\Transfer\PushNotificationSubscriptionTransfer>
     */
    protected function mergePushNotificationSubscriptionTransfers(
        ArrayObject $validPushNotificationSubscriptionTransfers,
        ArrayObject $invalidPushNotificationSubscriptionTransfers
    ): ArrayObject {
        foreach ($invalidPushNotificationSubscriptionTransfers as $pushNotificationSubscriptionTransfer) {
            $validPushNotificationSubscriptionTransfers->append($pushNotificationSubscriptionTransfer);
        }

        return $validPushNotificationSubscriptionTransfers;
    }

    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     *
     * @return \ArrayObject<\Generated\Shared\Transfer\PushNotificationSubscriptionTransfer>
     */
    protected function executeCreatePushNotificationSubscriptionCollectionTransaction(
        ArrayObject $pushNotificationSubscriptionTransfers
    ): ArrayObject {
        $persistedPushNotificationSubscriptionTransfers = new ArrayObject();
        foreach ($pushNotificationSubscriptionTransfers as $pushNotificationSubscriptionTransfer) {
            $pushNotificationSubscriptionTransfer = $this->setPushNotificationSubscriptionExpiredAt(
                $pushNotificationSubscriptionTransfer,
            );

            $pushNotificationGroupTransfer = $this
                ->pushNotificationEntityManager
                ->createPushNotificationGroup($pushNotificationSubscriptionTransfer->getGroupOrFail());
            $pushNotificationSubscriptionTransfer->setGroup($pushNotificationGroupTransfer);

            $pushNotificationSubscriptionTransfer = $this
                ->pushNotificationEntityManager
                ->createPushNotificationSubscription($pushNotificationSubscriptionTransfer);

            $persistedPushNotificationSubscriptionTransfers->append($pushNotificationSubscriptionTransfer);
        }

        return $persistedPushNotificationSubscriptionTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer
     */
    protected function setPushNotificationSubscriptionExpiredAt(
        PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
    ): PushNotificationSubscriptionTransfer {
        if ($pushNotificationSubscriptionTransfer->getExpiredAt()) {
            return $pushNotificationSubscriptionTransfer;
        }

        $pushNotificationSubscriptionTtl = $this->pushNotificationConfig->getPushNotificationSubscriptionTTL();

        return $pushNotificationSubscriptionTransfer->setExpiredAt(
            (string)(new DateTime())->add(new DateInterval($pushNotificationSubscriptionTtl))->getTimestamp(),
        );
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     * @param \ArrayObject<\Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionCollectionResponseTransfer
     */
    protected function createPushNotificationSubscriptionCollectionResponseTransfer(
        ArrayObject $pushNotificationSubscriptionTransfers,
        ArrayObject $errorTransfers
    ): PushNotificationSubscriptionCollectionResponseTransfer {
        return (new PushNotificationSubscriptionCollectionResponseTransfer())
            ->setPushNotificationSubscriptions($pushNotificationSubscriptionTransfers)
            ->setErrors($errorTransfers);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer>
     */
    protected function generateCheckSum(ArrayObject $pushNotificationSubscriptionTransfers): ArrayObject
    {
        /** @var \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer */
        foreach ($pushNotificationSubscriptionTransfers as $pushNotificationSubscriptionTransfer) {
            $payloadCheckSum = $this->pushNotificationSubscriptionCheckSumGenerator->generatePayloadChecksum($pushNotificationSubscriptionTransfer);
            $pushNotificationSubscriptionTransfer->setPayloadCheckSum($payloadCheckSum);
        }

        return $pushNotificationSubscriptionTransfers;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer>
     */
    protected function expandPushNotificationSubscriptionsWithRelations(ArrayObject $pushNotificationSubscriptionTransfers): ArrayObject
    {
        foreach ($this->pushNotificationSubscriptionExpanders as $pushNotificationSubscriptionExpander) {
            $pushNotificationSubscriptionTransfers = $pushNotificationSubscriptionExpander->expand($pushNotificationSubscriptionTransfers);
        }

        return $pushNotificationSubscriptionTransfers;
    }
}
