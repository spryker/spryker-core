<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\ApiPushNotificationSubscriptionsAttributesTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionTransfer;

interface PushNotificationSubscriptionResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiPushNotificationSubscriptionsAttributesTransfer $apiPushNotificationSubscriptionsAttributesTransfer
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer
     */
    public function mapApiPushNotificationSubscriptionsAttributesTransferToPushNotificationSubscriptionTransfer(
        ApiPushNotificationSubscriptionsAttributesTransfer $apiPushNotificationSubscriptionsAttributesTransfer,
        PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
    ): PushNotificationSubscriptionTransfer;

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     * @param \Generated\Shared\Transfer\ApiPushNotificationSubscriptionsAttributesTransfer $apiPushNotificationSubscriptionsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ApiPushNotificationSubscriptionsAttributesTransfer
     */
    public function mapPushNotificationSubscriptionTransferToApiPushNotificationSubscriptionsAttributesTransfer(
        PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer,
        ApiPushNotificationSubscriptionsAttributesTransfer $apiPushNotificationSubscriptionsAttributesTransfer
    ): ApiPushNotificationSubscriptionsAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer
     */
    public function mapGlueRequestTransferToPushNotificationSubscriptionTransfer(
        GlueRequestTransfer $glueRequestTransfer,
        PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
    ): PushNotificationSubscriptionTransfer;
}
