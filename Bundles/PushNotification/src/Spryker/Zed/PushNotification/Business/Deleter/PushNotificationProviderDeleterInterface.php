<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Deleter;

use Generated\Shared\Transfer\PushNotificationProviderCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCollectionResponseTransfer;

interface PushNotificationProviderDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\PushNotificationProviderCollectionDeleteCriteriaTransfer $pushNotificationProviderCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderCollectionResponseTransfer
     */
    public function deletePushNotificationProviderCollection(
        PushNotificationProviderCollectionDeleteCriteriaTransfer $pushNotificationProviderCollectionDeleteCriteriaTransfer
    ): PushNotificationProviderCollectionResponseTransfer;
}
