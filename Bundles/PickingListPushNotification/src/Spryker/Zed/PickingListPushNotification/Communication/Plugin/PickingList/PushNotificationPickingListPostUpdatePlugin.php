<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingListPushNotification\Communication\Plugin\PickingList;

use Generated\Shared\Transfer\PickingListCollectionResponseTransfer;
use Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListPostUpdatePluginInterface;

/**
 * @method \Spryker\Zed\PickingListPushNotification\Business\PickingListPushNotificationFacadeInterface getFacade()
 * @method \Spryker\Zed\PickingListPushNotification\PickingListPushNotificationConfig getConfig()
 * @method \Spryker\Zed\PickingListPushNotification\Communication\PickingListPushNotificationCommunicationFactory getFactory()
 */
class PushNotificationPickingListPostUpdatePlugin extends AbstractPlugin implements PickingListPostUpdatePluginInterface
{
    /**
     * @var string
     */
    protected const ACTION_UPDATE = 'update';

    /**
     * @inheritDoc
     * - Requires `PushNotificationCollectionRequest.action` to be set.
     * - Requires `PickingList.uuid`, `PickingList.warehouse.uuid` to be set for each element in `PushNotificationCollectionRequest.pickingLists`.
     * - Filters picking lists considering `PickingList.modifiedAttributes()` and {@link \Spryker\Zed\PickingListPushNotification\PickingListPushNotificationConfig::getPickingListNotifiableAttributes()}.
     * - Groups picking lists by `PickingList.warehouse.uuid`.
     * - Creates push notification for each picking list group.
     * - Uses {@link \Spryker\Zed\PushNotification\Business\PushNotificationFacade::createPushNotificationCollection()} to create push notifications.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PickingListCollectionResponseTransfer $pickingListCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionResponseTransfer
     */
    public function postUpdate(
        PickingListCollectionResponseTransfer $pickingListCollectionResponseTransfer
    ): PickingListCollectionResponseTransfer {
        $pushNotificationCollectionRequestTransfer = (new PushNotificationCollectionRequestTransfer())
            ->setPickingLists($pickingListCollectionResponseTransfer->getPickingLists())
            ->setAction(static::ACTION_UPDATE);

        return $this->getFacade()
            ->createPushNotificationCollection($pushNotificationCollectionRequestTransfer);
    }
}
