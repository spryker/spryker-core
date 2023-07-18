<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingListPushNotification\Communication\Plugin\PushNotification;

use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionTransfer;
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
     * - Requires `PushNotificationSubscriptionCollectionTransfer.pushNotificationSubscription.user.uuid` to be set.
     * - Requires `PushNotificationSubscriptionCollectionTransfer.pushNotificationSubscription.group.identifier` to be set.
     * - Calls {@link \Spryker\Zed\WarehouseUser\Business\WarehouseUserFacade::getWarehouseUserAssignmentCollection()} to get warehouse user assignment collection.
     * - Returns a collection of validation errors when no active warehouse user assignment was found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionCollectionTransfer $pushNotificationSubscriptionCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(PushNotificationSubscriptionCollectionTransfer $pushNotificationSubscriptionCollectionTransfer): ErrorCollectionTransfer
    {
        return $this->getFacade()->validateSubscriptions($pushNotificationSubscriptionCollectionTransfer);
    }
}
