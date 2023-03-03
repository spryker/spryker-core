<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Deleter;

use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionDeleteCriteriaTransfer;

interface PushNotificationSubscriptionDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionCollectionDeleteCriteriaTransfer $pushNotificationSubscriptionCollectionDeleteCriteriaTransfer
     *
     * @return void
     */
    public function deletePushNotificationSubscriptions(
        PushNotificationSubscriptionCollectionDeleteCriteriaTransfer $pushNotificationSubscriptionCollectionDeleteCriteriaTransfer
    ): void;
}
