<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Creator;

use ArrayObject;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\PushNotification\Persistence\PushNotificationEntityManagerInterface;

class PushNotificationSubscriptionDeliveryLogCreator implements PushNotificationSubscriptionDeliveryLogCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\PushNotification\Persistence\PushNotificationEntityManagerInterface
     */
    protected PushNotificationEntityManagerInterface $pushNotificationEntityManager;

    /**
     * @param \Spryker\Zed\PushNotification\Persistence\PushNotificationEntityManagerInterface $pushNotificationEntityManager
     */
    public function __construct(PushNotificationEntityManagerInterface $pushNotificationEntityManager)
    {
        $this->pushNotificationEntityManager = $pushNotificationEntityManager;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionDeliveryLogTransfer> $pushNotificationSubscriptionDeliveryLogTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionDeliveryLogTransfer>
     */
    public function createPushNotificationSubscriptionDeliveryLogCollection(
        ArrayObject $pushNotificationSubscriptionDeliveryLogTransfers
    ): ArrayObject {
        return $this->getTransactionHandler()->handleTransaction(
            function () use ($pushNotificationSubscriptionDeliveryLogTransfers): ArrayObject {
                return $this->executeCreatePushNotificationSubscriptionDeliveryLogCollectionTransaction(
                    $pushNotificationSubscriptionDeliveryLogTransfers,
                );
            },
        );
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionDeliveryLogTransfer> $pushNotificationSubscriptionDeliveryLogTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionDeliveryLogTransfer>
     */
    protected function executeCreatePushNotificationSubscriptionDeliveryLogCollectionTransaction(
        ArrayObject $pushNotificationSubscriptionDeliveryLogTransfers
    ): ArrayObject {
        $persistedPushNotificationSubscriptionDeliveryLogTransfers = new ArrayObject();

        foreach ($pushNotificationSubscriptionDeliveryLogTransfers as $pushNotificationSubscriptionDeliveryLogTransfer) {
            $persistedPushNotificationSubscriptionDeliveryLogTransfer = $this
                ->pushNotificationEntityManager
                ->createPushNotificationSubscriptionDeliverLog(
                    $pushNotificationSubscriptionDeliveryLogTransfer,
                );
            $persistedPushNotificationSubscriptionDeliveryLogTransfers->append(
                $persistedPushNotificationSubscriptionDeliveryLogTransfer,
            );
        }

        return $persistedPushNotificationSubscriptionDeliveryLogTransfers;
    }
}
