<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;

interface PushNotificationFilterInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationTransfer> $pushNotificationTransfers
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \ArrayObject<\Generated\Shared\Transfer\PushNotificationTransfer>
     */
    public function filterOutInvalidPushNotifications(
        ArrayObject $pushNotificationTransfers,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): ArrayObject;

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationTransfer> $pushNotificationTransfers
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \ArrayObject<\Generated\Shared\Transfer\PushNotificationTransfer>
     */
    public function filterOutValidPushNotifications(
        ArrayObject $pushNotificationTransfers,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): ArrayObject;
}
