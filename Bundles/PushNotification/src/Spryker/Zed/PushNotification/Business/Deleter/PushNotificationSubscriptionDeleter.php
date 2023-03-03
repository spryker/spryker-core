<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Deleter;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCriteriaTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\PushNotification\Business\Mapper\PushNotificationSubscriptionMapperInterface;
use Spryker\Zed\PushNotification\Persistence\PushNotificationEntityManagerInterface;
use Spryker\Zed\PushNotification\Persistence\PushNotificationRepositoryInterface;
use Spryker\Zed\PushNotification\PushNotificationConfig;

class PushNotificationSubscriptionDeleter implements PushNotificationSubscriptionDeleterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\PushNotification\Persistence\PushNotificationEntityManagerInterface
     */
    protected PushNotificationEntityManagerInterface $pushNotificationEntityManager;

    /**
     * @var \Spryker\Zed\PushNotification\Persistence\PushNotificationRepositoryInterface
     */
    protected PushNotificationRepositoryInterface $pushNotificationRepository;

    /**
     * @var \Spryker\Zed\PushNotification\PushNotificationConfig
     */
    protected PushNotificationConfig $pushNotificationConfig;

    /**
     * @var \Spryker\Zed\PushNotification\Business\Mapper\PushNotificationSubscriptionMapperInterface
     */
    protected PushNotificationSubscriptionMapperInterface $pushNotificationSubscriptionMapper;

    /**
     * @param \Spryker\Zed\PushNotification\Persistence\PushNotificationEntityManagerInterface $pushNotificationEntityManager
     * @param \Spryker\Zed\PushNotification\Persistence\PushNotificationRepositoryInterface $pushNotificationRepository
     * @param \Spryker\Zed\PushNotification\PushNotificationConfig $pushNotificationConfig
     * @param \Spryker\Zed\PushNotification\Business\Mapper\PushNotificationSubscriptionMapperInterface $pushNotificationSubscriptionMapper
     */
    public function __construct(
        PushNotificationEntityManagerInterface $pushNotificationEntityManager,
        PushNotificationRepositoryInterface $pushNotificationRepository,
        PushNotificationConfig $pushNotificationConfig,
        PushNotificationSubscriptionMapperInterface $pushNotificationSubscriptionMapper
    ) {
        $this->pushNotificationEntityManager = $pushNotificationEntityManager;
        $this->pushNotificationRepository = $pushNotificationRepository;
        $this->pushNotificationConfig = $pushNotificationConfig;
        $this->pushNotificationSubscriptionMapper = $pushNotificationSubscriptionMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionCollectionDeleteCriteriaTransfer $pushNotificationSubscriptionCollectionDeleteCriteriaTransfer
     *
     * @return void
     */
    public function deletePushNotificationSubscriptions(
        PushNotificationSubscriptionCollectionDeleteCriteriaTransfer $pushNotificationSubscriptionCollectionDeleteCriteriaTransfer
    ): void {
        $pushNotificationSubscriptionCriteriaTransfer = $this->getPushNotificationSubscriptionCriteriaTransfer(
            $pushNotificationSubscriptionCollectionDeleteCriteriaTransfer,
        );
        do {
            $pushNotificationSubscriptionCollectionTransfer = $this
                ->pushNotificationRepository
                ->getPushNotificationSubscriptionCollection(
                    $pushNotificationSubscriptionCriteriaTransfer,
                );

            $this->getTransactionHandler()->handleTransaction(
                function () use ($pushNotificationSubscriptionCollectionTransfer): void {
                    $this->executeDeletePushNotificationSubscriptionsTransaction(
                        $pushNotificationSubscriptionCollectionTransfer,
                    );
                },
            );
        } while (count($pushNotificationSubscriptionCollectionTransfer->getPushNotificationSubscriptions()) > 0);
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionCollectionTransfer $pushNotificationSubscriptionCollectionTransfer
     *
     * @return void
     */
    protected function executeDeletePushNotificationSubscriptionsTransaction(
        PushNotificationSubscriptionCollectionTransfer $pushNotificationSubscriptionCollectionTransfer
    ): void {
        foreach ($pushNotificationSubscriptionCollectionTransfer->getPushNotificationSubscriptions() as $pushNotificationSubscriptionTransfer) {
            $this->pushNotificationEntityManager->deletePushNotificationSubscription(
                $pushNotificationSubscriptionTransfer,
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionCollectionDeleteCriteriaTransfer $pushNotificationSubscriptionCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionCriteriaTransfer
     */
    protected function getPushNotificationSubscriptionCriteriaTransfer(
        PushNotificationSubscriptionCollectionDeleteCriteriaTransfer $pushNotificationSubscriptionCollectionDeleteCriteriaTransfer
    ): PushNotificationSubscriptionCriteriaTransfer {
        $pushNotificationSubscriptionCriteriaTransfer = $this->getPaginatedPushNotificationSubscriptionCriteriaTransfer();

        return $this
            ->pushNotificationSubscriptionMapper
            ->mapPushNotificationSubscriptionCollectionDeleteCriteriaTransferToPushNotificationSubscriptionCriteriaTransfer(
                $pushNotificationSubscriptionCollectionDeleteCriteriaTransfer,
                $pushNotificationSubscriptionCriteriaTransfer,
            );
    }

    /**
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionCriteriaTransfer
     */
    protected function getPaginatedPushNotificationSubscriptionCriteriaTransfer(): PushNotificationSubscriptionCriteriaTransfer
    {
        $paginationTransfer = (new PaginationTransfer())
            ->setPage(1)
            ->setMaxPerPage($this->pushNotificationConfig->getPushNotificationDeleteBatchSize());

        return (new PushNotificationSubscriptionCriteriaTransfer())->setPagination($paginationTransfer);
    }
}
