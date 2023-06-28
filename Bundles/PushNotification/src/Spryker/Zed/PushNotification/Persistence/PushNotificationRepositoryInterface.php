<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Persistence;

use Generated\Shared\Transfer\PushNotificationCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationCriteriaTransfer;
use Generated\Shared\Transfer\PushNotificationGroupCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationGroupCriteriaTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCriteriaTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCriteriaTransfer;

interface PushNotificationRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\PushNotificationCriteriaTransfer $pushNotificationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationCollectionTransfer
     */
    public function getPushNotificationCollection(
        PushNotificationCriteriaTransfer $pushNotificationCriteriaTransfer
    ): PushNotificationCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\PushNotificationProviderCriteriaTransfer $pushNotificationProviderCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderCollectionTransfer
     */
    public function getPushNotificationProviderCollection(
        PushNotificationProviderCriteriaTransfer $pushNotificationProviderCriteriaTransfer
    ): PushNotificationProviderCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\PushNotificationGroupCriteriaTransfer $pushNotificationGroupCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationGroupCollectionTransfer
     */
    public function getPushNotificationGroupCollection(
        PushNotificationGroupCriteriaTransfer $pushNotificationGroupCriteriaTransfer
    ): PushNotificationGroupCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionCriteriaTransfer $pushNotificationSubscriptionCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionCollectionTransfer
     */
    public function getPushNotificationSubscriptionCollection(
        PushNotificationSubscriptionCriteriaTransfer $pushNotificationSubscriptionCriteriaTransfer
    ): PushNotificationSubscriptionCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionCriteriaTransfer $pushNotificationSubscriptionCriteriaTransfer
     *
     * @return bool
     */
    public function pushNotificationSubscriptionExists(
        PushNotificationSubscriptionCriteriaTransfer $pushNotificationSubscriptionCriteriaTransfer
    ): bool;

    /**
     * @param \Generated\Shared\Transfer\PushNotificationCriteriaTransfer $pushNotificationCriteriaTransfer
     *
     * @return bool
     */
    public function pushNotificationExists(
        PushNotificationCriteriaTransfer $pushNotificationCriteriaTransfer
    ): bool;
}
