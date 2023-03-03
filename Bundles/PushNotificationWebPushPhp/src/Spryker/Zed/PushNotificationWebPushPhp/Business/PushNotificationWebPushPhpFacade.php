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
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\PushNotificationWebPushPhp\Business\PushNotificationWebPushPhpBusinessFactory getFactory()
 * @method \Spryker\Zed\PushNotificationWebPushPhp\Persistence\PushNotificationWebPushPhpRepositoryInterface getRepository()
 * @method \Spryker\Zed\PushNotificationWebPushPhp\Persistence\PushNotificationWebPushPhpEntityManagerInterface getEntityManager()
 */
class PushNotificationWebPushPhpFacade extends AbstractFacade implements PushNotificationWebPushPhpFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validateSubscriptions(
        ArrayObject $pushNotificationSubscriptionTransfers
    ): ErrorCollectionTransfer {
        return $this->getFactory()
            ->createPushNotificationSubscriptionPayloadStructureValidator()
            ->validateSubscriptions($pushNotificationSubscriptionTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationTransfer> $pushNotificationTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validatePayloadLength(
        ArrayObject $pushNotificationTransfers
    ): ErrorCollectionTransfer {
        return $this->getFactory()
            ->createPushNotificationPayloadLengthValidator()
            ->validatePayloadLength($pushNotificationTransfers);
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
