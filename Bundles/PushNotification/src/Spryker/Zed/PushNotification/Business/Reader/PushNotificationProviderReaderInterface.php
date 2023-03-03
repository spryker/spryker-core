<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Reader;

use Generated\Shared\Transfer\PushNotificationProviderCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCriteriaTransfer;

interface PushNotificationProviderReaderInterface
{
    /**
     * @return array<string, \Generated\Shared\Transfer\PushNotificationProviderTransfer>
     */
    public function getPushNotificationProviderTransfersIndexedByName(): array;

    /**
     * @param \Generated\Shared\Transfer\PushNotificationProviderCriteriaTransfer $pushNotificationProviderCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderCollectionTransfer
     */
    public function getPushNotificationProviderCollection(
        PushNotificationProviderCriteriaTransfer $pushNotificationProviderCriteriaTransfer
    ): PushNotificationProviderCollectionTransfer;
}
