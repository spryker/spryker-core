<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationWebPushPhp\Business;

use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer;
use Generated\Shared\Transfer\PushNotificationCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionTransfer;

interface PushNotificationWebPushPhpFacadeInterface
{
    /**
     * Specification:
     * - Requires `PushNotificationSubscriptionCollectionTransfer.pushNotificationSubscription.provider.name` transfer field to be set.
     * - Applies when `PushNotificationSubscriptionCollectionTransfer.pushNotificationSubscription.provider.name` is equal to {@link \Spryker\Zed\PushNotificationWebPushPhp\PushNotificationWebPushPhpConfig::WEB_PUSH_PHP_PROVIDER_NAME} only.
     * - Validates `PushNotificationSubscriptionCollectionTransfer.pushNotificationSubscription.payload` format.
     * - The following payload keys combinations considered as valid:
     * - `PushNotificationSubscriptionCollectionTransfer.pushNotificationSubscription.payload.endpoint`.
     * - `PushNotificationSubscriptionCollectionTransfer.pushNotificationSubscription.payload.endpoint` + `PushNotificationSubscriptionCollectionTransfer.pushNotificationSubscription.payload.publicKey` + `PushNotificationSubscriptionCollectionTransfer.pushNotificationSubscription.payload.authToken`.
     * - `PushNotificationSubscriptionCollectionTransfer.pushNotificationSubscription.payload.endpoint` + `PushNotificationSubscriptionCollectionTransfer.pushNotificationSubscription.payload.keys.p256dh` + `PushNotificationSubscriptionCollectionTransfer.pushNotificationSubscription.payload.keys.auth`.
     * - Returns a collection of validation errors.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionCollectionTransfer $pushNotificationSubscriptionCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validateSubscriptions(
        PushNotificationSubscriptionCollectionTransfer $pushNotificationSubscriptionCollectionTransfer
    ): ErrorCollectionTransfer;

    /**
     * Specification:
     * - Requires `PushNotificationCollectionTransfer.pushNotification.provider.name` transfer field to be set.
     * - Applies when `PushNotificationCollectionTransfer.pushNotification.provider.name` is equal to {@link \Spryker\Zed\PushNotificationWebPushPhp\PushNotificationWebPushPhpConfig::WEB_PUSH_PHP_PROVIDER_NAME} only.
     * - Encodes `PushNotificationCollectionTransfer.pushNotification.payload` to JSON.
     * - Validates whether the length of the payload is exceeded {@link \Spryker\Zed\PushNotificationWebPushPhp\PushNotificationWebPushPhpConfig::getPushNotificationPayloadMaxLength()}.
     * - Returns a collection of validation errors.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PushNotificationCollectionTransfer $pushNotificationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validatePayloadLength(
        PushNotificationCollectionTransfer $pushNotificationCollectionTransfer
    ): ErrorCollectionTransfer;

    /**
     * Specification:
     * - Requires `PushNotificationSubscriptionTransfer.provider.name` transfer field to be set.
     * - Filters push notifications with {@link \Spryker\Zed\PushNotificationWebPushPhp\PushNotificationWebPushPhpConfig::WEB_PUSH_PHP_PROVIDER_NAME} push notification provider.
     * - Sends given push notification collection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer $pushNotificationCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer
     */
    public function sendNotifications(
        PushNotificationCollectionRequestTransfer $pushNotificationCollectionRequestTransfer
    ): PushNotificationCollectionResponseTransfer;

    /**
     * Specification:
     * - Gets push notification provider with {@link \Spryker\Zed\PushNotificationWebPushPhp\PushNotificationWebPushPhpConfig::WEB_PUSH_PHP_PROVIDER_NAME} name by {@link \Spryker\Zed\PushNotification\Business\PushNotificationFacadeInterface::getPushNotificationProviderCollection()}.
     * - Skips if provider already exists.
     * - Creates push notification provider with {@link \Spryker\Zed\PushNotificationWebPushPhp\PushNotificationWebPushPhpConfig::WEB_PUSH_PHP_PROVIDER_NAME} name by {@link \Spryker\Zed\PushNotification\Business\PushNotificationFacadeInterface::createPushNotificationProviderCollection()}.
     *
     * @api
     *
     * @return void
     */
    public function installWebPushPhpProvider(): void;
}
