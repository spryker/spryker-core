<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Persistence;

use Generated\Shared\Transfer\PushNotificationGroupTransfer;
use Generated\Shared\Transfer\PushNotificationProviderTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionDeliveryLogTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionTransfer;
use Generated\Shared\Transfer\PushNotificationTransfer;

interface PushNotificationEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer
     */
    public function createPushNotificationSubscription(
        PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
    ): PushNotificationSubscriptionTransfer;

    /**
     * @param \Generated\Shared\Transfer\PushNotificationTransfer $pushNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationTransfer
     */
    public function createPushNotification(
        PushNotificationTransfer $pushNotificationTransfer
    ): PushNotificationTransfer;

    /**
     * @param \Generated\Shared\Transfer\PushNotificationProviderTransfer $pushNotificationProviderTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderTransfer
     */
    public function createPushNotificationProvider(
        PushNotificationProviderTransfer $pushNotificationProviderTransfer
    ): PushNotificationProviderTransfer;

    /**
     * @param \Generated\Shared\Transfer\PushNotificationGroupTransfer $pushNotificationGroupTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationGroupTransfer
     */
    public function createPushNotificationGroup(
        PushNotificationGroupTransfer $pushNotificationGroupTransfer
    ): PushNotificationGroupTransfer;

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionDeliveryLogTransfer $pushNotificationSubscriptionDeliveryLogTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionDeliveryLogTransfer
     */
    public function createPushNotificationSubscriptionDeliverLog(
        PushNotificationSubscriptionDeliveryLogTransfer $pushNotificationSubscriptionDeliveryLogTransfer
    ): PushNotificationSubscriptionDeliveryLogTransfer;

    /**
     * @param \Generated\Shared\Transfer\PushNotificationProviderTransfer $pushNotificationProviderTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderTransfer
     */
    public function updatePushNotificationProvider(
        PushNotificationProviderTransfer $pushNotificationProviderTransfer
    ): PushNotificationProviderTransfer;

    /**
     * @param list<string> $pushNotificationProviderUuids
     *
     * @return void
     */
    public function deletePushNotificationProviders(
        array $pushNotificationProviderUuids
    ): void;

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     *
     * @return void
     */
    public function deletePushNotificationSubscription(PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer): void;
}
