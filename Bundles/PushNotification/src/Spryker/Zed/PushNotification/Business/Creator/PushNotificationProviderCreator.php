<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Creator;

use ArrayObject;
use Generated\Shared\Transfer\PushNotificationProviderCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCollectionResponseTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\PushNotification\Business\Filter\PushNotificationProviderFilterInterface;
use Spryker\Zed\PushNotification\Business\Validator\PushNotificationProviderValidatorInterface;
use Spryker\Zed\PushNotification\Persistence\PushNotificationEntityManagerInterface;

class PushNotificationProviderCreator implements PushNotificationProviderCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\PushNotification\Business\Validator\PushNotificationProviderValidatorInterface
     */
    protected PushNotificationProviderValidatorInterface $pushNotificationProviderValidator;

    /**
     * @var \Spryker\Zed\PushNotification\Business\Filter\PushNotificationProviderFilterInterface
     */
    protected PushNotificationProviderFilterInterface $pushNotificationProviderFilter;

    /**
     * @var \Spryker\Zed\PushNotification\Persistence\PushNotificationEntityManagerInterface
     */
    protected PushNotificationEntityManagerInterface $pushNotificationEntityManager;

    /**
     * @param \Spryker\Zed\PushNotification\Business\Validator\PushNotificationProviderValidatorInterface $pushNotificationProviderValidator
     * @param \Spryker\Zed\PushNotification\Business\Filter\PushNotificationProviderFilterInterface $pushNotificationProviderFilter
     * @param \Spryker\Zed\PushNotification\Persistence\PushNotificationEntityManagerInterface $pushNotificationEntityManager
     */
    public function __construct(
        PushNotificationProviderValidatorInterface $pushNotificationProviderValidator,
        PushNotificationProviderFilterInterface $pushNotificationProviderFilter,
        PushNotificationEntityManagerInterface $pushNotificationEntityManager
    ) {
        $this->pushNotificationProviderValidator = $pushNotificationProviderValidator;
        $this->pushNotificationProviderFilter = $pushNotificationProviderFilter;
        $this->pushNotificationEntityManager = $pushNotificationEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationProviderCollectionRequestTransfer $pushNotificationProviderCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderCollectionResponseTransfer
     */
    public function createPushNotificationProviderCollection(
        PushNotificationProviderCollectionRequestTransfer $pushNotificationProviderCollectionRequestTransfer
    ): PushNotificationProviderCollectionResponseTransfer {
        $this->assertRequiredFields($pushNotificationProviderCollectionRequestTransfer);

        $pushNotificationProviderCollectionResponseTransfer = (new PushNotificationProviderCollectionResponseTransfer())
            ->setPushNotificationProviders($pushNotificationProviderCollectionRequestTransfer->getPushNotificationProviders());

        $pushNotificationProviderCollectionResponseTransfer = $this->pushNotificationProviderValidator
            ->validate($pushNotificationProviderCollectionResponseTransfer);

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers */
        $errorTransfers = $pushNotificationProviderCollectionResponseTransfer->getErrors();

        if ($pushNotificationProviderCollectionRequestTransfer->getIsTransactional() && $errorTransfers->count()) {
            return $pushNotificationProviderCollectionResponseTransfer;
        }

        [$validPushNotificationProviderTransfers, $invalidPushNotificationProviderTransfers] = $this->pushNotificationProviderFilter
            ->filterPushNotificationProvidersByValidity($pushNotificationProviderCollectionResponseTransfer);

        if ($validPushNotificationProviderTransfers->count()) {
            $validPushNotificationProviderTransfers = $this->getTransactionHandler()->handleTransaction(function () use ($validPushNotificationProviderTransfers) {
                return $this->executeCreatePushNotificationProviderCollectionTransaction($validPushNotificationProviderTransfers);
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
    protected function executeCreatePushNotificationProviderCollectionTransaction(
        ArrayObject $pushNotificationProviderTransfers
    ): ArrayObject {
        $persistedPushNotificationProviderTransfers = new ArrayObject();

        foreach ($pushNotificationProviderTransfers as $entityIdentifier => $pushNotificationProviderTransfer) {
            $pushNotificationProviderTransfer = $this->pushNotificationEntityManager->createPushNotificationProvider($pushNotificationProviderTransfer);
            $persistedPushNotificationProviderTransfers->offsetSet(
                $entityIdentifier,
                $pushNotificationProviderTransfer,
            );
        }

        return $persistedPushNotificationProviderTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationProviderCollectionRequestTransfer $pushNotificationProviderCollectionRequestTransfer
     *
     * @return void
     */
    protected function assertRequiredFields(
        PushNotificationProviderCollectionRequestTransfer $pushNotificationProviderCollectionRequestTransfer
    ): void {
        $pushNotificationProviderCollectionRequestTransfer
            ->requireIsTransactional()
            ->requirePushNotificationProviders();

        foreach ($pushNotificationProviderCollectionRequestTransfer->getPushNotificationProviders() as $pushNotificationProviderTransfer) {
            $pushNotificationProviderTransfer
                ->requireName();
        }
    }
}
