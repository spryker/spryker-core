<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Validator;

use Generated\Shared\Transfer\PushNotificationProviderCollectionResponseTransfer;

interface PushNotificationProviderValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\PushNotificationProviderCollectionResponseTransfer $pushNotificationProviderCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderCollectionResponseTransfer
     */
    public function validate(
        PushNotificationProviderCollectionResponseTransfer $pushNotificationProviderCollectionResponseTransfer
    ): PushNotificationProviderCollectionResponseTransfer;
}
