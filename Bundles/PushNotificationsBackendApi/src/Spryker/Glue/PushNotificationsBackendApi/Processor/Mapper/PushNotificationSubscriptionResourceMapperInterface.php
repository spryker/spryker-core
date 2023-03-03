<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\ApiPushNotificationSubscriptionAttributesTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionTransfer;

interface PushNotificationSubscriptionResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiPushNotificationSubscriptionAttributesTransfer $apiPushNotificationSubscriptionAttributesTransfer
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer
     */
    public function mapApiPushNotificationSubscriptionAttributesTransferToPushNotificationSubscriptionTransfer(
        ApiPushNotificationSubscriptionAttributesTransfer $apiPushNotificationSubscriptionAttributesTransfer,
        PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
    ): PushNotificationSubscriptionTransfer;

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     * @param \Generated\Shared\Transfer\ApiPushNotificationSubscriptionAttributesTransfer $apiPushNotificationSubscriptionAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ApiPushNotificationSubscriptionAttributesTransfer
     */
    public function mapPushNotificationSubscriptionTransferToApiPushNotificationSubscriptionAttributesTransfer(
        PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer,
        ApiPushNotificationSubscriptionAttributesTransfer $apiPushNotificationSubscriptionAttributesTransfer
    ): ApiPushNotificationSubscriptionAttributesTransfer;

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
