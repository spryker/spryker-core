<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi\Dependency\Facade;

use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionResponseTransfer;

class PushNotificationsBackendApiToPushNotificationFacadeBridge implements PushNotificationsBackendApiToPushNotificationFacadeInterface
{
    /**
     * @var \Spryker\Zed\PushNotification\Business\PushNotificationFacadeInterface
     */
    protected $pushNotificationFacade;

    /**
     * @param \Spryker\Zed\PushNotification\Business\PushNotificationFacadeInterface $pushNotificationFacade
     */
    public function __construct($pushNotificationFacade)
    {
        $this->pushNotificationFacade = $pushNotificationFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionCollectionRequestTransfer $pushNotificationSubscriptionCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionCollectionResponseTransfer
     */
    public function createPushNotificationSubscriptionCollection(
        PushNotificationSubscriptionCollectionRequestTransfer $pushNotificationSubscriptionCollectionRequestTransfer
    ): PushNotificationSubscriptionCollectionResponseTransfer {
        return $this->pushNotificationFacade->createPushNotificationSubscriptionCollection(
            $pushNotificationSubscriptionCollectionRequestTransfer,
        );
    }
}
