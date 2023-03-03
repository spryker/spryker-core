<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Reader;

use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCriteriaTransfer;
use Spryker\Zed\PushNotification\Persistence\PushNotificationRepositoryInterface;

class PushNotificationSubscriptionReader implements PushNotificationSubscriptionReaderInterface
{
    /**
     * @var \Spryker\Zed\PushNotification\Persistence\PushNotificationRepositoryInterface
     */
    protected PushNotificationRepositoryInterface $pushNotificationRepository;

    /**
     * @param \Spryker\Zed\PushNotification\Persistence\PushNotificationRepositoryInterface $pushNotificationRepository
     */
    public function __construct(PushNotificationRepositoryInterface $pushNotificationRepository)
    {
        $this->pushNotificationRepository = $pushNotificationRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionCriteriaTransfer $pushNotificationSubscriptionCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionCollectionTransfer
     */
    public function getPushNotificationSubscriptionCollection(
        PushNotificationSubscriptionCriteriaTransfer $pushNotificationSubscriptionCriteriaTransfer
    ): PushNotificationSubscriptionCollectionTransfer {
        return $this
            ->pushNotificationRepository
            ->getPushNotificationSubscriptionCollection($pushNotificationSubscriptionCriteriaTransfer);
    }
}
