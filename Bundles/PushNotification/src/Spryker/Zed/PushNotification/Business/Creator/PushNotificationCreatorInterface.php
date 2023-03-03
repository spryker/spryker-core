<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Creator;

use Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer;

interface PushNotificationCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer $pushNotificationCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer
     */
    public function createPushNotificationCollection(
        PushNotificationCollectionRequestTransfer $pushNotificationCollectionRequestTransfer
    ): PushNotificationCollectionResponseTransfer;
}
