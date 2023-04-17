<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingListPushNotification\Business\Generator;

interface PushNotificationPayloadGeneratorInterface
{
    /**
     * @param array<int, \Generated\Shared\Transfer\PickingListTransfer> $pickingListTransfers
     * @param string $action
     *
     * @return array<string, mixed>
     */
    public function generatePushNotificationPayload(array $pickingListTransfers, string $action): array;
}
