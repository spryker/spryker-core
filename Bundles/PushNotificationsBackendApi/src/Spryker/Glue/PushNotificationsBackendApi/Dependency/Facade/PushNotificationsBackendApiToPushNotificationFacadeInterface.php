<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi\Dependency\Facade;

use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionResponseTransfer;

interface PushNotificationsBackendApiToPushNotificationFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionCollectionRequestTransfer $pushNotificationSubscriptionCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionCollectionResponseTransfer
     */
    public function createPushNotificationSubscriptionCollection(
        PushNotificationSubscriptionCollectionRequestTransfer $pushNotificationSubscriptionCollectionRequestTransfer
    ): PushNotificationSubscriptionCollectionResponseTransfer;
}
