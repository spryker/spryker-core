<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationWebPushPhp\Business;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer;

interface PushNotificationWebPushPhpFacadeInterface
{
    /**
     * Specification:
     * - Requires `PushNotificationSubscriptionTransfer.provider.name` transfer field to be set.
     * - Applies when `PushNotificationSubscriptionTransfer.provider.name` is equal to {@link \Spryker\Zed\PushNotificationWebPushPhp\PushNotificationWebPushPhpConfig::WEB_PUSH_PHP_PROVIDER_NAME} only.
     * - Validates `PushNotificationSubscription.payload` format.
     * - The following payload keys combinations considered as valid:
     * - `PushNotificationSubscription.payload.endpoint`.
     * - `PushNotificationSubscription.payload.endpoint` + `PushNotificationSubscription.payload.publicKey` + `PushNotificationSubscription.payload.authToken`.
     * - `PushNotificationSubscription.payload.endpoint` + `PushNotificationSubscription.payload.keys.p256dh` + `PushNotificationSubscription.payload.keys.auth`.
     * - Returns a collection of validation errors.
     *
     * @api
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validateSubscriptions(
        ArrayObject $pushNotificationSubscriptionTransfers
    ): ErrorCollectionTransfer;

    /**
     * Specification:
     * - Requires `PushNotificationTransfer.provider.name` transfer field to be set.
     * - Applies when `PushNotificationSubscriptionTransfer.provider.name` is equal to {@link \Spryker\Zed\PushNotificationWebPushPhp\PushNotificationWebPushPhpConfig::WEB_PUSH_PHP_PROVIDER_NAME} only.
     * - Json encodes of `PushNotificationTransfer.payload`.
     * - Validates whether the length of the payload is exceeded {@link \Spryker\Zed\PushNotificationWebPushPhp\PushNotificationWebPushPhpConfig::getPushNotificationPayloadMaxLength()}.
     * - Returns a collection of validation errors.
     *
     * @api
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationTransfer> $pushNotificationTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validatePayloadLength(
        ArrayObject $pushNotificationTransfers
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
