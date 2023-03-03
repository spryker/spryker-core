<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationWebPushPhp\Communication\Plugin\PushNotification;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
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
    public function validate(
        ArrayObject $pushNotificationTransfers
    ): ErrorCollectionTransfer {
        return $this->getFacade()
            ->validatePayloadLength($pushNotificationTransfers);
    }
}
