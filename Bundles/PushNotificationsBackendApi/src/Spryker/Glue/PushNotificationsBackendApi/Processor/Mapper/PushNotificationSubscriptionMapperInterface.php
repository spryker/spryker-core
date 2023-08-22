<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionTransfer;

interface PushNotificationSubscriptionMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionsBackendApiAttributesTransfer $pushNotificationSubscriptionsBackendApiAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionsBackendApiAttributesTransfer
     */
    public function mapPushNotificationSubscriptionTransferToPushNotificationSubscriptionsBackendApiAttributesTransfer(
        PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer,
        PushNotificationSubscriptionsBackendApiAttributesTransfer $pushNotificationSubscriptionsBackendApiAttributesTransfer
    ): PushNotificationSubscriptionsBackendApiAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionsBackendApiAttributesTransfer $pushNotificationSubscriptionsBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer
     */
    public function mapPushNotificationSubscriptionsBackendApiAttributesTransferToPushNotificationSubscriptionTransfer(
        PushNotificationSubscriptionsBackendApiAttributesTransfer $pushNotificationSubscriptionsBackendApiAttributesTransfer,
        PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
    ): PushNotificationSubscriptionTransfer;

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
