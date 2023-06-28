<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi\Processor\Reader;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\PushNotificationProviderTransfer;

interface PushNotificationProviderReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getPushNotificationProviderCollection(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getPushNotificationProvider(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer;

    /**
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderTransfer|null
     */
    public function findPushNotificationProviderByUuid(string $uuid): ?PushNotificationProviderTransfer;
}
