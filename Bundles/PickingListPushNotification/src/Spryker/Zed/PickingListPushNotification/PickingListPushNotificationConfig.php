<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingListPushNotification;

use Generated\Shared\Transfer\PickingListTransfer;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\PickingListPushNotification\Business\Exception\PushNotificationProviderException;

class PickingListPushNotificationConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const PUSH_NOTIFICATION_WAREHOUSE_GROUP = 'warehouse';

    /**
     * @var string
     */
    protected const MESSAGE_PUSH_NOTIFICATION_PROVIDER_IS_NOT_CONFIGURED = 'Push notification provider is not configured.';

    /**
     * Specification:
     * - Returns a name of push notification provider which should be used to send push notifications.
     *
     * @api
     *
     * @throws \Spryker\Zed\PickingListPushNotification\Business\Exception\PushNotificationProviderException
     *
     * @return string
     */
    public function getPushNotificationProviderName(): string
    {
        throw new PushNotificationProviderException(static::MESSAGE_PUSH_NOTIFICATION_PROVIDER_IS_NOT_CONFIGURED);
    }

    /**
     * Specification:
     * - Returns the push notification warehouse group name which will be used for push notification creation.
     *
     * @api
     *
     * @return string
     */
    public function getPushNotificationWarehouseGroup(): string
    {
        return static::PUSH_NOTIFICATION_WAREHOUSE_GROUP;
    }

    /**
     * Specification:
     * - Return the list of `PickingList` transfer properties which will be used to determine if `PushNotification` should be created.
     *
     * @api
     *
     * @return list<string>
     */
    public function getPickingListNotifiableAttributes(): array
    {
        return [
            PickingListTransfer::ID_PICKING_LIST,
            PickingListTransfer::STATUS,
        ];
    }
}
