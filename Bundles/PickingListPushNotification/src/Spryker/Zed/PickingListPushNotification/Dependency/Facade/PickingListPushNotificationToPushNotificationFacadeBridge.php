<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingListPushNotification\Dependency\Facade;

use Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer;

class PickingListPushNotificationToPushNotificationFacadeBridge implements PickingListPushNotificationToPushNotificationFacadeInterface
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
     * @param \Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer $pushNotificationCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer
     */
    public function createPushNotificationCollection(
        PushNotificationCollectionRequestTransfer $pushNotificationCollectionRequestTransfer
    ): PushNotificationCollectionResponseTransfer {
        return $this->pushNotificationFacade->createPushNotificationCollection($pushNotificationCollectionRequestTransfer);
    }
}
