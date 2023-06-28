<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Deleter;

use ArrayObject;
use Generated\Shared\Transfer\PushNotificationProviderCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCollectionResponseTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationProviderConditionsTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCriteriaTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\PushNotification\Business\Filter\PushNotificationProviderFilterInterface;
use Spryker\Zed\PushNotification\Business\Reader\PushNotificationProviderReaderInterface;
use Spryker\Zed\PushNotification\Business\Validator\PushNotificationProviderValidatorInterface;
use Spryker\Zed\PushNotification\Persistence\PushNotificationEntityManagerInterface;

class PushNotificationProviderDeleter implements PushNotificationProviderDeleterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\PushNotification\Persistence\PushNotificationEntityManagerInterface
     */
    protected PushNotificationEntityManagerInterface $pushNotificationEntityManager;

    /**
     * @var \Spryker\Zed\PushNotification\Business\Validator\PushNotificationProviderValidatorInterface
     */
    protected PushNotificationProviderValidatorInterface $pushNotificationProviderValidator;

    /**
     * @var \Spryker\Zed\PushNotification\Business\Filter\PushNotificationProviderFilterInterface
     */
    protected PushNotificationProviderFilterInterface $pushNotificationProviderFilter;

    /**
     * @var \Spryker\Zed\PushNotification\Business\Reader\PushNotificationProviderReaderInterface
     */
    protected PushNotificationProviderReaderInterface $pushNotificationProviderReader;

    /**
     * @param \Spryker\Zed\PushNotification\Persistence\PushNotificationEntityManagerInterface $pushNotificationEntityManager
     * @param \Spryker\Zed\PushNotification\Business\Validator\PushNotificationProviderValidatorInterface $pushNotificationProviderValidator
     * @param \Spryker\Zed\PushNotification\Business\Filter\PushNotificationProviderFilterInterface $pushNotificationProviderFilter
     * @param \Spryker\Zed\PushNotification\Business\Reader\PushNotificationProviderReaderInterface $pushNotificationProviderReader
     */
    public function __construct(
        PushNotificationEntityManagerInterface $pushNotificationEntityManager,
        PushNotificationProviderValidatorInterface $pushNotificationProviderValidator,
        PushNotificationProviderFilterInterface $pushNotificationProviderFilter,
        PushNotificationProviderReaderInterface $pushNotificationProviderReader
    ) {
        $this->pushNotificationEntityManager = $pushNotificationEntityManager;
        $this->pushNotificationProviderValidator = $pushNotificationProviderValidator;
        $this->pushNotificationProviderFilter = $pushNotificationProviderFilter;
        $this->pushNotificationProviderReader = $pushNotificationProviderReader;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationProviderCollectionDeleteCriteriaTransfer $pushNotificationProviderCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderCollectionResponseTransfer
     */
    public function deletePushNotificationProviderCollection(
        PushNotificationProviderCollectionDeleteCriteriaTransfer $pushNotificationProviderCollectionDeleteCriteriaTransfer
    ): PushNotificationProviderCollectionResponseTransfer {
        $this->assertRequiredFields($pushNotificationProviderCollectionDeleteCriteriaTransfer);

        $pushNotificationProviderCollectionTransfer = $this->getPushNotificationProviderCollection(
            $pushNotificationProviderCollectionDeleteCriteriaTransfer,
        );

        $pushNotificationProviderCollectionResponseTransfer = (new PushNotificationProviderCollectionResponseTransfer())
            ->setPushNotificationProviders($pushNotificationProviderCollectionTransfer->getPushNotificationProviders());

        $pushNotificationProviderCollectionResponseTransfer = $this->pushNotificationProviderValidator
            ->validate($pushNotificationProviderCollectionResponseTransfer);

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers */
        $errorTransfers = $pushNotificationProviderCollectionResponseTransfer->getErrors();

        if ($pushNotificationProviderCollectionDeleteCriteriaTransfer->getIsTransactional() && $errorTransfers->count()) {
            return $pushNotificationProviderCollectionResponseTransfer;
        }

        [$validPushNotificationProviderTransfers, $invalidPushNotificationProviderTransfers] = $this->pushNotificationProviderFilter
            ->filterPushNotificationProvidersByValidity($pushNotificationProviderCollectionResponseTransfer);

        if ($validPushNotificationProviderTransfers->count()) {
            $validPushNotificationProviderTransfers = $this->getTransactionHandler()
                ->handleTransaction(function () use ($validPushNotificationProviderTransfers) {
                    return $this->executeDeletePushNotificationProviderCollectionTransaction($validPushNotificationProviderTransfers);
                });
        }

        $pushNotificationProviderTransfers = $this->pushNotificationProviderFilter->mergePushNotificationProviders(
            $validPushNotificationProviderTransfers,
            $invalidPushNotificationProviderTransfers,
        );

        return $pushNotificationProviderCollectionResponseTransfer->setPushNotificationProviders($pushNotificationProviderTransfers);
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\PushNotificationProviderTransfer> $pushNotificationProviderTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\PushNotificationProviderTransfer>
     */
    protected function executeDeletePushNotificationProviderCollectionTransaction(
        ArrayObject $pushNotificationProviderTransfers
    ): ArrayObject {
        $pushNotificationProviderUuids = $this->extractPushNotificationProviderUuids($pushNotificationProviderTransfers);

        $this->pushNotificationEntityManager->deletePushNotificationProviders($pushNotificationProviderUuids);

        return $pushNotificationProviderTransfers;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\PushNotificationProviderTransfer> $pushNotificationProviderTransfers
     *
     * @return list<string>
     */
    protected function extractPushNotificationProviderUuids(ArrayObject $pushNotificationProviderTransfers): array
    {
        $pushNotificationProviderUuids = [];

        foreach ($pushNotificationProviderTransfers as $pushNotificationProviderTransfer) {
            $pushNotificationProviderUuids[] = $pushNotificationProviderTransfer->getUuidOrFail();
        }

        return $pushNotificationProviderUuids;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationProviderCollectionDeleteCriteriaTransfer $pushNotificationProviderCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderCollectionTransfer
     */
    protected function getPushNotificationProviderCollection(
        PushNotificationProviderCollectionDeleteCriteriaTransfer $pushNotificationProviderCollectionDeleteCriteriaTransfer
    ): PushNotificationProviderCollectionTransfer {
        $pushNotificationProviderConditionsTransfer = (new PushNotificationProviderConditionsTransfer())
            ->setUuids($pushNotificationProviderCollectionDeleteCriteriaTransfer->getUuids());

        $pushNotificationProviderCriteriaTransfer = (new PushNotificationProviderCriteriaTransfer())
            ->setPushNotificationProviderConditions($pushNotificationProviderConditionsTransfer);

        return $this->pushNotificationProviderReader->getPushNotificationProviderCollection($pushNotificationProviderCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationProviderCollectionDeleteCriteriaTransfer $pushNotificationProviderCollectionDeleteCriteriaTransfer
     *
     * @return void
     */
    protected function assertRequiredFields(
        PushNotificationProviderCollectionDeleteCriteriaTransfer $pushNotificationProviderCollectionDeleteCriteriaTransfer
    ): void {
        $pushNotificationProviderCollectionDeleteCriteriaTransfer
            ->requireIsTransactional()
            ->requireUuids();
    }
}
