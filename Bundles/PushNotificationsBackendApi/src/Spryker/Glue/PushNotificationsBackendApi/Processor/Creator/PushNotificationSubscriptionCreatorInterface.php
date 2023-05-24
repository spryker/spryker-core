<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi\Processor\Creator;

use Generated\Shared\Transfer\ApiPushNotificationSubscriptionsAttributesTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;

interface PushNotificationSubscriptionCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiPushNotificationSubscriptionsAttributesTransfer $apiPushNotificationSubscriptionsAttributesTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createPushNotificationSubscription(
        ApiPushNotificationSubscriptionsAttributesTransfer $apiPushNotificationSubscriptionsAttributesTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer;
}
