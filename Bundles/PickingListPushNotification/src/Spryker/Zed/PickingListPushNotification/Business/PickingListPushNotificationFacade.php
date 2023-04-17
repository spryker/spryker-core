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
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\PickingListPushNotification\Business\PickingListPushNotificationBusinessFactory getFactory()
 */
class PickingListPushNotificationFacade extends AbstractFacade implements PickingListPushNotificationFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer $pushNotificationCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionResponseTransfer
     */
    public function createPushNotificationCollection(
        PushNotificationCollectionRequestTransfer $pushNotificationCollectionRequestTransfer
    ): PickingListCollectionResponseTransfer {
        return $this->getFactory()
            ->createPushNotificationCreator()
            ->createPushNotificationCollection($pushNotificationCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validateSubscriptions(ArrayObject $pushNotificationSubscriptionTransfers): ErrorCollectionTransfer
    {
        return $this->getFactory()
            ->createPushNotificationSubscriptionWarehouseUserAssignmentValidator()
            ->validateSubscriptions($pushNotificationSubscriptionTransfers);
    }
}
