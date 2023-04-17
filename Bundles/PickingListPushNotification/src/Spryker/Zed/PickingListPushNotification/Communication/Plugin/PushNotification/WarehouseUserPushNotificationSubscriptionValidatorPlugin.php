<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingListPushNotification\Communication\Plugin\PushNotification;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PushNotificationExtension\Dependency\Plugin\PushNotificationSubscriptionValidatorPluginInterface;

/**
 * @method \Spryker\Zed\PickingListPushNotification\Business\PickingListPushNotificationFacadeInterface getFacade()
 * @method \Spryker\Zed\PickingListPushNotification\PickingListPushNotificationConfig getConfig()
 * @method \Spryker\Zed\PickingListPushNotification\Communication\PickingListPushNotificationCommunicationFactory getFactory()
 */
class WarehouseUserPushNotificationSubscriptionValidatorPlugin extends AbstractPlugin implements PushNotificationSubscriptionValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `PushNotificationSubscriptionTransfer.user.uuid` and `PushNotificationSubscriptionTransfer.group.identifier` to be set.
     * - Calls {@link \Spryker\Zed\WarehouseUser\Business\WarehouseUserFacade::getWarehouseUserAssignmentCollection()} to get warehouse user assignment collection.
     * - Return a collection of validation errors in the case when no active warehouse user assignment was not found.
     *
     * @api
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $pushNotificationSubscriptionTransfers): ErrorCollectionTransfer
    {
        return $this->getFacade()->validateSubscriptions($pushNotificationSubscriptionTransfers);
    }
}
