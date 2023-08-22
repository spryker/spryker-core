<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi\Controller;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionsBackendApiAttributesTransfer;
use Spryker\Glue\Kernel\Backend\Controller\AbstractBackendApiController;

/**
 * @method \Spryker\Glue\PushNotificationsBackendApi\PushNotificationsBackendApiFactory getFactory()
 */
class PushNotificationSubscriptionsResourceController extends AbstractBackendApiController
{
    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Creates a push notification subscription."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responses": {
     *              "201": "Expected response to a valid request.",
     *              "400": "Expected response to a bad request."
     *          },
     *          "requestAttributesClassName": "\\Generated\\Shared\\Transfer\\PushNotificationSubscriptionsBackendApiAttributesTransfer",
     *          "responseAttributesClassName": "\\Generated\\Shared\\Transfer\\PushNotificationSubscriptionsBackendApiAttributesTransfer"
     *     }
     * })
     *
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionsBackendApiAttributesTransfer $pushNotificationSubscriptionsBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function postAction(
        PushNotificationSubscriptionsBackendApiAttributesTransfer $pushNotificationSubscriptionsBackendApiAttributesTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        return $this->getFactory()
            ->createPushNotificationSubscriptionCreator()
            ->createPushNotificationSubscription(
                $pushNotificationSubscriptionsBackendApiAttributesTransfer,
                $glueRequestTransfer,
            );
    }
}
