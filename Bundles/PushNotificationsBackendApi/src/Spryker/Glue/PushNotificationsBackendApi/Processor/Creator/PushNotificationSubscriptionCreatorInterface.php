<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi\Processor\Creator;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionsBackendApiAttributesTransfer;

interface PushNotificationSubscriptionCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionsBackendApiAttributesTransfer $pushNotificationSubscriptionsBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createPushNotificationSubscription(
        PushNotificationSubscriptionsBackendApiAttributesTransfer $pushNotificationSubscriptionsBackendApiAttributesTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer;
}
