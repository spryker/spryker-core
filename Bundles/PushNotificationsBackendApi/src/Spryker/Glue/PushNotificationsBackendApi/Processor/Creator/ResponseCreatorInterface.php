<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi\Processor\Creator;

use ArrayObject;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;

interface ResponseCreatorInterface
{
    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createPushNotificationSubscriptionResponse(
        ArrayObject $pushNotificationSubscriptionTransfers
    ): GlueResponseTransfer;

    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createPushNotificationSubscriptionErrorResponse(
        ArrayObject $errorTransfers,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer;
}
