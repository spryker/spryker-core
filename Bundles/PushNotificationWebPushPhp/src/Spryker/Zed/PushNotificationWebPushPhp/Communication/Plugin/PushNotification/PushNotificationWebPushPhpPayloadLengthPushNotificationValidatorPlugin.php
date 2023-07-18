<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationWebPushPhp\Communication\Plugin\PushNotification;

use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PushNotificationExtension\Dependency\Plugin\PushNotificationValidatorPluginInterface;

/**
 * @method \Spryker\Zed\PushNotificationWebPushPhp\Business\PushNotificationWebPushPhpFacadeInterface getFacade()
 * @method \Spryker\Zed\PushNotificationWebPushPhp\PushNotificationWebPushPhpConfig getConfig()
 * @method \Spryker\Zed\PushNotificationWebPushPhp\Communication\PushNotificationWebPushPhpCommunicationFactory getFactory()
 */
class PushNotificationWebPushPhpPayloadLengthPushNotificationValidatorPlugin extends AbstractPlugin implements PushNotificationValidatorPluginInterface
{
    /**
     * {@inheritDoc}
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
    public function validate(
        PushNotificationCollectionTransfer $pushNotificationCollectionTransfer
    ): ErrorCollectionTransfer {
        return $this->getFacade()
            ->validatePayloadLength($pushNotificationCollectionTransfer);
    }
}
