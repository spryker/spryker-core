<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Extractor;

use ArrayObject;

interface PushNotificationProviderExtractorInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\PushNotificationProviderTransfer> $pushNotificationProviderTransfers
     *
     * @return list<string>
     */
    public function extractPushNotificationProviderUuids(ArrayObject $pushNotificationProviderTransfers): array;
}
