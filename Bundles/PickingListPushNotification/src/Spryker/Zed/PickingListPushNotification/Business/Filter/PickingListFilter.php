<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingListPushNotification\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\PickingListTransfer;
use Spryker\Zed\PickingListPushNotification\PickingListPushNotificationConfig;

class PickingListFilter implements PickingListFilterInterface
{
    /**
     * @var \Spryker\Zed\PickingListPushNotification\PickingListPushNotificationConfig
     */
    protected PickingListPushNotificationConfig $pickingListPushNotificationConfig;

    /**
     * @param \Spryker\Zed\PickingListPushNotification\PickingListPushNotificationConfig $pickingListPushNotificationConfig
     */
    public function __construct(PickingListPushNotificationConfig $pickingListPushNotificationConfig)
    {
        $this->pickingListPushNotificationConfig = $pickingListPushNotificationConfig;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PickingListTransfer> $pickingListTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PickingListTransfer>
     */
    public function filterNotifiablePickingLists(ArrayObject $pickingListTransfers): ArrayObject
    {
        $filteredPickingListTransfers = new ArrayObject();
        /** @var \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer */
        foreach ($pickingListTransfers as $pickingListTransfer) {
            if (!$this->hasModifiedNotifiableProperty($pickingListTransfer)) {
                continue;
            }
            $filteredPickingListTransfers->append($pickingListTransfer);
        }

        return $filteredPickingListTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     *
     * @return bool
     */
    protected function hasModifiedNotifiableProperty(PickingListTransfer $pickingListTransfer): bool
    {
        $notifiableProperties = $this->pickingListPushNotificationConfig->getPickingListNotifiableAttributes();

        return array_intersect($notifiableProperties, $pickingListTransfer->getModifiedAttributes()) !== [];
    }
}
