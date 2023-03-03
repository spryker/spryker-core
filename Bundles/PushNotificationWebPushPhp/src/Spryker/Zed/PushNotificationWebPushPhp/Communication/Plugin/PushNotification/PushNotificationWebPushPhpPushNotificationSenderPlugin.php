<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationWebPushPhp\Communication\Plugin\PushNotification;

use Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PushNotificationExtension\Dependency\Plugin\PushNotificationSenderPluginInterface;

/**
 * @method \Spryker\Zed\PushNotificationWebPushPhp\Business\PushNotificationWebPushPhpFacadeInterface getFacade()
 * @method \Spryker\Zed\PushNotificationWebPushPhp\PushNotificationWebPushPhpConfig getConfig()
 * @method \Spryker\Zed\PushNotificationWebPushPhp\Communication\PushNotificationWebPushPhpCommunicationFactory getFactory()
 */
class PushNotificationWebPushPhpPushNotificationSenderPlugin extends AbstractPlugin implements PushNotificationSenderPluginInterface
{
    /**
     * {@inheritDoc}
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
    public function send(PushNotificationCollectionRequestTransfer $pushNotificationCollectionRequestTransfer): PushNotificationCollectionResponseTransfer
    {
        return $this->getFacade()->sendNotifications($pushNotificationCollectionRequestTransfer);
    }
}
