<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\PushNotificationProviderCollectionResponseTransfer;

interface PushNotificationProviderFilterInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\PushNotificationProviderTransfer> $validPushNotificationProviderTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\PushNotificationProviderTransfer> $invalidPushNotificationProviderTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\PushNotificationProviderTransfer>
     */
    public function mergePushNotificationProviders(
        ArrayObject $validPushNotificationProviderTransfers,
        ArrayObject $invalidPushNotificationProviderTransfers
    ): ArrayObject;

    /**
     * @param \Generated\Shared\Transfer\PushNotificationProviderCollectionResponseTransfer $pushNotificationProviderCollectionResponseTransfer
     *
     * @return array<\ArrayObject<array-key, \Generated\Shared\Transfer\PushNotificationProviderTransfer>>
     */
    public function filterPushNotificationProvidersByValidity(
        PushNotificationProviderCollectionResponseTransfer $pushNotificationProviderCollectionResponseTransfer
    ): array;
}
