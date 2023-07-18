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
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\PushNotificationWebPushPhp\Business\PushNotificationWebPushPhpBusinessFactory getFactory()
 */
class PushNotificationWebPushPhpFacade extends AbstractFacade implements PushNotificationWebPushPhpFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionCollectionTransfer $pushNotificationSubscriptionCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validateSubscriptions(
        PushNotificationSubscriptionCollectionTransfer $pushNotificationSubscriptionCollectionTransfer
    ): ErrorCollectionTransfer {
        return $this->getFactory()
            ->createPushNotificationSubscriptionPayloadStructureValidator()
            ->validateSubscriptions($pushNotificationSubscriptionCollectionTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PushNotificationCollectionTransfer $pushNotificationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validatePayloadLength(
        PushNotificationCollectionTransfer $pushNotificationCollectionTransfer
    ): ErrorCollectionTransfer {
        return $this->getFactory()
            ->createPushNotificationPayloadLengthValidator()
            ->validatePayloadLength($pushNotificationCollectionTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer $pushNotificationCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer
     */
    public function sendNotifications(
        PushNotificationCollectionRequestTransfer $pushNotificationCollectionRequestTransfer
    ): PushNotificationCollectionResponseTransfer {
        return $this->getFactory()
            ->createPushNotificationCollectionSender()
            ->sendNotifications($pushNotificationCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function installWebPushPhpProvider(): void
    {
        $this->getFactory()
            ->createPushNotificationProviderInstaller()
            ->installWebPushPhpProvider();
    }
}
