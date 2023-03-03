<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationWebPushPhp\Communication\Plugin\PushNotification;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
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
    public function validate(
        ArrayObject $pushNotificationSubscriptionTransfers
    ): ErrorCollectionTransfer {
        return $this->getFacade()->validateSubscriptions($pushNotificationSubscriptionTransfers);
    }
}
