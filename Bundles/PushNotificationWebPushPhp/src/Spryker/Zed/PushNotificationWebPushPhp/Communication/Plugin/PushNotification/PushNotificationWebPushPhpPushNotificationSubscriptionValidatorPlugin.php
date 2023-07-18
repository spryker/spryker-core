<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationWebPushPhp\Communication\Plugin\PushNotification;

use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PushNotificationExtension\Dependency\Plugin\PushNotificationSubscriptionValidatorPluginInterface;

/**
 * @method \Spryker\Zed\PushNotificationWebPushPhp\Business\PushNotificationWebPushPhpFacadeInterface getFacade()
 * @method \Spryker\Zed\PushNotificationWebPushPhp\PushNotificationWebPushPhpConfig getConfig()
 * @method \Spryker\Zed\PushNotificationWebPushPhp\Communication\PushNotificationWebPushPhpCommunicationFactory getFactory()
 */
class PushNotificationWebPushPhpPushNotificationSubscriptionValidatorPlugin extends AbstractPlugin implements PushNotificationSubscriptionValidatorPluginInterface
{
    /**
     * {@inheritDoc}
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
    public function validate(
        PushNotificationSubscriptionCollectionTransfer $pushNotificationSubscriptionCollectionTransfer
    ): ErrorCollectionTransfer {
        return $this->getFacade()->validateSubscriptions($pushNotificationSubscriptionCollectionTransfer);
    }
}
