<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Reader;

use Generated\Shared\Transfer\PushNotificationGroupTransfer;

interface PushNotificationGroupReaderInterface
{
    /**
     * @param string $name
     * @param string|null $identifier
     *
     * @return \Generated\Shared\Transfer\PushNotificationGroupTransfer|null
     */
    public function findPushNotificationGroupByNameAndIdentifier(string $name, ?string $identifier): ?PushNotificationGroupTransfer;
}
