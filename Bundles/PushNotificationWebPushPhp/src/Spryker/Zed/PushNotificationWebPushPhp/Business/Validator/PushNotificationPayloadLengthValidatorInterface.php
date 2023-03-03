<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationWebPushPhp\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;

interface PushNotificationPayloadLengthValidatorInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationTransfer> $pushNotificationTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validatePayloadLength(
        ArrayObject $pushNotificationTransfers
    ): ErrorCollectionTransfer;
}
