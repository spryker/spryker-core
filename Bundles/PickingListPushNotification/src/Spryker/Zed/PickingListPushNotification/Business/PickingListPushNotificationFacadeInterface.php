<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingListPushNotification\Business;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\PickingListCollectionResponseTransfer;
use Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer;

interface PickingListPushNotificationFacadeInterface
{
    /**
     * Specification:
     * - Requires `PushNotificationCollectionRequest.action` to be set.
     * - Requires `PickingList.uuid`, `PickingList.warehouse.uuid` to be set for each element in `PushNotificationCollectionRequest.pickingLists`.
     * - Filters picking lists considering `PickingList.modifiedAttributes()` and {@link \Spryker\Zed\PickingListPushNotification\PickingListPushNotificationConfig::getPickingListNotifiableAttributes()}.
     * - Groups picking lists by `PickingList.warehouse.uuid`.
     * - Creates push notification for each picking list group.
     * - Uses {@link \Spryker\Zed\PushNotification\Business\PushNotificationFacade::createPushNotificationCollection()} to create push notifications.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer $pushNotificationCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionResponseTransfer
     */
    public function createPushNotificationCollection(
        PushNotificationCollectionRequestTransfer $pushNotificationCollectionRequestTransfer
    ): PickingListCollectionResponseTransfer;

    /**
     * Specification:
     * - Requires `PushNotificationSubscription.user.uuid`, `PushNotificationSubscription.group.identifier` to be set.
     * - Calls {@link \Spryker\Zed\WarehouseUser\Business\WarehouseUserFacade::getWarehouseUserAssignmentCollection()} to get warehouse user assignment collection.
     * - Return a collection of validation errors in the case when no active warehouse user assignment was not found.
     *
     * @api
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validateSubscriptions(ArrayObject $pushNotificationSubscriptionTransfers): ErrorCollectionTransfer;
}
