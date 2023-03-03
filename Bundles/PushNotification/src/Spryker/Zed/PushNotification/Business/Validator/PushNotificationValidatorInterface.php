<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;

interface PushNotificationValidatorInterface
{
    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\PushNotificationTransfer> $pushNotificationTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validateCollection(
        ArrayObject $pushNotificationTransfers
    ): ErrorCollectionTransfer;
}
