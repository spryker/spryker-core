<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;

interface PushNotificationProviderFilterInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationProviderTransfer> $pushNotificationProviderTransfers
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationProviderTransfer>
     */
    public function filterOutInvalidPushNotificationProviders(
        ArrayObject $pushNotificationProviderTransfers,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): ArrayObject;
}
